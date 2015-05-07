<?php
namespace Politizr\FrontBundle\Lib\Service;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

/**
 * Services métiers génériques.
 *
 * @author Lionel Bouzonville
 */
class GenericManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                                           LISTING PAGINE (FONCTIONS AJAX)                                */
    /* ######################################################################################################## */

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
            'PolitizrFrontBundle:Fragment\\PaginatedList:'.$twigTemplate
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }
}
