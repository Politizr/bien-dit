<?php

namespace Politizr\AdminBundle\Controller\PCTopic;

use Admingenerated\PolitizrAdminBundle\BasePCTopicController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;

use Politizr\Model\PCTopic;
use Politizr\Model\PCTopicQuery;

use Politizr\AdminBundle\Form\Type\AdminPCTopicLocalizationType;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(PCTopic $PCTopic)
    {
        $PCTopic->moveUp();
        $PCTopic->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(PCTopic $PCTopic)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCTopic_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(PCTopic $PCTopic)
    {
        $PCTopic->moveDown();
        $PCTopic->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(PCTopic $PCTopic)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCTopic_list"));
    }

    /**
     *
     * @param PCTopic $topic
     * @return Response
     */
    protected function successObjectLocalization(PCTopic $topic)
    {

        $form = $this->createForm(new AdminPCTopicLocalizationType($topic));

        return $this->render('PolitizrAdminBundle:PCTopicActions:localization.html.twig', array(
            'topic' => $topic,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param $request
     * @param $pk
     * @return Response
     */
    public function localizationUpdateAction(Request $request, $pk)
    {
        $topic = PCTopicQuery::create()->findPk($pk);
        if (!$topic) {
            throw new InconsistentDataException('PCTopic pk-'.$pk.' not found.');
        }

        $form = $this->createForm(new AdminPCTopicLocalizationType($topic));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();

                $topic->setForceGeolocType($data['force_geoloc_type']);
                $topic->setForceGeolocId($data['force_geoloc_id']);

                $topic->save();

                $this->get('session')->getFlashBag()->add('success', 'La localisation a été mise à jour avec succès.');

                return $this->redirect(
                    $this->generateUrl("Politizr_AdminBundle_PCTopic_list")
                );

            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            }
        }

        return $this->render('PolitizrAdminBundle:PCTopicActions:localization.html.twig', array(
            'topic' => $topic,
            'form' => $form->createView(),
        ));
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
            $topic = PCTopicQuery::create()->findPk($pk);
            if (!$topic) {
                throw new InconsistentDataException('PCTopic pk-'.$pk.' not found.');
            }

            // Chemin des images
            $uploadWebPath = PathConstants::CIRCLE_UPLOAD_WEB_PATH;
            $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;

            // Appel du service d'upload ajax
            $fileName = $this->get('politizr.tools.global')->uploadXhrImage(
                $request,
                'file',
                $path,
                1200,
                1200
            );

            // create thumbnail
            $imagePath = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;
            $thbFileName = DocumentConstants::DOC_THUMBNAIL_PREFIX . $fileName;
            $this->get('politizr.tools.global')->copyFile(
                $imagePath . $fileName,
                $imagePath . $thbFileName
            );
            $this->get('politizr.tools.global')->resizeImage(
                $imagePath . $thbFileName,
                DocumentConstants::DOC_THUMBNAIL_MAX_WIDTH,
                DocumentConstants::DOC_THUMBNAIL_MAX_HEIGHT
            );

            $topic->setFileName($fileName);
            $topic->save();

            $data = ['success' => true, 'fileName' => $fileName, 'thbFileName' => $thbFileName, 'filePath' => $uploadWebPath ];
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
            $topic = PCTopicQuery::create()->findPk($pk);
            if (!$topic) {
                throw new InconsistentDataException('PCTopic pk-'.$pk.' not found.');
            }

            // File syst deletion
            $fileName = $topic->getFileName();
            if ($fileName) {
                $uploadWebPath = PathConstants::CIRCLE_UPLOAD_WEB_PATH;
                $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;
                unlink($path . $fileName);
                unlink($path . DocumentConstants::DOC_THUMBNAIL_PREFIX . $fileName);

                // delete cache folder
                $this->get('politizr.tools.global')->clearFilesFromFolder($this->get('kernel')->getRootDir() . '/../web/uploads/cache', $fileName);
            }

            $topic->setFileName(null);
            $topic->save();

            $data = ['success' => true];
        } catch(\Exception $e) {
            $data = ['success' => false, 'error' => 'Une erreur s\'est produite! Msg = '.$e->getMessage()];
            return new JsonResponse($data, 500);
        }

        return new JsonResponse($data);
    }
}
