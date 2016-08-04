<?php
namespace Politizr\AdminBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\NotificationConstants;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\AdminBundle\Form\Type\Homepage\AdminNotificationType;

use Politizr\Model\PUNotificationQuery;


/**
 * XHR service for admin management.
 *
 * @author Lionel Bouzonville
 */
class XhrDashboard
{
    private $kernel;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $globalTools;
    private $logger;

    /**
     *
     * @param @kernel
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $kernel,
        $eventDispatcher,
        $templating,
        $formFactory,
        $globalTools,
        $logger
    ) {
        $this->kernel = $kernel;

        $this->eventDispatcher = $eventDispatcher;
        
        $this->templating = $templating;
        $this->formFactory = $formFactory;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                    NOTIF                                                 */
    /* ######################################################################################################## */


    /**
     * Admin notif
     */
    public function adminNotif(Request $request)
    {
        $this->logger->info('*** adminNotif');

        $form = $this->formFactory->create(new AdminNotificationType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            foreach ($data['p_users'] as $user) {
                // Notification
                $event = new GenericEvent($user, array('admin_msg' => $data['description']));
                $dispatcher = $this->eventDispatcher->dispatch('n_admin_message', $event);
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        // Last notif sent
        $lastPUNotif = PUNotificationQuery::create()
            ->filterByPNotificationId(NotificationConstants::ID_ADM_MESSAGE)
            ->orderByCreatedAt('desc')
            ->findOne();


        $notifLast = $this->templating->render(
            'PolitizrAdminBundle:Dashboard:_notifLast.html.twig',
            array(
                'lastPUNotif' => $lastPUNotif,
            )
        );

        return array(
            'notifLast' => $notifLast,
        );
    }


}