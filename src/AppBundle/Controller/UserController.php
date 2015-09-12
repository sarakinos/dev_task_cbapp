<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\PrivateProjectsPlan;
use AppBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('AppBundle:User:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $redeemForm = $this->createRedeemForm($id);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'redeem_form' => $redeemForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Checks if the user can redeem the given code.
     *
     */
    public function redeemAction(Request $request, $id)
    {
        //Get Request POST from form
        $form = $this->createRedeemForm($id);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();

            //Get redeem code from form POST
            $redeemCode = $form->getData('redeem_code');
            
            $redeem_code_entity = $em->getRepository('AppBundle:RedeemCodes')->findByCode($redeemCode['redeem_code']);

            if(!$redeem_code_entity){
                  throw $this->createNotFoundException('Unable to find redeem code.');
            }
            $campaign_entity   = $em->getRepository('AppBundle:Campaign')->findById($redeem_code_entity[0]->getCampainId());   

            //Check if the redeem code is valid
            $validRedeemCode = $this->checkRedeemCode($redeem_code_entity[0],$campaign_entity[0]);

            if($validRedeemCode){
                $redeem_code_entity[0]=$this->assignRedeemCodeToUser($redeem_code_entity[0],$id);
                $p_projects_entity = $em->getRepository('AppBundle:PrivateProjectsPlan')->findByUser($id);

                $campaign_p_num = $campaign_entity[0]->getProjectNum();

                if($p_projects_entity){  
                    $allowed_p_num = $p_projects_entity[0]->getNumberAllowed();                  
                    $p_projects_entity[0]->setNumberAllowed($allowed_p_num+$campaign_p_num);
                }else{
                    $p_projects_entity = new PrivateProjectsPlan();
                    $p_projects_entity->setUser($id);
                    $p_projects_entity->setNumberAllowed($campaign_p_num);
                    $p_projects_entity->setExpirationDate($campaign_entity[0]->getDuration());

                    $em->persist($p_projects_entity);
                }

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
 
    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

     /**
     * Creates a form to redeem a code by User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createRedeemForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_redeem_code', array('id' => $id)))
            ->setMethod('POST')
            ->add('redeem_code','text',array('label'=>'Redeem Code'))
            ->add('submit', 'submit', array('label' => 'Redeem'))
            ->getForm()
        ;
    }

    
}
