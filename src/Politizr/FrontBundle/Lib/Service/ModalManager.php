<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PDocument;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;

use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

/**
 * Services métiers associés aux modal.
 *
 * @author Lionel Bouzonville
 */
class ModalManager
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
     * Chargement d'une box modal contenant une liste paginée
     */
    public function modalPaginatedList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** modalPaginatedList');
        
        // Récupération args
        $request = $this->sc->get('request');

        $twigTemplate = $request->get('twigTemplate');

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:'.$twigTemplate
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /* ######################################################################################################## */
    /*                                              SUGGESTIONS                                                 */
    /* ######################################################################################################## */

    /**
     *  Listing de débats du jour ordonnancés suivant l'argument récupéré
     *
     */
    public function dailyDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** dailyDebateList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // $params = $request->request->all();
        // $logger->info('$params = ' . print_r($params, true));

        $order = $request->get('order');
        $logger->info('$order = ' . print_r($order, true));
        $filters = $request->get('filters');
        $logger->info('$filters = ' . print_r($filters, true));
        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));

        $debates = PDDebateQuery::create()
                    ->online()
                    ->filterByKeywords($filters, $user)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     *  Listing de profils du jour ordonnancés suivant l'argument récupéré
     *
     */
    public function dailyUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** dailyUserList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $order = $request->get('order');
        $logger->info('$order = ' . print_r($order, true));
        $filters = $request->get('filters');
        $logger->info('$filters = ' . print_r($filters, true));
        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));

        // Requête suivant order
        $users = PUserQuery::create()
                    ->online()
                    ->filterByKeywords($filters, $user)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }


}
