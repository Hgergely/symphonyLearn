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

use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\JsonResponse;

class MovieController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {

        $movie = new Movie();
        $form =$this->initNewForm($movie);
        $insertResult=null;

        /*
         * Handling post
         */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() holds the submitted values
            try {

                $em = $this->getDoctrine()->getManager();
                $em->persist($movie);
                $em->flush();
            } catch(\Doctrine\DBAL\DBALException $e) {

                $insertResult=false;

            }

            $insertResult=true;

            //init empty form
            $movie = new Movie();
            $form =$this->initNewForm($movie);

        }

        /*
         * Getting all movies
         */
        $allMovies =  $this->getAll();

        /*
         * Passing all to be rendered
         */

        return $this->render('movie/index.html.twig', array(
            "form" => $form->createView(),
            "allMovies" => $allMovies,
            "insertResult" => $insertResult,
            "RequestPath"=>$request->getRequestUri()
        ));
    }

    /**
     * @param $movieId
     * @return JsonResponse
     */
    public function deleteAction($movieId){

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('AppBundle:Movie')->find($movieId);

        if (!$movie) {
            return new JsonResponse(
                        array(  "success" => "false",
                                "message"=>"this entity is not found",
                                "refId"=>$movieId));
        }

        try{
            $em->remove($movie);
            $em->flush();
        }catch(Exception $e){
            return new JsonResponse(
                        array(
                                'success' => "false",
                                "refId"=>$movieId));
        }

        return new JsonResponse(array('success' => "true", "refId"=>$movieId));

    }



    /**
     * @param Movie $movie
     * @return mixed
     */
    function initNewForm(Movie $movie){

        return $this->createFormBuilder($movie)
            ->add('title', TextType::class)
            // If you use PHP 5.3 or 5.4 you must use
            // ->add('task', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('description', TextareaType::class)
            ->add('duration', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Save movie'))
            ->getForm();
    }

    /**
     * @return array
     */
    public function getAll(){

        return $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findAll();
    }


}