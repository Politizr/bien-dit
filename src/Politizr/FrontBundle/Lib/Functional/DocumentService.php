<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;

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
    private $securityAuthorizationChecker;
    
    private $documentManager;

    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.document
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $documentManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->documentManager = $documentManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

   /**
     * Debate feed timeline
     *
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    private function generateDraftsListingRawSql($offset, $count = 10)
    {
        $this->logger->info('*** getSql');

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $sql = $this->documentManager->createDraftsRawSql(
            $user->getId(),
            $offset,
            $count
        );

        return $sql;
    }

    /*
     * Execute SQL and hydrate PDDebate|PDReaction model
     *
     * @param string $sql
     * @return PropelCollection[PDDebate|PDReaction]
     */
    private function hydrateDocumentRows($sql)
    {
        $this->logger->info('*** hydrateDocumentRows');

        $documents = new \PropelCollection();

        if ($sql) {
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

            // dump($sql);

            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            // dump($result);

            foreach ($result as $row) {
                $type = $row['type'];

                if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                    $document = PDDebateQuery::create()->findPk($row['id']);
                } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                    $document = PDReactionQuery::create()->findPk($row['id']);
                } else {
                    throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
                }
                
                $documents->set($document->getId(), $document);
            }
        }

        return $documents;
    }


    /* ######################################################################################################## */
    /*                                              CRUD OPERATIONS                                             */
    /* ######################################################################################################## */
    
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

    /* ######################################################################################################## */
    /*                                              DOCUMENTS LISTING                                           */
    /* ######################################################################################################## */
    
    /**
     * Get the drafts paginated listing of documents (debate + reaction)
     *
     * @param integer $offset
     * @return PropelCollection[PDDebate|PDReaction]
     */
    public function generateDraftsListing($offset = 0)
    {
        $this->logger->info('*** generateDraftsPaginatedListing');
        
        $sql = $this->generateDraftsListingRawSql($offset);
        $documents = $this->hydrateDocumentRows($sql);

        return $documents;
    }
}
