<?php

// src/AppBundle/Controller/LuckyController.php 
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;


class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number")
     */


   public function indexAction(Request $request)
    {
       return $this->json(array('username' => 'jane.doe'));
    }

    public function listAction()
    {
       return new Response(
            '<html><body>Default</body></html>'
        );
    }


    public function SomeAction()
    {
       return new Response(
            '<html><body>Some</body></html>'
        );
    }

    public function multipleAction($var1,$var2,$var3)
    {
        $number = mt_rand(0, 100);

        // return new Response(
        //     '<html><body>Lucky number: '.$number.'</body></html>'
        // );

        throw $this->createNotFoundException('The product does not exist');

        return $this->render('lucky/number2.html.twig', array(
            'var1' => $var1,
            'var2' => $var2,
            'var3' => $var3,

        ));
    }


    public function numberAction($var=1)
    {
        $number = mt_rand(0, 100);

        // return new Response(
        //     '<html><body>Lucky number: '.$number.'</body></html>'
        // );

        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
            'var' => $var,
        ));
    }


     public function anyAction()
    {
       return new Response(
            '<html><body>Any</body></html>'
        );
    }
}