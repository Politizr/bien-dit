<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PUser;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;

/**
 * Document admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminDocumentExtension extends \Twig_Extension
{
    private $logger;

    private $formFactory;

    protected $documentService;
    private $router;
    private $templating;

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
            'adminUserDebates'  => new \Twig_SimpleFunction(
                'adminUserDebates',
                array($this, 'adminUserDebates'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserReactions'  => new \Twig_SimpleFunction(
                'adminUserReactions',
                array($this, 'adminUserReactions'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserDComments'  => new \Twig_SimpleFunction(
                'adminUserDComments',
                array($this, 'adminUserDComments'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserRComments'  => new \Twig_SimpleFunction(
                'adminUserRComments',
                array($this, 'adminUserRComments'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateReactions'  => new \Twig_SimpleFunction(
                'adminDebateReactions',
                array($this, 'adminDebateReactions'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateComments'  => new \Twig_SimpleFunction(
                'adminDebateComments',
                array($this, 'adminDebateComments'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateFollowersQ'  => new \Twig_SimpleFunction(
                'adminDebateFollowersQ',
                array($this, 'adminDebateFollowersQ'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateFollowersC'  => new \Twig_SimpleFunction(
                'adminDebateFollowersC',
                array($this, 'adminDebateFollowersC'),
                array(
                    'is_safe' => array('html')
                    )
            ),
            'adminReactionComments'  => new \Twig_SimpleFunction(
                'adminReactionComments',
                array($this, 'adminReactionComments'),
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
     * User's debates
     *
     * @param PUser $user
     * @return string
     */
    public function adminUserDebates(PUser $user)
    {
        $this->logger->info('*** adminUserDebates');
        // $this->logger->info('$pUser = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
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
    public function adminUserReactions(PUser $user)
    {
        $this->logger->info('*** adminUserReactions');
        // $this->logger->info('$user = '.print_r($user, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
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
    public function adminUserDComments(PUser $user)
    {
        $this->logger->info('*** adminUserDComments');

        // Construction du rendu du tag
        $html = $this->templating->render(
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
    public function adminUserRComments(PUser $user)
    {
        $this->logger->info('*** adminUserRComments');

        // Construction du rendu du tag
        $html = $this->templating->render(
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
     * @param PDDDebate $debate
     * @return string
     */
    public function adminDebateReactions(PDDebate $debate)
    {
        $this->logger->info('*** adminDebateReactions');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrAdminBundle:Fragment\\Reaction:_debateReactions.html.twig',
            array(
                'debate' => $debate,
                'reactions' => $debate->getTreeReactions(),
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
    public function adminDebateComments(PDDebate $debate)
    {
        $this->logger->info('*** adminDebateComments');
        // $this->logger->info('$debate = '.print_r($debate, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
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
    public function adminDebateFollowersQ(PDDebate $pdDebate)
    {
        $this->logger->info('*** adminDebateFollowersQ');
        // $this->logger->info('$pdDebate = '.print_r($pdDebate, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
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
    public function adminDebateFollowersC(PDDebate $pdDebate)
    {
        $this->logger->info('*** adminUserFollowersC');
        // $this->logger->info('$pdDebate = '.print_r($pdDebate, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
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
    public function adminReactionComments(PDReaction $reaction)
    {
        $this->logger->info('*** adminReactionComments');
        // $this->logger->info('$reaction = '.print_r($reaction, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
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
