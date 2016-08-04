<?php

namespace Politizr\AdminBundle\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\NotificationConstants;

use Politizr\AdminBundle\Form\Type\Homepage\AdminNotificationType;

use Politizr\Model\PUNotificationQuery;

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
        // Last notif sent
        $lastPUNotif = PUNotificationQuery::create()
            ->filterByPNotificationId(NotificationConstants::ID_ADM_MESSAGE)
            ->orderByCreatedAt('desc')
            ->findOne();
        
        $form = $this->createForm(new AdminNotificationType());

        return $this->render('PolitizrAdminBundle:Homepage:index.html.twig', array(
            'form' => $form->createView(),
            'lastPUNotif' => $lastPUNotif,
        ));
    }

}
