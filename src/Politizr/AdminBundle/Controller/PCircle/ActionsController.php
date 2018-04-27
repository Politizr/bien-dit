<?php

namespace Politizr\AdminBundle\Controller\PCircle;

use Admingenerated\PolitizrAdminBundle\BasePCircleController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Model\PCircle;
use Politizr\Model\PCircleQuery;

use Politizr\AdminBundle\Form\Type\PUsersFiltersType;
use Politizr\AdminBundle\Form\Type\PCirclePUsersSelectListType;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(PCircle $PCircle)
    {
        $PCircle->moveUp();
        $PCircle->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(PCircle $PCircle)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCircle_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(PCircle $PCircle)
    {
        $PCircle->moveDown();
        $PCircle->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(PCircle $PCircle)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCircle_list"));
    }


    /**
     * This function is for you to customize what action actually does
     */
    protected function executeObjectScope(PCircle $PCircle)
    {
        // By default action does nothing
        // Overwrite this function!
    }


    /**
     * This is called when action is successfull
     *
     * @param \Politizr\Model\PCircle $PCircle Your \Politizr\Model\PCircle object
     * @return Response Must return a response!
     */
    protected function successObjectScope(PCircle $circle)
    {
        // filter forms
        $formFilter1 = $this->createForm(new PUsersFiltersType());
        $formFilter2 = $this->createForm(new PUsersFiltersType());
        $formFilter3 = $this->createForm(new PUsersFiltersType());

        // users forms
        $circleService = $this->get('politizr.functional.circle');

        $users = $circleService->getUsersInCircleByCircleId();
        $usersInCircle = $circleService->getUsersInCircleByCircleId($circle->getId());
        $usersCanReactInCircle = $circleService->getUsersInCircleByCircleId($circle->getId(), true);

        $formUsers1 = $this->createForm(new PCirclePUsersSelectListType(), null, array('circle_id' => $circle->getId(), 'users' => $users));
        $formUsers2 = $this->createForm(new PCirclePUsersSelectListType(), null, array('circle_id' => $circle->getId(), 'users' => $usersInCircle));
        $formUsers3 = $this->createForm(new PCirclePUsersSelectListType(), null, array('circle_id' => $circle->getId(), 'users' => $usersCanReactInCircle));

        return $this->render(
            'PolitizrAdminBundle:PCircleActions:index.html.twig',
            $this->getAdditionalRenderParameters($circle, 'scope') + array(
                "PCircle" => $circle,
                "title" => $this->get('translator')->trans(
                    "action.custom.title",
                    array('%name%' => 'scope'),
                    'Admingenerator'
                ),
                "actionRoute" => "Politizr_AdminBundle_PCircle_object",
                "actionParams" => array("pk" => $circle->getId(), "action" => "scope"),
                'circleId' => $circle->getId(),
                'formFilter1' => $formFilter1->createView(),
                'formFilter2' => $formFilter2->createView(),
                'formFilter3' => $formFilter3->createView(),
                'formUsers1' => $formUsers1->createView(),
                'formUsers2' => $formUsers2->createView(),
                'formUsers3' => $formUsers3->createView(),
            )
        );
    }

    /**
     * Add users to circle post action
     */
    public function addUsersToCircleAction(Request $request)
    {
        $form = $this->createForm(new PCirclePUsersSelectListType());
        
        $form->handleRequest($request);

        $data = $form->getData();
        if ($form->isValid()) {
            $users = $data['p_users'];
            $circleId = $data['p_circle_id'];
            $circle = PCircleQuery::create()->findPk($circleId);

            foreach ($users as $user) {
                $this->get('politizr.functional.circle')->addUserInCircle($user, $circle);
            }
        }

        return $this->redirect(
            $this->generateUrl("Politizr_AdminBundle_PCircle_object", array('pk' => $data['p_circle_id'], 'action' => 'scope') )
        );
    }

    /**
     * Remove users from circle post action
     */
    public function removeUsersFromCircleAction(Request $request)
    {
        $form = $this->createForm(new PCirclePUsersSelectListType());
        
        $form->handleRequest($request);

        $data = $form->getData();
        if ($form->isValid()) {
            $users = $data['p_users'];
            $circleId = $data['p_circle_id'];
            $circle = PCircleQuery::create()->findPk($circleId);

            foreach ($users as $user) {
                $this->get('politizr.functional.circle')->removeUserFromCircle($user, $circle);
            }
        }

        return $this->redirect(
            $this->generateUrl("Politizr_AdminBundle_PCircle_object", array('pk' => $data['p_circle_id'], 'action' => 'scope') )
        );
    }

    /**
     * Add users authorization to publish reaction from circle post action
     */
    public function addUsersIsAuthorizedReactionToCircleAction(Request $request)
    {
        $form = $this->createForm(new PCirclePUsersSelectListType());
        
        $form->handleRequest($request);

        $data = $form->getData();
        if ($form->isValid()) {
            $users = $data['p_users'];
            $circleId = $data['p_circle_id'];
            $circle = PCircleQuery::create()->findPk($circleId);

            foreach ($users as $user) {
                $this->get('politizr.functional.circle')->updateUserIsAuthorizedReactionInCircle($user, $circle, true);
            }
        }

        return $this->redirect(
            $this->generateUrl("Politizr_AdminBundle_PCircle_object", array('pk' => $data['p_circle_id'], 'action' => 'scope') )
        );
    }

    /**
     * Remove users authorization to publish reaction from circle post action
     */
    public function removeUsersIsAuthorizedReactionToCircleAction(Request $request)
    {
        $form = $this->createForm(new PCirclePUsersSelectListType());
        
        $form->handleRequest($request);

        $data = $form->getData();
        if ($form->isValid()) {
            $users = $data['p_users'];
            $circleId = $data['p_circle_id'];
            $circle = PCircleQuery::create()->findPk($circleId);

            foreach ($users as $user) {
                $this->get('politizr.functional.circle')->updateUserIsAuthorizedReactionInCircle($user, $circle, false);
            }
        }

        return $this->redirect(
            $this->generateUrl("Politizr_AdminBundle_PCircle_object", array('pk' => $data['p_circle_id'], 'action' => 'scope') )
        );
    }

}
