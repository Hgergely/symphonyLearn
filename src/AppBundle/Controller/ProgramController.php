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
//use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Program;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return new Response(
            '<html><body>Index</body></html>'
        );
    }

    public function createAction()
    {

        $em = $this->getDoctrine()->getManager();

        $program = new Program();
        $program->setName('Men in Black');


        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($program);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id '.$program->getId());
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
