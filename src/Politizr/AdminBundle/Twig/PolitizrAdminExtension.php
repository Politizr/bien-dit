<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PCircleQuery;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PCOwnerQuery;
use Politizr\Model\PDMediaQuery;

/**
 * Generic admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminExtension extends \Twig_Extension
{
    private $documentService;
    
    private $formFactory;
    private $router;
    private $logger;

    /**
     *
     * @param politizr.functional.document
     * @param form.factory
     * @param router
     * @param logger
     */
    public function __construct(
        $documentService,
        $formFactory,
        $router,
        $logger
    ) {
        $this->documentService = $documentService;
        
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'adminCreatePath'  => new \Twig_SimpleFunction(
                'adminCreatePath',
                array($this, 'adminCreatePath'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */

 
    /**
     * Create a link to the object.
     *
     * @param string $objectClass
     * @param int $objectId
     * @param boolean $displayWithType
     * @param string $mode show|edit
     * @param string $idType id|uuid
     * @return string
     */
    public function adminCreatePath(\Twig_Environment $env, $objectClass, $objectId, $displayWithType = false, $mode = 'show', $idType = 'id')
    {
        $this->logger->info('*** adminCreatePath');
        $this->logger->info('$objectClass = '.print_r($objectClass, true));
        $this->logger->info('$objectId = '.print_r($objectId, true));
        $this->logger->info('$displayWithType = '.print_r($displayWithType, true));
        $this->logger->info('$mode = '.print_r($mode, true));
        $this->logger->info('$idType = '.print_r($idType, true));

        switch ($objectClass) {
            case ObjectTypeConstants::TYPE_DEBATE:
                ($displayWithType)?$label = 'Débat ':$label = '';
                if ($idType == 'id') {
                    $subject = PDDebateQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PDDebateQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('Politizr_AdminBundle_PDDebate_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                ($displayWithType)?$label = 'Réaction ':$label = '';
                if ($idType == 'id') {
                    $subject = PDReactionQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PDReactionQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('Politizr_AdminBundle_PDReaction_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_MEDIA:
                ($displayWithType)?$label = 'Média ':$label = '';
                if ($idType == 'id') {
                    $subject = PDMediaQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PDMediaQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->getFileName();
                    $url = $this->router->generate('Politizr_AdminBundle_PDMedia_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                ($displayWithType)?$label = 'Commentaire (débat) ':$label = '';
                if ($idType == 'id') {
                    $subject = PDDCommentQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PDDCommentQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = substr(strip_tags($subject->getDescription()), 0, 50);
                    $url = $this->router->generate('Politizr_AdminBundle_PDDComment_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                ($displayWithType)?$label = 'Commentaire (réaction) ':$label = '';
                if ($idType == 'id') {
                    $subject = PDRCommentQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PDRCommentQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = substr(strip_tags($subject->getDescription()), 0, 50);
                    $url = $this->router->generate('Politizr_AdminBundle_PDRComment_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                ($displayWithType)?$label = 'Utilisateur ':$label = '';
                if ($idType == 'id') {
                    $subject = PUserQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PUserQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->__toString();
                    $url = $this->router->generate('Politizr_AdminBundle_PUser_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_TAG:
                ($displayWithType)?$label = 'Tag ':$label = '';
                if ($idType == 'id') {
                    $subject = PTagQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PTagQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('Politizr_AdminBundle_PTag_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_CIRCLE:
                ($displayWithType)?$label = 'Groupe ':$label = '';
                if ($idType == 'id') {
                    $subject = PCircleQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PCircleQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->__toString();
                    $url = $this->router->generate('Politizr_AdminBundle_PCircle_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_TOPIC:
                ($displayWithType)?$label = 'Discussions ':$label = '';
                if ($idType == 'id') {
                    $subject = PCTopicQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PCTopicQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->__toString();
                    $url = $this->router->generate('Politizr_AdminBundle_PCTopic_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            case ObjectTypeConstants::TYPE_CIRCLE_OWNER:
                ($displayWithType)?$label = 'Clients ':$label = '';
                if ($idType == 'id') {
                    $subject = PCOwnerQuery::create()->findPk($objectId);
                } elseif ($idType == 'uuid') {
                    $subject = PCOwnerQuery::create()->filterByUuid($objectId)->findOne();
                }

                if ($subject) {
                    $title = $subject->__toString();
                    $url = $this->router->generate('Politizr_AdminBundle_PCOwner_show', array('pk' => $subject->getId()));

                    $html = sprintf('<a href="%s">%sid-%s %s</a>', $url, $label, $subject->getId(), $title);
                } else {
                    // $html = sprintf('%sid-%s non trouvé', $label, $objectId);
                    $html = 'non trouvé';
                }
                break;
            default:
                throw new InconsistentDataException(sprintf('Object class %s not managed.', $objectClass));
        }

        return $html;
    }

    public function getName()
    {
        return 'admin_generic_extension';
    }
}
