<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

/**
 * Functional service for document management.
 *
 * @author Lionel Bouzonville
 */
class DocumentService
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }


    /**
     * Create new debate
     *
     * @return PDDebate
     */
    public function createDebate()
    {
        // Get current user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Get document manager
        $documentManager = $this->sc->get('politizr.manager.document');

        // Create debate for user
        $debate = $documentManager->createDebate($user->getId());

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
        // Get current user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Get document manager
        $documentManager = $this->sc->get('politizr.manager.document');

        // Récupération du débat sur lequel la réaction a lieu
        $debate = PDDebateQuery::create()->findPk($debateId);
        if (!$debate) {
            throw new InconsistentDataException('Debate n°'.$debateId.' not found.');
        }

        // Récupération de la réaction parente sur laquelle la réaction a lieu
        $parent = null;
        if ($parentId) {
            $parent = PDReactionQuery::create()->findPk($parentId);
            if (!$parent) {
                throw new InconsistentDataException('Parent reaction n°'.$parentId.' not found.');
            }
        }

        // Create debate for user
        $reaction = $documentManager->createReaction($user->getId(), $debate->getId(), $parentId);

        return $reaction;
    }
}
