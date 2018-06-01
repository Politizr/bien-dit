<?php

namespace Politizr\AdminBundle\Controller\PTag;

use Admingenerated\PolitizrAdminBundle\BasePTagController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\AdminBundle\Form\Type\AdminPTagModerationType;

use Politizr\Model\PTag;

use Politizr\Model\PTagQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     * @param PTag $tag
     * @return Response
     */
    protected function successObjectModeration(PTag $tag)
    {
        $form = $this->createForm(new AdminPTagModerationType($tag));

        return $this->render('PolitizrAdminBundle:PTagActions:moderation.html.twig', array(
            'tag' => $tag,
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
        $tag = PTagQuery::create()->findPk($pk);
        if (!$tag) {
            throw new InconsistentDataException('PTag pk-'.$pk.' not found.');
        }

        $form = $this->createForm(new AdminPTagModerationType($tag));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $moderationType = $data['p_m_moderation_type'];

                // moderation updating
                if ($data['moderation_level'] == 1) {
                    $tag->setModerated(true);
                    $tag->setModeratedAt(new \DateTime());

                    $tag->setTitle($data['title']);

                    $tag->save();
                } elseif ($data['moderation_level'] == 2) {
                    $tag->setModerated(true);
                    $tag->setModeratedAt(new \DateTime());
                    
                    $tag->setOnline(false);

                    $tag->save();
                }

                // user relative actions
                $user = $tag->getPUser();

                if ($user) {
                    $userModerated = $this->get('politizr.functional.moderation')->addUserModerated(
                        $user,
                        $moderationType->getId(),
                        ObjectTypeConstants::TYPE_TAG,
                        $tag->getId(),
                        $data['score_evolution']
                    );

                    $this->get('politizr.functional.moderation')->updateUserReputation($user, $data['score_evolution']);

                    if ($data['ban']) {
                        $this->get('politizr.functional.moderation')->banUser($user);
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
                }

                $this->get('session')->getFlashBag()->add('success', 'La thématique a été modérée avec succès.');

                return $this->redirect(
                    $this->generateUrl("Politizr_AdminBundle_PTag_list")
                );

            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            }
        }

        return $this->render('PolitizrAdminBundle:PTagActions:moderation.html.twig', array(
            'tag' => $tag,
            'form' => $form->createView(),
        ));
    }

}
