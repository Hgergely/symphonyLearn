<?php
/**
 * Created by PhpStorm.
 * User: Gergely Hajcsak
 * Date: 28/06/2017
 * Time: 08:51
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Program;
use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use AppBundle\Controller\MovieController;

class ProgramController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

//        $this->checkforOverlapping();
//        die;

        $insertResult=null;
        $program = new Program();

        $form =$this->initNewForm($program);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() holds the submitted values

            //check for time overlapping
            $movie = $program->getMovie();

            if($this->checkforOverlapping(clone($program->getDatetime()),$movie->getDuration())){
                $insertResult = "taken";
            }else{

                $duration = $movie->getDuration();
                $finish = clone($program->getDatetime());

                $finish->add(new \DateInterval('PT' . $duration . 'M'));
                $program->setFinish($finish);

                $program->setFinish($finish);

                $post=$form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($program);
                $em->flush();

                $insertResult="success";

                //init empty form

            }

            $program = new Program();
            $form =$this->initNewForm($program);

        }


        $currentweekArray = $this->getWeekDays(-1);

        return $this->render('program/index.html.twig', array(
            "form" => $form->createView(),
            "insertResult" => $insertResult,
            "RequestPath"=>$request->getRequestUri(),
            "programs"=>$this->getAll(),
            "weekdays"=>$currentweekArray,
            "offset"=>-1

        ));
    }

        function checkforOverlapping($startDateObj, $duration){


            //clone obj
            $finishdate= clone($startDateObj);

            //add 15 min time margin
            //$startDateObj->sub(new \DateInterval('PT15M'));

            //$shifted = 'PT' . ($duration+15) . 'M';
            //$finishdate->add(new \DateInterval($shifted));

            if ((int)$startDateObj->format('H')<8){
                return true;

            }

            if ((int)$finishdate->format('H')>=22 || (int)$finishdate->format('H')<8){
                return true;

            }


            $em = $this->getDoctrine()->getManager();

            $query = $em->createQuery(
                'select p
                from AppBundle:program  p
                where :startDateTime BETWEEN  p.datetime  and p.finish
                or :finishDateTime BETWEEN p.datetime and p.finish '
            )->setParameters(
                array(
                    "startDateTime"=>$startDateObj->format('Y-m-d H:i:s'),
                    "finishDateTime"=>$finishdate->format('Y-m-d H:i:s'),
                    )
            );


          $result = $query->getResult();



            if (empty($result)) return false;
            else return true;

    }

    public function programPartialAction($weekOffset){

        $currentweekArray = $this->getWeekDays($weekOffset);

        return $this->render('program/partial_program.html.twig', array(
            "programs"=>$this->getAll(),
            "weekdays"=>$currentweekArray,
            "offset"=>$weekOffset
        ));

    }

    /**
     * @param $weekOffset
     * @return array
     */
    function getWeekDays($weekOffset){

        $currentweekArray=[];
        $baseDate=  strtotime($weekOffset.' week monday');

        for ($i=0;$i<=6;$i++){

            $currentweekArray[]=[
                "timestamp"=>$baseDate,
                "display" => date("Y-m-d",$baseDate)
            ];
            $baseDate = strtotime('+1 day', $baseDate);

        }

        return $currentweekArray;
    }
    /**
     * @return array
     */
    public function getAll(){
        $allProgram =  $this->getDoctrine()
            ->getRepository('AppBundle:Program')
            ->findAll();

//        echo "<pre>";
//        print_r($allProgram);
//        echo "</pre>";

        return $allProgram;
    }


    /**
     * @return array
     */
    function allMoviesChoice(){
        return $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findAll();
    }

    /**
     * @param Program $program
     * @return mixed
     */
    function initNewForm(Program $program){

        return $this->createFormBuilder($program)

            ->add('movie', EntityType::class, array(
                'class' => 'AppBundle:Movie',
                'property' => 'label',
                ))
            ->add('datetime', DateTimeType::class)
            ->add('save', SubmitType::class, array('label' => 'Add Program'))
            ->getForm();
    }

}
