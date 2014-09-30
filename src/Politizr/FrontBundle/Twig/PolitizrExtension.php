<?php
namespace Politizr\Frontbundle\Twig;

use Politizr\Constant\ReputationConstants;

use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;


/**
 * Fonctions/Helper Politizr
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
        );
    }


    /* ######################################################################################################## */
    /*                                                DEBATS                                                    */
    /* ######################################################################################################## */


    /**
     *  Affiche le lien "Suivre" / "Ne plus suivre" / "M'inscrire" suivant le cas
     *
     * @param $objectId     ID objet
     * @param $objectType   Type d'objet suivi: user - debate
     *
     * @return string
     */
    public function linkFollow($objectId, $objectType)
    {
        // $this->logger->info('*** linkFollow');
        // $this->logger->info('$objectId = '.print_r($objectId, true));
        // $this->logger->info('$objectType = '.print_r($objectType, true));

        $isFollower = false;

        if ($this->pUser) {
            if ($objectType == 'debate') {
                $nb = PUFollowDDQuery::create()
                    ->filterByPUserId($this->pUser->getId())
                    ->filterByPDDebateId($objectId)
                    ->count();
                if ($nb > 0) {
                    $isFollower = true;
                }
            } elseif ($objectType == 'user') {
                $nb = PUFollowUQuery::create()
                    ->filterByPUserFollowerId($this->pUser->getId())
                    ->filterByPUserId($objectId)
                    ->count();
                if ($nb > 0) {
                    $isFollower = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment:FollowAction.html.twig', array(
                                'objectId' => $objectId,
                                'objectType' => $objectType,
                                'isFollower' => $isFollower
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
                            'PolitizrFrontBundle:Fragment:Tags.html.twig', array(
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
                            'PolitizrFrontBundle:Fragment:EditTags.html.twig', array(
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
                            'PolitizrFrontBundle:Fragment:Tags.html.twig', array(
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
                            'PolitizrFrontBundle:Fragment:EditTags.html.twig', array(
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
                            'PolitizrFrontBundle:Fragment:Tags.html.twig', array(
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
                            'PolitizrFrontBundle:Fragment:EditTags.html.twig', array(
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



    public function getName()
    {
        return 'p_link_extension';
    }



}