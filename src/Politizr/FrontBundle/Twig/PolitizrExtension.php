<?php
namespace Politizr\Frontbundle\Twig;

use Politizr\Constant\ReputationConstants;
use Politizr\Model\PRBadgeMetal;
use Politizr\Model\PRAction;
use Politizr\Model\PDocument;

use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUReputationRAQuery;


/**
 * Fonctions Twig Politizr Front
 *
 * TODO:
 *      - constantes en dur sur type de document (linkFollow) > utiliser les constantes de PDocument
 *
 * @author Lionel Bouzonville
 */
class PolitizrExtension extends \Twig_Extension
{
    private $logger;

    private $router;
    private $templating;

    private $pUser;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->logger = $serviceContainer->get('logger');
        $this->router = $serviceContainer->get('router');
        $this->templating = $serviceContainer->get('templating');

        // Récupération du user en session
        $token = $serviceContainer->get('security.context')->getToken();
        if ($token && $user = $token->getUser()) {

            $className = 'Politizr\Model\PUser';
            if ($user && $user instanceof $className) {
                $this->pUser = $user;
            } else {
                $this->pUser = null;
            }
        } else {
            $this->pUser = null;
        }

    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */


    /**
     *  Renvoie la liste des filtres
     */
//     public function getFilters()
//     {
//         return array(
//             new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
//         );
//     }


    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'linkFollow'  => new \Twig_Function_Method($this, 'linkFollow', array(
                    'is_safe' => array('html')
                    )
            ),
            'debateTags'  => new \Twig_Function_Method($this, 'debateTags', array(
                    'is_safe' => array('html')
                    )
            ),
            'debateTagsEdit'  => new \Twig_Function_Method($this, 'debateTagsEdit', array(
                    'is_safe' => array('html')
                    )
            ),
            'userFollowTags'  => new \Twig_Function_Method($this, 'userFollowTags', array(
                    'is_safe' => array('html')
                    )
            ),
            'userFollowTagsEdit'  => new \Twig_Function_Method($this, 'userFollowTagsEdit', array(
                    'is_safe' => array('html')
                    )
            ),
            'userTaggedTags'  => new \Twig_Function_Method($this, 'userTaggedTags', array(
                    'is_safe' => array('html')
                    )
            ),
            'userTaggedTagsEdit'  => new \Twig_Function_Method($this, 'userTaggedTagsEdit', array(
                    'is_safe' => array('html')
                    )
            ),
            'badgeMetalTwBootClass'  => new \Twig_Function_Method($this, 'badgeMetalTwBootClass', array(
                    'is_safe' => array('html')
                    )
            ),
            'linkNote'  => new \Twig_Function_Method($this, 'linkNote', array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                                DEBATS                                                    */
    /* ######################################################################################################## */


    /**
     *  Affiche le lien "Suivre" / "Ne plus suivre" / "M'inscrire" suivant le cas
     *
     * @param $objectId     ID objet
     * @param $context      Type d'objet suivi: user - debate
     *
     * @return string
     */
    public function linkFollow($object, $context)
    {
        $this->logger->info('*** linkFollow');
        $this->logger->info('$object = '.print_r($object, true));
        $this->logger->info('$context = '.print_r($context, true));

        $follower = false;
        if ($this->pUser) {
            switch($context) {
                case PDocument::TYPE_DEBATE:
                    $follow = PUFollowDDQuery::create()
                        ->filterByPUserId($this->pUser->getId())
                        ->filterByPDDebateId($object->getId())
                        ->findOne();
                    
                    if ($follow) {
                        $follower = true;
                    }

                    break;
                case PDocument::TYPE_USER:
                    $follow = PUFollowUQuery::create()
                        ->filterByPUserFollowerId($this->pUser->getId())
                        ->filterByPUserId($object->getId())
                        ->findOne();
                    
                    if ($follow) {
                        $follower = true;
                    }

                    break;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Follow:Subscribe.html.twig', array(
                                'object' => $object,
                                'context' => $context,
                                'follower' => $follower
                                )
                    );

        return $html;

    }

   /**
     *  Affiche les tags d'un débat suivant le type fourni
     *
     * @param $debate     PDDebate    PDDebate
     * @param $tagTypeId  integer     ID type de tag
     *
     * @return string
     */
    public function debateTags($debate, $tagTypeId)
    {
        $this->logger->info('*** debateTags');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Tag:List.html.twig', array(
                                'tags' => $debate->getTags($tagTypeId),
                                'tagTypeId' => $tagTypeId
                                )
                    );

