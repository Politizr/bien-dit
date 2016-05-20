<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\QualificationConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUMandate;

use Politizr\FrontBundle\Form\Type\PUMandateType;

/**
 * User admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminUserExtension extends \Twig_Extension
{
    private $logger;

    private $formFactory;

    protected $documentService;
    private $router;
    private $templating;

    private $globalTools;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->logger = $serviceContainer->get('logger');

        $this->formFactory = $serviceContainer->get('form.factory');

        $this->documentService = $serviceContainer->get('politizr.functional.document');

        $this->router = $serviceContainer->get('router');
        $this->templating = $serviceContainer->get('templating');

        $this->globalTools = $serviceContainer->get('politizr.tools.global');
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
            'adminUserFollowers'  => new \Twig_SimpleFunction(
                'adminUserFollowers',
                array($this, 'adminUserFollowers'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserSubscribers'  => new \Twig_SimpleFunction(
                'adminUserSubscribers',
                array($this, 'adminUserSubscribers'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserBadges'  => new \Twig_SimpleFunction(
                'adminUserBadges',
                array($this, 'adminUserBadges'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserMandates'  => new \Twig_SimpleFunction(
                'adminUserMandates',
                array($this, 'adminUserMandates'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserAffinities'  => new \Twig_SimpleFunction(
                'adminUserAffinities',
                array($this, 'adminUserAffinities'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserReputation'  => new \Twig_SimpleFunction(
                'adminUserReputation',
                array($this, 'adminUserReputation'),
                array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */

    /**
     * User's followers
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserFollowers(PUser $user)
    {
        $this->logger->info('*** adminUserFollowers');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Follow:_userFollowersSubscribers.html.twig',
            array(
                'user' => $user,
                'users' => $user->getFollowers(),
            )
        );

        return $html;
    }

    /**
     * User's subscribers
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserSubscribers(PUser $user)
    {
        $this->logger->info('*** adminUserSubscribers');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Follow:_userFollowersSubscribers.html.twig',
            array(
                'user' => $user,
                'users' => $user->getSubscribers(),
            )
        );

        return $html;
    }

    /**
     *  Gestion des badges d'un user
     *
     * @param $pUser        PUser
     * @param $prBadgeType  integer     ID type de badge
     * @param $zoneId       integer     ID de la zone CSS
     *
     * @return string
     */
    public function adminUserBadges(PUser $pUser, $prBadgeType, $zoneId = 1)
    {
        $this->logger->info('*** adminUserBadges');
        // $this->logger->info('$pUser = '.print_r($pUser, true));
        // $this->logger->info('$prBadgeType = '.print_r($prBadgeType, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment:UserBadges.html.twig',
            array(
                'pUser' => $pUser,
                'badges' => $pUser->getBadges($prBadgeType),
                'zoneId' => $zoneId,
            )
        );

        return $html;

    }

    /**
     * Edit user's mandates
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserMandates(PUser $user)
    {
        $this->logger->info('*** adminUserMandates');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Mandates form views
        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

        // New mandate
        $mandate = new PUMandate();
        $formMandate = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV, $user->getId()), $mandate);

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\User:_mandates.html.twig',
            array(
                'user' => $user,
                'formMandate' => $formMandate?$formMandate->createView():null,
                'formMandateViews' => $formMandateViews?$formMandateViews:null,
            )
        );

        return $html;
    }

    /**
     *  Gestion des affinités organisation d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserAffinities(PUser $pUser)
    {
        $this->logger->info('*** adminUserAffinities');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment:UserOrganizations.html.twig',
            array(
                'pUser' => $pUser,
                'organizations' => $pUser->getCurrentOrganizations(),
            )
        );

        return $html;
    }

    /**
     * User's reputation
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserReputation(PUser $user)
    {
        $this->logger->info('*** adminUserReputation');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Reputation:user.html.twig',
            array(
                'user' => $user,
            )
        );

        return $html;
    }

    public function getName()
    {
        return 'admin_user_extension';
    }
}
