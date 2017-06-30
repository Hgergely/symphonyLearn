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

class ProgramController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('program/index.html.twig',
                array(
                    "RequestPath"=>$request->getRequestUri()
                )
        );
    }

 //if you have multiple entity managers, use the registry to fetch them
    public function editAction(ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $em2 = $doctrine->getManager('other_connection');

    }

    public function showAction($productId)
    {

        $program = $this->getDoctrine()
            ->getRepository('AppBundle:Program')
            ->find($productId);

        if (!$program) {
//            throw $this->createNotFoundException(
//                'No product found for id '.$productId
//            );

            return new Response('Cant find the program with the given ID : '.$productId);
        }

        return new Response('Name of the Program is '.$program->getName());
    }

}
