<?php
namespace Politizr\Frontbundle\Twig;

use Politizr\Model\PDocument;
use Politizr\Model\PUStatus;

use Politizr\Model\PUFollowUQuery;


/**
 * Extension Twig / Gestion des users
 *
 * @author Lionel Bouzonville
 */
class PolitizrUserExtension extends \Twig_Extension
{
    private $sc;

    private $logger;
    private $router;
    private $templating;

    private $user;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->sc = $serviceContainer;
        
        $this->logger = $serviceContainer->get('logger');
        $this->router = $serviceContainer->get('router');
        $this->templating = $serviceContainer->get('templating');

        // Récupération du user en session
        $token = $serviceContainer->get('security.context')->getToken();
        if ($token && $user = $token->getUser()) {

            $className = 'Politizr\Model\PUser';
            if ($user && $user instanceof $className) {
                $this->user = $user;
            } else {
                $this->user = null;
            }
        } else {
            $this->user = null;
        }

    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */


    /**
     *  Renvoie la liste des filtres
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('photo', array($this, 'photo'), array(
                    'is_safe' => array('html')
                    )
            ),
            new \Twig_SimpleFilter('followTags', array($this, 'followTags'), array(
                    'is_safe' => array('html')
                    )
            ),
            new \Twig_SimpleFilter('tags', array($this, 'tags'), array(
                    'is_safe' => array('html')
                    )
            ),
            new \Twig_SimpleFilter('linkSubscribeUser', array($this, 'linkSubscribeUser'), array(
                    'is_safe' => array('html')
                    )
            ),
            new \Twig_SimpleFilter('followersUser', array($this, 'followersUser'), array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'isGrantedC'  => new \Twig_Function_Method($this, 'isGrantedC', array(
                    'is_safe' => array('html')
                    )
            ),
            'isGrantedE'  => new \Twig_Function_Method($this, 'isGrantedE', array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     *  Photo de profil d'un user
     *
     *  @param $user         PUser       PUser
     *
     *  @return html
     */
    public function photo($user, $filterName = 'user_bio', $effect = 'img-rounded')
    {
        // $this->logger->info('*** photo');
        // $this->logger->info('$user = '.print_r($user, true));

        $path = 'bundles/politizrfront/images/default_user.png';
        if ($user && $fileName = $user->getFileName()) {
            $path = 'uploads/users/'.$fileName;
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Global:UserPhoto.html.twig', array(
                                'user' => $user,
                                'path' => $path,
                                'filterName' => $filterName,
                                'effect' => $effect,
                                )
                    );

        return $html;
    }


   /**
     *  Affiche les tags suivis par un user suivant le type fourni
     *
     * @param $user         PUser       PUser
     * @param $tagTypeId    integer     ID type de tag
     *
     * @return string
     */
    public function followTags($user, $tagTypeId)
    {
        $this->logger->info('*** followTags');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Tag:List.html.twig', array(
                                'tags' => $user->getFollowTags($tagTypeId),
                                'tagTypeId' => $tagTypeId
                                )
                    );

        return $html;

    }

   /**
     *  Affiche les tags associés à un user suivant le type fourni
     *
     * @param $user         PUser       PUser
     * @param $tagTypeId    integer     ID type de tag
     *
     * @return string
     */
    public function tags($user, $tagTypeId)
    {
        $this->logger->info('*** tags');
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
     *  Affiche le lien "Suivre" / "Ne plus suivre" / "M'inscrire" suivant le cas
     *
     * @param $user       PUser
     *
     * @return string
     */
    public function linkSubscribeUser(\Politizr\Model\PUser $user)
    {
        // $this->logger->info('*** linkSubscribeDebate');
        // $this->logger->info('$debate = '.print_r($user, true));

        $follower = false;
        if ($this->user) {
            $follow = PUFollowUQuery::create()
                ->filterByPUserFollowerId($this->user->getId())
                ->filterByPUserId($user->getId())
                ->findOne();
            
            if ($follow) {
                $follower = true;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Follow:Subscribe.html.twig', array(
                                'object' => $user,
                                'type' => PDocument::TYPE_USER,
                                'follower' => $follower
                                )
                    );

        return $html;

    }

    /**
     *  Affiche le bloc des followers
     *
     *  @param $user       PUser
     *
     *  @return string
     */
    public function followersUser(\Politizr\Model\PUser $user)
    {
        // $this->logger->info('*** followersUser');
        // $this->logger->info('$debate = '.print_r($user, true));

        $nbC = 0;
        $nbQ = 0;
        $followersC = array();
        $followersQ = array();

        $nbC = $user->countPUserFollowersC();
        $nbQ = $user->countPUserFollowersQ();
        $followersC = $user->getPUserFollowersC();
        $followersQ = $user->getPUserFollowersQ();

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Follow:Followers.html.twig', array(
                                'nbC' => $nbC,
                                'nbQ' => $nbQ,
                                'followersC' => $followersC,
                                'followersQ' => $followersQ,
                                )
                    );

        return $html;

    }



    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     *  Test l'autorisation d'un user citoyen et de l'état de son inscription
     *
     * @param $user         PUser à tester
     *
     * @return string
     */
    public function isGrantedC()
    {
        $this->logger->info('*** isGrantedC');

        if ($this->sc->get('security.context')->isGranted('ROLE_CITIZEN') &&
            $this->user &&
            $this->user->getOnline()) {
            return true;    
        }

        return false;
    }


    /**
     *  Test l'autorisation d'un user débatteur et de l'état de son inscription
     *
     * @param $user         PUser à tester
     *
     * @return string
     */
    public function isGrantedE()
    {
        $this->logger->info('*** isGrantedE');

        if ($this->sc->get('security.context')->isGranted('ROLE_ELECTED') &&
            $this->user &&
            $this->user->getPUStatusId() == PUStatus::ACTIVED &&
            $this->user->getOnline()) {
            return true;    
        }

        return false;
    }


    public function getName()
    {
        return 'p_e_user';
    }



}