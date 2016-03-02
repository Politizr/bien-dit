<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PMAbuseReporting;
use Politizr\Model\PMAskForUpdate;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

use Politizr\FrontBundle\Form\Type\PMAbuseReportingType;
use Politizr\FrontBundle\Form\Type\PMAskForUpdateType;

/**
 * XHR service for monitoring management.
 *
 * @author Lionel Bouzonville
 */
class XhrMonitoring
{
    private $securityTokenStorage;
    private $templating;
    private $formFactory;
    private $eventDispatcher;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @form.factory
     * @param @event_dispatcher
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $formFactory,
        $eventDispatcher,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;

        $this->formFactory = $formFactory;

        $this->eventDispatcher = $eventDispatcher;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                                 ABUSE                                                    */
    /* ######################################################################################################## */

    /**
     * Abuse form
     */
    public function abuse(Request $request)
    {
        $this->logger->info('*** abuse');
        
        // Request arguments
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        $this->logger->info('$type = ' . print_r($type, true));

        // form
        $abuseReporting = new PMAbuseReporting();
        $abuseReporting->setPObjectName($type);
        $abuseReporting->setPObjectUuid($uuid);

        $formAbuse = $this->formFactory->create(new PMAbuseReportingType(), $abuseReporting);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Monitoring:_formAbuse.html.twig',
            array(
                'formAbuse' => $formAbuse->createView(),
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Abuse form submit
     */
    public function abuseCreate(Request $request)
    {
        $this->logger->info('*** abuseCreate');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // Function process
        $formAbuse = $this->formFactory->create(new PMAbuseReportingType(), new PMAbuseReporting());

        $formAbuse->bind($request);
        if ($formAbuse->isValid()) {
            $abuse = $formAbuse->getData();
            $abuse->setPUserId($user->getId());
            $abuse->save();

            // email
            $event = new GenericEvent($abuse);
            $dispatcher =  $this->eventDispatcher->dispatch('monitoring_email', $event);
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($formAbuse);
            throw new BoxErrorException($errors);
        }

        return true;
    }

    /* ######################################################################################################## */
    /*                                           ASK FOR UPDATE                                                 */
    /* ######################################################################################################## */

    /**
     * Ask for update form
     */
    public function askForUpdate(Request $request)
    {
        $this->logger->info('*** askForUpdate');
        
        // Request arguments
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        $this->logger->info('$type = ' . print_r($type, true));

        // form
        $askForUpdate = new PMAskForUpdate();
        $askForUpdate->setPObjectName($type);
        $askForUpdate->setPObjectUuid($uuid);

        $formAskForUpdate = $this->formFactory->create(new PMAskForUpdateType(), $askForUpdate);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Monitoring:_formAskForUpdate.html.twig',
            array(
                'formAskForUpdate' => $formAskForUpdate->createView(),
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Abuse form submit
     */
    public function askForUpdateCreate(Request $request)
    {
        $this->logger->info('*** askForUpdateCreate');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $formAskForUpdate = $this->formFactory->create(new PMAskForUpdateType(), new PMAskForUpdate());

        $formAskForUpdate->bind($request);
        if ($formAskForUpdate->isValid()) {
            $askForUpdate = $formAskForUpdate->getData();
            $askForUpdate->setPUserId($user->getId());
            $askForUpdate->save();

            // email
            $event = new GenericEvent($askForUpdate);
            $dispatcher =  $this->eventDispatcher->dispatch('monitoring_email', $event);
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($formAskForUpdate);
            throw new BoxErrorException($errors);
        }

        return true;
    }
}
