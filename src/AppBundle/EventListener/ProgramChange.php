<?php
/**
 * Created by PhpStorm.
 * User: Gergely Hajcsak
 * Date: 05/07/2017
 * Time: 15:27
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Program;


class ProgramChange 
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if ($entity instanceof Program) {

            /*
             * code :
             * get all email addresses subscribed to this movie
             * send them emails about the new program
             */

            //$repository = $this->$em->getRepository('AppBundle:Subscription');
            $entityManager = $args->getEntityManager();
            $repository = $entityManager->getRepository('AppBundle:Subscription');
            $subscriptions = $repository->findByMovie($entity->getMovie()->getId());

            foreach ($subscriptions as $key => $object){

                // $object->getEmailAddress()
                // sending email to email addresses
            }
        }
    }

}