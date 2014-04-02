<?php

namespace Politizr\AdminBundle\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description
 *
 * @author lionel
 */
class AdminHomepageController extends Controller {

  /**
   * Homepage
   */
  public function indexAction() {
    return $this->render('PolitizrAdminBundle:Homepage:index.html.twig');
  }

}
