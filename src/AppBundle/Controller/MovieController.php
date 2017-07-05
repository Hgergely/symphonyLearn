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
use AppBundle\Entity\Subscription;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\JsonResponse;


class MovieController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {

//        var_dump($locale = $request->getLocale());
//        echo $translated = $this->get('translator')->trans('Set new program');
//

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
            ->add('title', TextType::class,  array('label' => 'Title', 'attr' => array('class' => 'formElements ')))
            ->add('description', TextareaType::class, array('label' => 'Description', 'attr' => array('class' => 'formElements')))
            ->add('duration', IntegerType::class, array('label' => 'Duration', 'attr' => array('class' => 'formElements')))
            ->add('save', SubmitType::class, array('label' => 'Save movie'))
            ->getForm();
    }


    public function detailsAction($movieId,Request $request){

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('AppBundle:Movie')->find($movieId);

        $subscription = new Subscription();

        $subscription->setMovie($movie);

        $form = $this->createFormBuilder($subscription)
            ->setAction($this->generateUrl('savesubscribe'))
            ->add('movie', HiddenType ::class  )
            ->add('email_address', EmailType::class, array( 'attr' => array('placeholder' => 'Email Address')))
            ->add('save', SubmitType::class, array('label' => 'Subscribe'))
            ->getForm();

        return $this->render('movie/details.html.twig', array(
           "movie" => $movie,
           "form" => $form->createView(),
        ));

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