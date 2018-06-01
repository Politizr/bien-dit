<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\QualificationConstants;
use Politizr\Constant\PathConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUMandate;

use Politizr\FrontBundle\Form\Type\PUMandateType;
use Politizr\FrontBundle\Form\Type\PUserIdCheckType;

use Politizr\AdminBundle\Form\Type\AdminPUserLocalizationType;

/**
 * User admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminUserExtension extends \Twig_Extension
{
    protected $kernel;

    protected $documentService;
    private $globalTools;

    private $formFactory;
    private $router;
    private $logger;

    /**
     *
     * @param @kernel
     * @param politizr.functional.document
     * @param politizr.tools.global
     * @param form.factory
     * @param router
     * @param logger
     */
    public function __construct(
        $kernel,
        $documentService,
        $globalTools,
        $formFactory,
        $router,
        $logger
    ) {
        $this->kernel = $kernel;

        $this->documentService = $documentService;
        $this->globalTools = $globalTools;

        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */
    /**
     * Filters list
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'userFileSize',
                array($this, 'userFileSize'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

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
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserSubscribers'  => new \Twig_SimpleFunction(
                'adminUserSubscribers',
                array($this, 'adminUserSubscribers'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserBadges'  => new \Twig_SimpleFunction(
                'adminUserBadges',
                array($this, 'adminUserBadges'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserMandates'  => new \Twig_SimpleFunction(
                'adminUserMandates',
                array($this, 'adminUserMandates'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserIdCheck'  => new \Twig_SimpleFunction(
                'adminUserIdCheck',
                array($this, 'adminUserIdCheck'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserAffinities'  => new \Twig_SimpleFunction(
                'adminUserAffinities',
                array($this, 'adminUserAffinities'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserReputation'  => new \Twig_SimpleFunction(
                'adminUserReputation',
                array($this, 'adminUserReputation'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserLocalization'  => new \Twig_SimpleFunction(
                'adminUserLocalization',
                array($this, 'adminUserLocalization'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                              FILTERS                                                     */
    /* ######################################################################################################## */

    /**
     * Compute user's file size
     *
     * @param PUser $user
     * @return html
     */
    public function userFileSize(\Twig_Environment $env, PUser $user)
    {
        if ($fileName = $user->getFileName()) {
            if (file_exists($this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH.$fileName)) {
                $fileSize = fileSize($this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH.$fileName);

                return $fileSize;
            }
        }

        return null;
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
    public function adminUserFollowers(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserFollowers');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $env->render(
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
    public function adminUserSubscribers(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserSubscribers');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $env->render(
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
    public function adminUserBadges(\Twig_Environment $env, PUser $pUser, $prBadgeType, $zoneId = 1)
    {
        $this->logger->info('*** adminUserBadges');
        // $this->logger->info('$pUser = '.print_r($pUser, true));
        // $this->logger->info('$prBadgeType = '.print_r($prBadgeType, true));

        // Construction du rendu du tag
        $html = $env->render(
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
    public function adminUserMandates(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserMandates');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Mandates form views
        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

        // New mandate
        $mandate = new PUMandate();
        $formMandate = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV, $user->getId()), $mandate);

        // Construction du rendu du tag
        $html = $env->render(
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
     * Validate user's identity
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserIdCheck(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserIdCheck');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // id check form
        $formIdCheck = $this->formFactory->create(new PUserIdCheckType($user->getId()), $user);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\User:_idCheck.html.twig',
            array(
                'user' => $user,
                'formIdCheck' => $formIdCheck->createView(),
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
    public function adminUserAffinities(\Twig_Environment $env, PUser $pUser)
    {
        $this->logger->info('*** adminUserAffinities');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $env->render(
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
    public function adminUserReputation(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserReputation');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Reputation:user.html.twig',
            array(
                'user' => $user,
            )
        );

        return $html;
    }

    /**
     * User's localization
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserLocalization(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserLocalization');
        // $this->logger->info('$user = '.print_r($user, true));

        $form = $this->formFactory->create(new AdminPUserLocalizationType($user));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\User:_localization.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

        return $html;
    }

    public function getName()
    {
        return 'admin_user_extension';
    }
}
