<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\PrivateProjectsPlan;

/**
 * Redeem controller.
 *
 */
class RedeemController extends Controller{
public function redeemAction(Request $request, $id)
    {
        //Form initilize
        $form = $this->createRedeemForm($id);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();

            //Get redeem code from form POST
            $redeemCode = $form->getData('redeem_code');
            
            $redeem_code_entity = $em->getRepository('AppBundle:RedeemCodes')->findByCode($redeemCode['redeem_code']);

            if(!$redeem_code_entity){
            	//Return false back to the called 
                  throw $this->createNotFoundException('Unable to find redeem code.');
            }
            //Get the $compain for the code given from DB
            $campaign_entity   = $em->getRepository('AppBundle:Campaign')->findById($redeem_code_entity[0]->getCampainId());   

            //Check if the redeem code is valid
            $validRedeemCode = $this->checkRedeemCode($redeem_code_entity[0],$campaign_entity[0]);

            if($validRedeemCode){
            	//Calling createNewPlan controller to assign a new private project plan to user 	
            	$response = $this->forward('AppBundle:PrivateProjectsPlan:createNewPlan', array(
				        'user'  => $id,
				        'numOfPrivateProjects' => $campaign_entity[0]->getProjectNum(),
				        'expirationDate'			=>$campaign_entity[0]->getDuration()
					    ));
            	// [0] is used because findByCode above returns an array 
            	// Assign the redeem code to the user
                $redeem_code_entity[0]=$this->assignRedeemCodeToUser($redeem_code_entity[0],$id);
                $em->flush();  
            }
             return $this->redirect($this->generateUrl('user'));
        }
    }

    //Redeem check function 
    private function checkRedeemCode($redeem,$campaign){
        //Expiration of redeem code
        $redeem_expiration  = $redeem->getExpiration();
        $campaign_expiration = $campaign->getExpiration();
        $campaign_active    = $campaign->getActive();

        $dateNow = new \DateTime();

        if($redeem->getUsed()==false && $dateNow<=$redeem_expiration && $dateNow<=$campaign_expiration && $campaign_active){
            return true;
        }
        return false;
    }

    //Gets user id and assigns it to the redeem code updating the used field
    private function assignRedeemCodeToUser($redeemCode,$u_id){
        $redeemCode->setUserId($u_id);
        $redeemCode->setUsed(true);
        return $redeemCode;
    }
    //Provide private projects to user (depending on the value on campaigns table)
 
 
     /* Creates a form to redeem a code by User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createRedeemForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('redeem_code', array('id' => $id)))
            ->setMethod('POST')
            ->add('redeem_code','text',array('label'=>'Redeem Code'))
            ->add('submit', 'submit', array('label' => 'Redeem'))
            ->getForm()
        ;
    }
 

}