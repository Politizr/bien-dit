<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\QualificationConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\AdminBundle\Form\Type\AdminPUserLocalizationType;
use Politizr\AdminBundle\Form\Type\AdminPUserModerationType;

use Politizr\FrontBundle\Form\Type\PUserIdCheckType;
use Politizr\FrontBundle\Form\Type\PUMandateType;

use Politizr\Model\PUserQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUMandate;

use Politizr\Exception\InconsistentDataException;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     * @param PUser $user
     * @return Response
     */
    protected function successObjectIdcheck(PUser $user)
    {

        // id check form
        $form = $this->createForm(new PUserIdCheckType($user->getId()), $user);

        return $this->render('PolitizrAdminBundle:PUserActions:idcheck.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param PUser $user
     * @return Response
     */
    protected function successObjectMandate(PUser $user)
    {

        // Mandates form views
        $formMandateViews = $this->get('politizr.tools.global')->getFormMandateViews($user->getId());

        // New mandate
        $mandate = new PUMandate();
        $formMandate = $this->createForm(new PUMandateType(QualificationConstants::TYPE_ELECTIV, $user->getId()), $mandate);

        return $this->render('PolitizrAdminBundle:PUserActions:mandate.html.twig', array(
            'user' => $user,
            'formMandate' => $formMandate?$formMandate->createView():null,
            'formMandateViews' => $formMandateViews?$formMandateViews:null,
        ));
    }

    /**
     *
     * @param PUser $user
     * @return Response
     */
    protected function successObjectLocalization(PUser $user)
    {

        $form = $this->createForm(new AdminPUserLocalizationType($user));

        return $this->render('PolitizrAdminBundle:PUserActions:localization.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param PUser $user
     * @return Response
     */
    protected function successObjectModeration(PUser $user)
    {
        $form = $this->createForm(new AdminPUserModerationType($user));

        return $this->render('PolitizrAdminBundle:PUserActions:moderation.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param $request
     * @param $pk
     * @return Response
     */
    public function moderationUpdateAction(Request $request, $pk)
    {
        $user = PUserQuery::create()->findPk($pk);
        if (!$user) {
            throw new InconsistentDataException('PUser pk-'.$pk.' not found.');
        }

        $form = $this->createForm(new AdminPUserModerationType($user));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $moderationType = $data['p_m_moderation_type'];

                $this->get('politizr.functional.moderation')->archiveUser($user);

                $userModerated = $this->get('politizr.functional.moderation')->addUserModerated(
                    $user,
                    $moderationType->getId(),
                    ObjectTypeConstants::TYPE_USER,
                    $user->getId(),
                    $data['score_evolution']
                );

                $this->get('politizr.functional.moderation')->updateUserReputation($user, $data['score_evolution']);

                if ($data['ban']) {
                    $this->get('politizr.functional.moderation')->banUser($user);
                } else {
                    // Upd object
                    $user->setFirstname($data['firstname']);
                    $user->setName($data['name']);
                    $user->setBiography($data['biography']);
                    $user->setWebsite($data['website']);
                    $user->setFacebook($data['facebook']);
                    $user->setTwitter($data['twitter']);

                    $user->save();
                }

                if ($data['send_email']) {
                    $this->get('politizr.functional.moderation')->notifUser($user, $userModerated);
                }

                $abuseLevel = $user->getAbuseLevel();
                if (!$abuseLevel) {
                    $abuseLevel = 0;
                }
                $user->setAbuseLevel($abuseLevel + 1);
                $user->save();

                $this->get('session')->getFlashBag()->add('success', 'L\'utilisateur a été modéré avec succès.');

                return $this->redirect(
                    $this->generateUrl("Politizr_AdminBundle_PUser_list")
                );

            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            }
        }

        return $this->render('PolitizrAdminBundle:PUserActions:moderation.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param int $pk
     */
    public function archiveAction($pk)
    {
        $user = PUserQuery::create()->findPk($pk);
        if (!$user) {
            throw new InconsistentDataException('PUser pk-'.$pk.' not found.');
        }

        try {
            $this->get('politizr.functional.moderation')->archiveUser($user);

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PUser_edit", array('pk' => $pk)));
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
            $user = PUserQuery::create()->findPk($pk);
            if (!$user) {
                throw new InconsistentDataException('PUser pk-'.$pk.' not found.');
            }

            // Chemin des images
            $uploadWebPath = PathConstants::USER_UPLOAD_WEB_PATH;
            $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;

            // Appel du service d'upload ajax
            $fileName = $this->get('politizr.tools.global')->uploadXhrImage(
                $request,
                'file',
                $path,
                250,
                250
            );

            $user->setFileName($fileName);
            $user->save();

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
            $user = PUserQuery::create()->findPk($pk);
            if (!$user) {
                throw new InconsistentDataException('PUser pk-'.$pk.' not found.');
            }

            // File syst deletion
            $fileName = $user->getFileName();
            if ($fileName) {
                $uploadWebPath = PathConstants::USER_UPLOAD_WEB_PATH;
                $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;
                unlink($path . $fileName);
            }

            $user->setFileName(null);
            $user->save();

            $data = ['success' => true];
        } catch(\Exception $e) {
            $data = ['success' => false, 'error' => 'Une erreur s\'est produite! Msg = '.$e->getMessage()];
            return new JsonResponse($data, 500);
        }

        return new JsonResponse($data);
    }
}
