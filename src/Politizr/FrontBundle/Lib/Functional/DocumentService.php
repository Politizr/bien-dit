<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\Manager\DocumentManager;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

/**
 * Functional service for document management.
 *
 * @author Lionel Bouzonville
 */
class DocumentService
{
    private $securityTokenStorage;
    private $documentManager;

    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @politizr.manager.document
     * @param @logger
     */
    public function __construct(
        TokenStorage $securityTokenStorage,
        DocumentManager $documentManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->documentManager = $documentManager;

        $this->logger = $logger;
    }


    /**
     * Create new debate
     *
     * @return PDDebate
     */
    public function createDebate()
    {
        $this->logger->info('*** createDebate');

        $user = $this->securityTokenStorage->getToken()->getUser();
        $debate = $this->documentManager->createDebate($user->getId());

        return $debate;
    }


    /**
     * Create new reaction
     *
     * @param int $debateId Associated debate id
     * @param int $parentId Associated parent reaction id
     * @return PDReaction
     */
    public function createReaction($debateId, $parentId)
    {
        $this->logger->info('*** createReaction');

        $user = $this->securityTokenStorage->getToken()->getUser();

        // get reaction's associated debate
        $debate = PDDebateQuery::create()->findPk($debateId);
        if (!$debate) {
            throw new InconsistentDataException(sprintf('Debate "%s" not found', $debateId));
        }

        // get parent's reaction
        $parent = null;
        if ($parentId) {
            $parent = PDReactionQuery::create()->findPk($parentId);
            if (!$parent) {
                throw new InconsistentDataException(sprintf('Parent reaction "%s" not found', $parentId));
            }
        }

        // Create debate for user
        $reaction = $this->documentManager->createReaction($user->getId(), $debate->getId(), $parentId);

        return $reaction;
    }
}
