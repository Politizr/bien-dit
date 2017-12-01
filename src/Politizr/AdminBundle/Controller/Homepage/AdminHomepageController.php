<?php

namespace Politizr\AdminBundle\Controller\Homepage;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\NotificationConstants;

use Politizr\AdminBundle\Form\Type\PUsersFiltersType;
use Politizr\AdminBundle\Form\Type\Homepage\AdminNotificationType;

use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUserQuery;

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

        // init w. all users
        $users = PUserQuery::create()
            ->orderByName()
            ->distinct()
            ->find();
        
        $formFilter = $this->createForm(new PUsersFiltersType());
        $form = $this->createForm(new AdminNotificationType(), null, array('users' => $users));

        return $this->render('PolitizrAdminBundle:Homepage:index.html.twig', array(
            'formFilter' => $formFilter->createView(),
            'form' => $form->createView(),
            'lastPUNotif' => $lastPUNotif,
        ));
    }

}
