<?php
/**
 * Created by PhpStorm.
 * User: Gergely Hajcsak
 * Date: 05/07/2017
 * Time: 17:09
 */

namespace AppBundle\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Subscription;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class SubscribeController extends Controller
{

    public function saveAction(Request $request){

        $subscription = New Subscription();

        $form = $this->initNewForm($subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $em = $this->getDoctrine()->getManager();
                $em->persist($subscription);
                $em->flush();
            } catch(\Doctrine\DBAL\DBALException $e) {

                return new JsonResponse(array('success' => "false"));
            }

            return new JsonResponse(array('success' => "true"));

        }else{
            return new JsonResponse(array('success' => "false"));
        }
    }

    function initNewForm($subscription){

        return  $this->createFormBuilder($subscription)
            ->setAction($this->generateUrl('savesubscribe'))
            ->add('movie', EntityType ::class, array(
                'class' => 'AppBundle:Movie'
            )  )
            ->add('email_address', EmailType::class, array( 'attr' => array('placeholder' => 'Email Address')))
            ->add('save', SubmitType::class, array('label' => 'Subscribe'))
            ->getForm();
    }

}