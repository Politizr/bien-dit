<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PTag;
use Politizr\Model\PTagType;

use Politizr\Model\PTagQuery;
use Politizr\Model\PTagTypeQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUTaggedTQuery;


/**
 * Services métiers associés aux tags 
 *
 * @author Lionel Bouzonville
 */
class TagManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                                               FONCTIONS PRIVÉES                                          */
    /* ######################################################################################################## */

    /**
     *  Gestion des différents cas de figure suite à l'ajout d'un tag: tag sélectionné depuis la liste, tag existant mais non sélectionné, tag non existant.
     *
     *  @param $tagId       integer
     *  @param $tagtTitle   string
     *  @param $tagTypeId   integer
     *  @param $newTag      boolean     création de tag autorisée ou pas
     *
     *  @return integer     id du tag sélectionné / retrouvé / créé
     */
    private function retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag = false) {
        if (!$tagId) {
            // Récupération via slug
            $slug = StudioEchoUtils::generateSlug($tagTitle);
            $tag = PTagQuery::create()->filterByPTTagTypeId($tagTypeId)->filterBySlug($slug)->findOne();

            if ($tag) {
                $tagId = $tag->getId();
            } elseif($newTag) {
                $tagId = PTagQuery::create()->addTag($tagTitle, $tagTypeId, true);
                
            } else {
                throw new \Exception('Création de nouveaux tags non autorisés, merci d\'en choisir un depuis la liste contextuelle proposée.');
            }
        }

        return $tagId;
    }


    /* ######################################################################################################## */
    /*                                               TAGS COMMUNS                                               */
    /* ######################################################################################################## */

    /**
     *  Renseigne et retourne un tableau contenant les tags
     *
     */
    public function getTags() {
        $logger = $this->sc->get('logger');
        $logger->info('*** getTags');
        
        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $tagTypeId = $request->get('tagTypeId');
        $logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        $zoneId = $request->get('zoneId');
        $logger->info('$zoneId = ' . print_r($zoneId, true));

        // Récupération des tags
        $tags = PTagQuery::create()
            ->select(array('id', 'title'))
            ->filterByOnline(true)
            ->filterByPTTagTypeId($tagTypeId)
            ->orderByTitle()
            ->find()
            ->toArray();
        // $logger->info('$pTags = ' . print_r($pTags, true));

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'tags' => $tags,
            'zoneId' => $zoneId
            );
    }



    /* ######################################################################################################## */
    /*                                               TAGS DEBATS                                                */
    /* ######################################################################################################## */

    /**
     *  Association d'un tag à un débat
     *
     */
    public function debateAddTag() {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateAddTag');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $tagTitle = $request->get('tagTitle');
        $tagId = $request->get('tagId');
        $tagTypeId = $request->get('tagTypeId');
        $objectId = $request->get('objectId');
        $newTag = $request->get('newTag');

        // Gestion tag non existant
        $tagId = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag);

        // Association du tag au debat
        $created = PDDTaggedTQuery::create()->addElement($objectId, $tagId);

        if (!$created) {
            $htmlTag = null;
        } else  {
            // Construction du rendu du tag
            $tag = PTagQuery::create()->findPk($tagId);
            $templating = $this->sc->get('templating');
            $htmlTag = $templating->render(
                                'PolitizrFrontBundle:Fragment\\Tag:ListRow.html.twig', array(
                                    'objectId' => $objectId,
                                    'tag' => $tag,
                                    'deleteUrl' => $this->sc->get('router')->generate('DebateDeleteTag')
                                    )
                        );
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'created' => $created,
            'htmlTag' => $htmlTag
            );
    }


    /**
     *  Suppression de l'association d'un tag à un débat
     *
     */
    public function debateDeleteTag() {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateDeleteTag');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $tagId = $request->get('tagId');
        $objectId = $request->get('objectId');

        // Suppression du tag / profil
        $deleted = PDDTaggedTQuery::create()->deleteElement($objectId, $tagId);

        return true;
    }

    /* ######################################################################################################## */
    /*                                               TAGS USERS                                                 */
    /* ######################################################################################################## */


    /**
     *  Association d'un tag suivi d'un user
     *
     */
    public function userFollowAddTag() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userFollowAddTag');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $tagTitle = $request->get('tagTitle');
        $tagId = $request->get('tagId');
        $tagTypeId = $request->get('tagTypeId');
        $objectId = $request->get('objectId');
        $newTag = $request->get('newTag');

        // Gestion tag non existant
        $tagId = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag);

        // Association du tag au user
        $created = PUFollowTQuery::create()->addElement($objectId, $tagId);

        if (!$created) {
            $htmlTag = null;
        } else  {
            // Construction du rendu du tag
            $tag = PTagQuery::create()->findPk($tagId);
            $templating = $this->sc->get('templating');
            $htmlTag = $templating->render(
                                'PolitizrFrontBundle:Fragment\\Tag:ListRow.html.twig', array(
                                    'objectId' => $objectId,
                                    'tag' => $tag,
                                    'deleteUrl' => $this->sc->get('router')->generate('UserFollowDeleteTag')
                                    )
                        );
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'created' => $created,
            'htmlTag' => $htmlTag
            );
    }


    /**
     *  Suppression de l'association d'un tag suivi d'un user
     *
     */
    public function userFollowDeleteTag() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userFollowDeleteTag');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $tagId = $request->get('tagId');
        $objectId = $request->get('objectId');

        // Suppression du tag / profil
        $deleted = PUFollowTQuery::create()->deleteElement($objectId, $tagId);

        return true;
    }


    /**
     *  Association d'un tag caractérisant un user
     *
     */
    public function userTaggedAddTag() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userTaggedAddTag');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');


        // Récupération args
        $tagTitle = $request->get('tagTitle');
        $tagId = $request->get('tagId');
        $tagTypeId = $request->get('tagTypeId');
        $objectId = $request->get('objectId');
        $newTag = $request->get('newTag');

        // Gestion tag non existant
        $tagId = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag);

        // Association du tag au user
        $created = PUTaggedTQuery::create()->addElement($objectId, $tagId);

        if (!$created) {
            $htmlTag = null;
        } else  {
            // Construction du rendu du tag
            $tag = PTagQuery::create()->findPk($tagId);
            $templating = $this->sc->get('templating');
            $htmlTag = $templating->render(
                                'PolitizrFrontBundle:Fragment\\Tag:ListRow.html.twig', array(
                                    'objectId' => $objectId,
                                    'tag' => $tag,
                                    'deleteUrl' => $this->sc->get('router')->generate('UserFollowDeleteTag')
                                    )
                        );
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'created' => $created,
            'htmlTag' => $htmlTag
            );
    }


    /**
     *  Suppression de l'association d'un tag suivi d'un user
     *
     */
    public function userTaggedDeleteTag() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userTaggedDeleteTag');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $tagId = $request->get('tagId');
        $objectId = $request->get('objectId');

        // Suppression du tag / profil
        $deleted = PUTaggedTQuery::create()->deleteElement($objectId, $tagId);

        return true;
    }



}