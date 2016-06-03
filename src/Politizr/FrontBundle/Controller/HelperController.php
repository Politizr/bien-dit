<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Helper controller
 *
 * http://nyrodev.info/fr/posts/286/Connexions-OAuth-Multiple-avec-Symfony-2-3
 * http://sirprize.me/scribble/under-the-hood-of-symfony-security/
 * http://www.reecefowell.com/2011/10/26/redirecting-on-loginlogout-in-symfony2-using-loginhandlers/
 *
 * @author Lionel Bouzonville
 */
class HelperController extends Controller
{

    /* ######################################################################################################## */
    /*                                            GENERIC HELPER                                                */
    /* ######################################################################################################## */

    /**
     * Global helper
     */
    public function globalAction()
    {

        return $this->render(
            'PolitizrFrontBundle:Navigation\\Helper:globalHelper.html.twig',
            array(
            )
        );
    }
}
