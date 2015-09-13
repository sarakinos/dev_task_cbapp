<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\PrivateProjectsPlan;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * PrivateProjectsPlan controller.
 *
 */
class PrivateProjectsPlanController extends Controller
{

    /**
     * Creates a new PrivateProjectsPlan with the provided data.
     *  
     * @param int $user The id of the user to get the private projects
     * @param int $numOfPrivateProjects The number of private projects to give
     * @param \DateTime $expirationDate The date when the plan will expire
     *
     * @return Response
     * The response is 0 if everything is ok, 1 for not found user and 2 for expired
     */
    public function createNewPlanAction($user, $numOfPrivateProjects, $expirationDate)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $userEntity = $entityManager->getRepository('AppBundle:User')->findOneBy(array('id' => $user));

        if ($userEntity === null) {
            return new Response('1', 404);
        }

        if ($expirationDate < new \DateTime('now')) {
            return new Response('2', 406);
        }

        $newPlan = new PrivateProjectsPlan();
        $newPlan->setUser($user);
        $newPlan->setNumberAllowed($numOfPrivateProjects);
        $newPlan->setExpirationDate($expirationDate);

        $entityManager->persist($newPlan);
        $entityManager->flush();

        return new Response('0', 200);
    }
}
