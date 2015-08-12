<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PMAbuseReporting;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

use Politizr\FrontBundle\Form\Type\PMAbuseReportingType;

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
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @form.factory
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $formFactory,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;

        $this->formFactory = $formFactory;

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
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $type = $request->get('type');
        $this->logger->info('$type = ' . print_r($type, true));

        $user = $this->securityTokenStorage->getToken()->getUser();

        // context
        switch ($type) {
            case ObjectTypeConstants::TYPE_USER:
                $contextUser = PUserQuery::create()->findPk($id);
                if (null === $contextUser) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_abuseContextUser.html.twig',
                    array(
                        'user' => $contextUser,
                    )
                );

                break;
            case ObjectTypeConstants::TYPE_DEBATE:
                $contextDebate = PDDebateQuery::create()->findPk($id);
                if (null === $contextDebate) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_abuseContextDebate.html.twig',
                    array(
                        'debate' => $contextDebate,
                    )
                );

                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $contextReaction = PDReactionQuery::create()->findPk($id);
                if (null === $contextReaction) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_abuseContextReaction.html.twig',
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

                $contextComment = $query->findPk($id);
                if (null === $contextComment) {
                    throw InconsistentDataException(sprintf('Object type %s ID#%s is null.', $type, $id));
                }

                $context = $this->templating->render(
                    'PolitizrFrontBundle:Monitoring:_abuseContextComment.html.twig',
                    array(
                        'comment' => $contextComment,
                    )
                );

                break;
            default:
                // @todo case default > throw IDException in all switch/case code
                throw InconsistentDataException(sprintf('Object type %s is not defined.', $type));
        }


        // form
        $abuseReporting = new PMAbuseReporting();
        $abuseReporting->setPUserId($user->getId());
        $abuseReporting->setPObjectName($type);
        $abuseReporting->setPObjectId($id);

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

        // Function process
        $formAbuse = $this->formFactory->create(new PMAbuseReportingType(), new PMAbuseReporting());

        $formAbuse->bind($request);
        if ($formAbuse->isValid()) {
            $abuse = $formAbuse->getData();

            $abuse->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($formAbuse);
            throw new FormValidationException($errors);
        }

        return true;
    }
}
