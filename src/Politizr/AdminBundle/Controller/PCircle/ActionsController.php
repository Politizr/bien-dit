<?php

namespace Politizr\AdminBundle\Controller\PCircle;

use Admingenerated\PolitizrAdminBundle\BasePCircleController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

use Politizr\Constant\PathConstants;


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

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCircle_list"));
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

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCircle_list"));
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

        return $this->render('PolitizrAdminBundle:PCircleActions:scope.html.twig', array(
            'circle' => $circle,
            'formFilter1' => $formFilter1->createView(),
            'formFilter2' => $formFilter2->createView(),
            'formFilter3' => $formFilter3->createView(),
            'formUsers1' => $formUsers1->createView(),
            'formUsers2' => $formUsers2->createView(),
            'formUsers3' => $formUsers3->createView(),
        ));
    }

    /**
     * Add users to circle post action
     */
    public function addUsersToCircleAction(Request $request)
    {
        try {
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

            $this->get('session')->getFlashBag()->add('success', 'Les autorisations ont été mises à jour avec succès.');        
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite: ' . $e->getMessage());
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
        try {
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

            $this->get('session')->getFlashBag()->add('success', 'Les autorisations ont été mises à jour avec succès.');        
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite: ' . $e->getMessage());
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
        try {
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

            $this->get('session')->getFlashBag()->add('success', 'Les autorisations ont été mises à jour avec succès.');        
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite: ' . $e->getMessage());
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
        try {
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

            $this->get('session')->getFlashBag()->add('success', 'Les autorisations ont été mises à jour avec succès.');        
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est produite: ' . $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl("Politizr_AdminBundle_PCircle_object", array('pk' => $data['p_circle_id'], 'action' => 'scope') )
        );
    }

    /**
     * XHR Dropzone file upload
     *
     * @param $request
     * @param $pk
     * @return JsonResponse
     */
    public function filenameUploadAction(Request $request, $pk)
    {
        try {
            $circle = PCircleQuery::create()->findPk($pk);
            if (!$circle) {
                throw new InconsistentDataException('PCircle pk-'.$pk.' not found.');
            }

            // Chemin des images
            $uploadWebPath = PathConstants::CIRCLE_UPLOAD_WEB_PATH;
            $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;

            // Appel du service d'upload ajax
            $fileName = $this->get('politizr.tools.global')->uploadXhrImage(
                $request,
                'file',
                $path,
                250,
                250
            );

            $circle->setLogoFileName($fileName);
            $circle->save();

            $data = ['success' => true, 'fileName' => $fileName, 'thbFileName' => $fileName, 'filePath' => $uploadWebPath ];
        } catch(\Exception $e) {
            $data = ['success' => false, 'error' => $e->getMessage()];
            return new JsonResponse($data, 500);
        }

        return new JsonResponse($data);
    }

    /**
     * XHR Dropzone file upload delete
     *
     * @param $request
     * @param $pk
     * @return JsonResponse
     */
    public function filenameDeleteAction(Request $request, $pk)
    {
        try {
            $circle = PCircleQuery::create()->findPk($pk);
            if (!$circle) {
                throw new InconsistentDataException('PCircle pk-'.$pk.' not found.');
            }

            // File syst deletion
            $fileName = $circle->getLogoFileName();
            if ($fileName) {
                $uploadWebPath = PathConstants::CIRCLE_UPLOAD_WEB_PATH;
                $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;
                unlink($path . $fileName);
            }

            $circle->setLogoFileName(null);
            $circle->save();

            $data = ['success' => true];
        } catch(\Exception $e) {
            $data = ['success' => false, 'error' => 'Une erreur s\'est produite! Msg = '.$e->getMessage()];
            return new JsonResponse($data, 500);
        }

        return new JsonResponse($data);
    }
}
