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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MovieController extends Controller
{

    public function indexAction()
    {
        return $this->render('movie/index.html.twig', array(
        ));
    }

//    public function createAction()
//    {
//
//        $em = $this->getDoctrine()->getManager();
//
//        $movie = new Movie();
//        $movie->setTitle('Men in Black')
//              ->setDescription("Two man against the monsters");
//
//        // tells Doctrine you want to (eventually) save the Product (no queries yet)
//        $em->persist($movie);
//
//        // actually executes the queries (i.e. the INSERT query)
//        $em->flush();
//
//        return new Response('Saved new product with id '.$movie->getId());
//    }

    public function moviesAction(Request $request)
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

        return $this->render('movie/new.html.twig', array(
            'form' => $form->createView(),
            'allMovies' => $allMovies,
            'insertResult' => $insertResult
        ));
    }

    function initNewForm(Movie $movie){

        return $this->createFormBuilder($movie)
            ->add('title', TextType::class)
            // If you use PHP 5.3 or 5.4 you must use
            // ->add('task', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Save movie'))
            ->getForm();
    }

    function getAll(){

        return $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findAll();
    }


}