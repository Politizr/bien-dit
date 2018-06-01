<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\AdminBundle\Form\Type\AdminPDocumentModerationType;

use Politizr\Model\PDReaction;
use Politizr\Model\PMReactionHistoric;

use Politizr\Model\PDReactionQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     * @param PDReaction $reaction
     * @return Response
     */
    protected function successObjectLocalization(PDReaction $reaction)
    {

        $form = $this->createForm(
            $this->get('politizr.form.type.document_localization'),
            $reaction,
            array(
                'data_class' => ObjectTypeConstants::TYPE_REACTION,
                'user' => $reaction->getUser(),
            )
        );

        return $this->render('PolitizrAdminBundle:PDReactionActions:localization.html.twig', array(
            'reaction' => $reaction,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param PDReaction $reaction
     * @return Response
     */
    protected function successObjectModeration(PDReaction $reaction)
    {
        $form = $this->createForm(new AdminPDocumentModerationType($reaction));

        return $this->render('PolitizrAdminBundle:PDReactionActions:moderation.html.twig', array(
            'reaction' => $reaction,
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
        $reaction = PDReactionQuery::create()->findPk($pk);
        if (!$reaction) {
            throw new InconsistentDataException('PDReaction pk-'.$pk.' not found.');
        }

        $form = $this->createForm(new AdminPDocumentModerationType($reaction));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $moderationType = $data['p_m_moderation_type'];

                $this->get('politizr.functional.moderation')->archiveReaction($reaction);

                // moderation updating
                if ($data['moderation_level'] == 1) {
                    $reaction->setModeratedPartial(true);
                    $reaction->setModeratedAt(new \DateTime());

                    $reaction->setTitle($data['title']);
                    $reaction->setDescription($data['description']);

                    $reaction->save();
                } elseif ($data['moderation_level'] == 2) {
                    $reaction->setModerated(true);
                    $reaction->setModeratedAt(new \DateTime());
                    $reaction->setOnline(false);

                    $reaction->save();
                }

                // user relative actions
                $user = $reaction->getPUser();

                if ($user) {
                    $userModerated = $this->get('politizr.functional.moderation')->addUserModerated(
                        $user,
                        $moderationType->getId(),
                        ObjectTypeConstants::TYPE_REACTION,
                        $reaction->getId(),
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

                $this->get('session')->getFlashBag()->add('success', 'Le document a été modéré avec succès.');

                return $this->redirect(
                    $this->generateUrl("Politizr_AdminBundle_PDReaction_list")
                );

            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            }
        }

        return $this->render('PolitizrAdminBundle:PDReactionActions:moderation.html.twig', array(
            'reaction' => $reaction,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param int $pk
     */
    public function archiveAction($pk)
    {
        $reaction = PDReactionQuery::create()->findPk($pk);
        if (!$reaction) {
            throw new InconsistentDataException('PDReactionQuery pk-'.$pk.' not found.');
        }

        try {
            $this->get('politizr.functional.moderation')->archiveReaction($reaction);

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDReaction_edit", array('pk' => $pk)));
    }

    /**
     * Redirection listing commentaires avec filtre ID réaction préfixé.
     *
     * @return \Politizr\AdminBundle\Controller\Customer\Response
     */
    public function commentsAction($pk)
    {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');

        $filterObject = array();
        $filterObject['p_d_reaction_id'] = $pk;
        $this->get('session')->set('Politizr\AdminBundle\PDRCommentList\Filters', $filterObject);

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDRComment_list"));
    }
}
