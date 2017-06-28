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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MovieController extends Controller
{

    public function indexAction()
    {
        return $this->render('movie/index.html.twig', array(
        ));
    }

    public function createAction()
    {

        $em = $this->getDoctrine()->getManager();

        $movie = new Movie();
        $movie->setTitle('Men in Black')
              ->setDescription("Two man against the monsters");

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($movie);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id '.$movie->getId());
    }

    public function newAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $movie = new Movie();
        $movie->setTitle('Dirty Dozen');
        $movie->setDescription('12 US prisoners are trained and deployed to distroy a naci object on German land');

        $form = $this->createFormBuilder($movie)
            ->add('title', TextType::class)
            // If you use PHP 5.3 or 5.4 you must use
            // ->add('task', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save movie'))
            ->getForm();

        return $this->render('movie/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


}