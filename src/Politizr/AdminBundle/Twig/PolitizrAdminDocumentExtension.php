<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;

use Politizr\Model\PUser;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDMedia;

/**
 * Document admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminDocumentExtension extends \Twig_Extension
{
    protected $kernel;

    private $documentService;

    private $formFactory;
    private $router;
    private $logger;

    /**
     *
       @param @kernel
     * @param politizr.functional.document
     * @param form.factory
     * @param router
     * @param logger
     */
    public function __construct(
        $kernel,
        $documentService,
        $formFactory,
        $router,
        $logger
    ) {
        $this->kernel = $kernel;
        
        $this->documentService = $documentService;
        
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
                'fileExists',
                array($this, 'fileExists'),
                array('is_safe' => array('html'), 'needs_environment' => false)
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
            'adminUserDebates'  => new \Twig_SimpleFunction(
                'adminUserDebates',
                array($this, 'adminUserDebates'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserReactions'  => new \Twig_SimpleFunction(
                'adminUserReactions',
                array($this, 'adminUserReactions'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserDComments'  => new \Twig_SimpleFunction(
                'adminUserDComments',
                array($this, 'adminUserDComments'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminUserRComments'  => new \Twig_SimpleFunction(
                'adminUserRComments',
                array($this, 'adminUserRComments'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminDebateReactions'  => new \Twig_SimpleFunction(
                'adminDebateReactions',
                array($this, 'adminDebateReactions'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminReactionReactions'  => new \Twig_SimpleFunction(
                'adminReactionReactions',
                array($this, 'adminReactionReactions'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminDebateComments'  => new \Twig_SimpleFunction(
                'adminDebateComments',
                array($this, 'adminDebateComments'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminDebateFollowersQ'  => new \Twig_SimpleFunction(
                'adminDebateFollowersQ',
                array($this, 'adminDebateFollowersQ'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminDebateFollowersC'  => new \Twig_SimpleFunction(
                'adminDebateFollowersC',
                array($this, 'adminDebateFollowersC'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminReactionComments'  => new \Twig_SimpleFunction(
                'adminReactionComments',
                array($this, 'adminReactionComments'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                              FILTERS                                                     */
    /* ######################################################################################################## */

    /**
     * Check if file physically exists
     *
     * @param PDMedia $media
     * @return boolean
     */
    public function fileExists(PDMedia $media)
    {
        if ($fileName = $media->getFileName()) {
            return file_exists($this->kernel->getRootDir() . '/../web' . PathConstants::DOCUMENT_UPLOAD_WEB_PATH.$fileName);
        }

        return false;
    }

    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */

    /**
     * User's debates
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserDebates(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserDebates');
        // $this->logger->info('$pUser = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Debate:_userDebates.html.twig',
            array(
                'user' => $user,
                'debates' => $user->getDebates(),
            )
        );

        return $html;
    }


    /**
     * User's reactions
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserReactions(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserReactions');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_userReactions.html.twig',
            array(
                'user' => $user,
                'reactions' => $user->getReactions(),
            )
        );

        return $html;

    }


    /**
     * User's debates' comments
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserDComments(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserDComments');

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Comment:_userComments.html.twig',
            array(
                'user' => $user,
                'comments' => $user->getDComments(),
                'type' => ObjectTypeConstants::TYPE_DEBATE_COMMENT,
            )
        );

        return $html;

    }


    /**
     * User's reactions' comments
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserRComments(\Twig_Environment $env, PUser $user)
    {
        $this->logger->info('*** adminUserRComments');

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Comment:_userComments.html.twig',
            array(
                'user' => $user,
                'comments' => $user->getRComments(),
                'type' => ObjectTypeConstants::TYPE_REACTION_COMMENT,
            )
        );

        return $html;

    }


    /**
     * Display debate's reactions
     *
     * @param PDDebate $debate
     * @return string
     */
    public function adminDebateReactions(\Twig_Environment $env, PDDebate $debate)
    {
        $this->logger->info('*** adminDebateReactions');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_debateReactions.html.twig',
            array(
                'debate' => $debate,
                'reactions' => $debate->getTreeReactions(),
            )
        );

        return $html;
    }

    /**
     * Display reaction's reactions
     *
     * @param PDReaction $reaction
     * @return string
     */
    public function adminReactionReactions(\Twig_Environment $env, PDReaction $reaction)
    {
        $this->logger->info('*** adminReactionReactions');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_reactionReactions.html.twig',
            array(
                'reaction' => $reaction,
                'reactions' => $reaction->getDescendantsReactions(true, true),
            )
        );

        return $html;
    }

    /**
     * Debate's comments
     *
     * @param PDDDebate $debate
     * @return string
     */
    public function adminDebateComments(\Twig_Environment $env, PDDebate $debate)
    {
        $this->logger->info('*** adminDebateComments');
        // $this->logger->info('$debate = '.print_r($debate, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Debate:_comments.html.twig',
            array(
                'debate' => $debate,
                'comments' => $debate->getComments(),
            )
        );

        return $html;
    }

    /**
     *  Gestion des followers élus d'un débat
     *
     * @param $pdDebate     PDDebate    PDDebate
     *
     * @return string
     */
    public function adminDebateFollowersQ(\Twig_Environment $env, PDDebate $pdDebate)
    {
        $this->logger->info('*** adminDebateFollowersQ');
        // $this->logger->info('$pdDebate = '.print_r($pdDebate, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment:DebateFollowers.html.twig',
            array(
                'pdDebate' => $pdDebate,
                'pUsers' => $pdDebate->getFollowersQ(),
            )
        );

        return $html;
    }


    /**
     *  Gestion des followers citoyens d'un débat
     *
     * @param $pdDebate     PDDebate    PDDebate
     *
     * @return string
     */
    public function adminDebateFollowersC(\Twig_Environment $env, PDDebate $pdDebate)
    {
        $this->logger->info('*** adminUserFollowersC');
        // $this->logger->info('$pdDebate = '.print_r($pdDebate, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment:DebateFollowers.html.twig',
            array(
                'pdDebate' => $pdDebate,
                'pUsers' => $pdDebate->getFollowersC(),
            )
        );

        return $html;
    }

    /**
     * Reaction's comments
     *
     * @param PDReaction $reaction
     * @return string
     */
    public function adminReactionComments(\Twig_Environment $env, PDReaction $reaction)
    {
        $this->logger->info('*** adminReactionComments');
        // $this->logger->info('$reaction = '.print_r($reaction, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_comments.html.twig',
            array(
                'reaction' => $reaction,
                'comments' => $reaction->getComments(),
            )
        );

        return $html;
    }

    public function getName()
    {
        return 'admin_document_extension';
    }
}
