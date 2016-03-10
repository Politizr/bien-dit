<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUserQuery;

/**
 * Public controller
 *
 * @author  Lionel Bouzonville
 */
class PublicController extends Controller
{
    /**
     * Homepage
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        // redirect if connected
        if ($profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix()) {
            return $this->redirect($this->generateUrl(sprintf('Homepage%s', $profileSuffix)));
        }

        return $this->render('PolitizrFrontBundle:Public:homepage.html.twig', array(
            'homepage' => true,
        ));
    }

    /**
     * Help
     */
    public function helpAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** helpAction');

        return $this->render('PolitizrFrontBundle:Public:help.html.twig', array(
        ));
    }
}
