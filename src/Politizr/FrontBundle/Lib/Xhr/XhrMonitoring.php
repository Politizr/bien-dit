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
    /*                                        PRIVATE FUNCTIONS                                                 */
    /* ######################################################################################################## */

    /**
     * Compute the rendering template of the modal context
     *
     * @param int $uuid   Object UUID
     * @param string $type
     * @return string
     */
    private function getModalContext($uuid, $type)
    {
        // context
        switch ($type) {
            case ObjectTypeConstants::TYPE_USER:
                $contextUser = PUserQuery::create()->filterByUuid($uuid)->findOne();
                if (null === $contextUser) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_contextUser.html.twig',
                    array(
                        'user' => $contextUser,
                    )
                );

                break;
            case ObjectTypeConstants::TYPE_DEBATE:
                $contextDebate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
                if (null === $contextDebate) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_contextDebate.html.twig',
                    array(
                        'debate' => $contextDebate,
                    )
                );

                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $contextReaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
                if (null === $contextReaction) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_contextReaction.html.twig',
                    array(
                        'reaction' => $contextReaction,
                    )
                );

                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                if ($type == ObjectTypeConstants::TYPE_DEBATE_COMMENT) {
                    $query = PDDCommentQuery::create();
                } else {
                    $query = PDRCommentQuery::create();
                }

                $contextComment = $query->filterByUuid($uuid)->findOne();
                if (null === $contextComment) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_contextComment.html.twig',
                    array(
                        'comment' => $contextComment,
                    )
                );

                break;
            default:
                throw InconsistentDataException(sprintf('Object type %s is not defined.', $type));
        }

        return $context;
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

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get context rendering
        $context = $this->getModalContext($uuid, $type);

        // form
        $abuseReporting = new PMAbuseReporting();
        $abuseReporting->setPObjectName($type);
        $abuseReporting->setPObjectUuid($uuid);

        $formAbuse = $this->formFactory->create(new PMAbuseReportingType(), $abuseReporting);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Monitoring:_abuse.html.twig',
            array(
                'context' => $context,
                'formAbuse' => $formAbuse->createView(),
            )
        );

        return array(
            'context' => $context,
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

        $user = $this->securityTokenStorage->getToken()->getUser();

        // get context rendering
        $context = $this->getModalContext($uuid, $type);

        // form
        $askForUpdate = new PMAskForUpdate();
        $askForUpdate->setPObjectName($type);
        $askForUpdate->setPObjectUuid($uuid);

        $formAskForUpdate = $this->formFactory->create(new PMAskForUpdateType(), $askForUpdate);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Monitoring:_askForUpdate.html.twig',
            array(
                'context' => $context,
                'formAskForUpdate' => $formAskForUpdate->createView(),
            )
        );

        return array(
            'context' => $context,
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
