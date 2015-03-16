<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * Redirection listing commentaires avec filtre ID débat préfixé.
     * 
     * @return \Politizr\AdminBundle\Controller\Customer\Response
     */
    public function commentsAction($pk) {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');

        $filterObject = array();
        $filterObject['p_d_debate_id'] = $pk;
        $this->get('session')->set('Politizr\AdminBundle\PDDCommentList\Filters', $filterObject);


        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDComment_list"));
    }


}
