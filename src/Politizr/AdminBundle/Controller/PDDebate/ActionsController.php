<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\AdminBundle\Form\Type\AdminPDocumentModerationType;

use Politizr\Model\PDDebate;
use Politizr\Model\PMDebateHistoric;

use Politizr\Model\PDDebateQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     * @param PDDebate $debate
     * @return Response
     */
    protected function successObjectLocalization(PDDebate $debate)
    {

        $form = $this->createForm(
            $this->get('politizr.form.type.document_localization'),
            $debate,
            array(
                'data_class' => ObjectTypeConstants::TYPE_DEBATE,
                'user' => $debate->getUser(),
            )
        );

        return $this->render('PolitizrAdminBundle:PDDebateActions:localization.html.twig', array(
            'debate' => $debate,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param PDDebate $debate
     * @return Response
     */
    protected function successObjectModeration(PDDebate $debate)
    {
        $form = $this->createForm(new AdminPDocumentModerationType($debate));

        return $this->render('PolitizrAdminBundle:PDDebateActions:moderation.html.twig', array(
            'debate' => $debate,
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
        $debate = PDDebateQuery::create()->findPk($pk);
        if (!$debate) {
            throw new InconsistentDataException('PDDebate pk-'.$pk.' not found.');
        }

        $form = $this->createForm(new AdminPDocumentModerationType($debate));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $moderationType = $data['p_m_moderation_type'];

                $this->get('politizr.functional.moderation')->archiveDebate($debate);

                // moderation updating
                if ($data['moderation_level'] == 1) {
                    $debate->setModeratedPartial(true);
                    $debate->setModeratedAt(new \DateTime());

                    $debate->setTitle($data['title']);
                    $debate->setDescription($data['description']);

                    $debate->save();
                } elseif ($data['moderation_level'] == 2) {
                    $debate->setModerated(true);
                    $debate->setModeratedAt(new \DateTime());
                    $debate->setOnline(false);

                    $debate->save();
                }

                // user relative actions
                $user = $debate->getPUser();

                if ($user) {
                    $userModerated = $this->get('politizr.functional.moderation')->addUserModerated(
                        $user,
                        $moderationType->getId(),
                        ObjectTypeConstants::TYPE_DEBATE,
                        $debate->getId(),
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
                    $this->generateUrl("Politizr_AdminBundle_PDDebate_list")
                );

            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            }
        }

        return $this->render('PolitizrAdminBundle:PDDebateActions:moderation.html.twig', array(
            'debate' => $debate,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param int $pk
     */
    public function archiveAction($pk)
    {
        $debate = PDDebateQuery::create()->findPk($pk);
        if (!$debate) {
            throw new InconsistentDataException('PDDebateQuery pk-'.$pk.' not found.');
        }

        try {
            $this->get('politizr.functional.moderation')->archiveDebate($debate);

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDebate_edit", array('pk' => $pk)));
    }

    /**
     * Redirection listing commentaires avec filtre ID débat préfixé.
     *
     * @return \Politizr\AdminBundle\Controller\Customer\Response
     */
    public function commentsAction($pk)
    {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');

        $filterObject = array();
        $filterObject['p_d_debate_id'] = $pk;
        $this->get('session')->set('Politizr\AdminBundle\PDDCommentList\Filters', $filterObject);

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDComment_list"));
    }
}