        return $html;

    }

    /**
     *  Gestion des tags d'un débat
     *
     *  @param $debate      PDDebate    PDDebate
     *  @param $tagTypeId   integer     ID type de tag
     *  @param $zoneId      integer     ID de la zone CSS
     *  @param $newTag      boolean     Ajout de nouveau tag possible ou pas
     *
     *  @return string
     */
    public function debateTagsEdit($debate, $tagTypeId, $zoneId = 1, $newTag = false)
    {
        $this->logger->info('*** debateTagsEdit');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\Tag:glAddList.html.twig', array(
                                'object' => $debate,
                                'tagTypeId' => $tagTypeId,
                                'zoneId' => $zoneId,
                                'newTag' => $newTag,
                                'tags' => $debate->getTags($tagTypeId),
                                'addPath' => 'DebateAddTag',
                                'deletePath' => 'DebateDeleteTag',
                                )
                    );

        return $html;
    }



   /**
     *  Affiche les tags suivis par un user suivant le type fourni
     *
     * @param $pUser        pUser       PDDebate
     * @param $tagTypeId  integer     ID type de tag
     *
     * @return string
     */
    public function userFollowTags($pUser, $tagTypeId)
    {
        $this->logger->info('*** userFollowTags');
        // $this->logger->info('$pUser = '.print_r($pUser, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Tag:List.html.twig', array(
                                'tags' => $pUser->getFollowTags($tagTypeId),
                                'tagTypeId' => $tagTypeId
                                )
                    );

        return $html;

    }

    /**
     *  Gestion des tags suivi d'un user
     *
     *  @param $user        PUser      PUser
     *  @param $tagTypeId   integer    ID type de tag
     *  @param $zoneId      integer    ID de la zone CSS
     *  @param $newTag      boolean     Ajout de nouveau tag possible ou pas
     *
     *  @return string
     */
    public function userFollowTagsEdit($user, $tagTypeId, $zoneId = 1, $newTag = false)
    {
        $this->logger->info('*** userFollowTagsEdit');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\Tag:glAddList.html.twig', array(
                                'object' => $user,
                                'tagTypeId' => $tagTypeId,
                                'zoneId' => $zoneId,
                                'newTag' => $newTag,
                                'tags' => $user->getFollowTags($tagTypeId),
                                'addPath' => 'UserFollowAddTag',
                                'deletePath' => 'UserFollowDeleteTag',
                                )
                    );

        return $html;
    }




   /**
     *  Affiche les tags associés à un user suivant le type fourni
     *
     * @param $uiser        uiser       PDDebate
     * @param $tagTypeId  integer     ID type de tag
     *
     * @return string
     */
    public function userTaggedTags($user, $tagTypeId)
    {
        $this->logger->info('*** userTaggedTags');
        // $this->logger->info('$uiser = '.print_r($uiser, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Tag:List.html.twig', array(
                                'tags' => $user->getTaggedTags($tagTypeId),
                                'tagTypeId' => $tagTypeId
                                )
                    );

        return $html;

    }


    /**
     *  Gestion des tags associé à un user
     *
     *  @param $user        PUser       PUser
     *  @param $tagTypeId   integer     ID type de tag
     *  @param $zoneId      integer     ID de la zone CSS
     *  @param $newTag      boolean     Ajout de nouveau tag possible ou pas
     *
     *  @return string
     */
    public function userTaggedTagsEdit($user, $tagTypeId, $zoneId = 1, $newTag = false)
    {
        $this->logger->info('*** userTaggedTagsEdit');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\Tag:glAddList.html.twig', array(
                                'object' => $user,
                                'tagTypeId' => $tagTypeId,
                                'zoneId' => $zoneId,
                                'newTag' => $newTag,
                                'tags' => $user->getTaggedTags($tagTypeId),
                                'addPath' => 'UserTaggedAddTag',
                                'deletePath' => 'UserTaggedDeleteTag',
                                )
                    );

        return $html;
    }



   /**
     *  Renvoit une classe de label twitter bootstrap 3 en fonction de l'id BadgeMetal
     *
     *  @param $uiser        uiser       PDDebate
     *  @param $tagTypeId  integer     ID type de tag
     *
     *  @return string
     */
    public function badgeMetalTwBootClass($badgeMetalId)
    {
        $this->logger->info('*** badgeMetalTwBootClass');
        // $this->logger->info('$badgeMetalId = '.print_r($badgeMetalId, true));

        $twClass = 'label-info';
        if ($badgeMetalId == PRBadgeMetal::GOLD) {
            $twClass = 'label-warning';
        } elseif($badgeMetalId == PRBadgeMetal::SILVER) {
            $twClass = 'label-default';
        } elseif($badgeMetalId == PRBadgeMetal::BRONZE) {
            $twClass = 'label-danger';
        }

        return $twClass;
    }


    /* ######################################################################################################## */
    /*                                              NOTATION                                                    */
    /* ######################################################################################################## */


    /**
     *  Affiche & active / désactive les Note + / Note -
     *
     *  @param $object          objet
     *  @param $context         PDocument::TYPE_DEBATE / TYPE_REACTION / TYPE_COMMENT
     *
     *  @return string
     */
    public function linkNote($object, $context)
    {
        // $this->logger->info('*** linkNote');
        // $this->logger->info('$object = '.print_r($object, true));
        // $this->logger->info('$context = '.print_r($context, true));

        $pos = false;
        $neg = false;

        if ($this->pUser) {
            // Le document a-t-il déjà été noté pos et/ou neg?
            switch($context) {
                case PDocument::TYPE_DEBATE:
                    $queryPos = PUReputationRAQuery::create()
                        ->filterByPRActionId(PRAction::ID_D_AUTHOR_DEBATE_NOTE_POS)
                        ->filterByPObjectName('Politizr\Model\PDDebate');
                    $queryNeg = PUReputationRAQuery::create()
                        ->filterByPRActionId(PRAction::ID_D_AUTHOR_DEBATE_NOTE_NEG)
                        ->filterByPObjectName('Politizr\Model\PDDebate');
                    break;
                case PDocument::TYPE_REACTION:
                    $queryPos = PUReputationRAQuery::create()
                        ->filterByPRActionId(PRAction::ID_D_AUTHOR_REACTION_NOTE_POS)
                        ->filterByPObjectName('Politizr\Model\PDReaction');
                    $queryNeg = PUReputationRAQuery::create()
                        ->filterByPRActionId(PRAction::ID_D_AUTHOR_REACTION_NOTE_NEG)
                        ->filterByPObjectName('Politizr\Model\PDReaction');
                    break;
                case PDocument::TYPE_COMMENT:
                    $queryPos = PUReputationRAQuery::create()
                        ->filterByPRActionId(PRAction::ID_D_AUTHOR_COMMENT_NOTE_POS)
                        ->filterByPObjectName('Politizr\Model\PDComment');
                    $queryNeg = PUReputationRAQuery::create()
                        ->filterByPRActionId(PRAction::ID_D_AUTHOR_COMMENT_NOTE_NEG)
                        ->filterByPObjectName('Politizr\Model\PDComment');
                    break;
            }

            $notePos = $queryPos->filterByPUserId($this->pUser->getId())
                ->filterByPObjectId($object->getId())
                ->findOne();
            if ($notePos) {
                $pos = true;
            }

            $noteNeg = $queryNeg->filterByPUserId($this->pUser->getId())
                ->filterByPObjectId($object->getId())
                ->findOne();
            if ($noteNeg) {
                $neg = true;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Reputation:Notation.html.twig', array(
                                'object' => $object,
                                'context' => $context,
                                'pos' => $pos,
                                'neg' => $neg,
                                )
                    );

        return $html;

    }




    public function getName()
    {
        return 'p_link_extension';
    }



}