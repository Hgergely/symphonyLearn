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


        $insertResult=null;
        $program = new Program();

        $form =$this->initNewForm($program);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() holds the submitted values

            try {
                $post=$form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($program);
                $em->flush();
            } catch(\Doctrine\DBAL\DBALException $e) {
                $insertResult=false;
            }

            $insertResult=true;

            //init empty form
            $program = new Program();
            $form =$this->initNewForm($program);

        }



        return $this->render('program/index.html.twig', array(
            "form" => $form->createView(),
            "insertResult" => $insertResult,
            "RequestPath"=>$request->getRequestUri()
        ));
    }

    function allMoviesChoice(){
        $movies = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findAll();

        return $movies;
    }

    function initNewForm(Program $program){

        return $this->createFormBuilder($program)

            ->add('movie', EntityType::class, array(
                'class' => 'AppBundle:Movie',
                'choice_label' => 'title',
            ))
            ->add('datetime', DateTimeType::class)
            ->add('save', SubmitType::class, array('label' => 'Add Program'))
            ->getForm();


    }

//    public function showAction($productId)
//    {
//
//        $program = $this->getDoctrine()
//            ->getRepository('AppBundle:Program')
//            ->find($productId);
//
//        if (!$program) {
////            throw $this->createNotFoundException(
////                'No product found for id '.$productId
////            );
//
//            return new Response('Cant find the program with the given ID : '.$productId);
//        }
//
//        return new Response('Name of the Program is '.$program->getName());
//    }

}
