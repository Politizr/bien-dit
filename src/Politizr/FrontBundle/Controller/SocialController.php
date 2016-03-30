<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Social (network) controller
 *
 * @author Lionel Bouzonville
 */
class SocialController extends Controller
{
    /**
     *  Partage RS
     */
    public function shareAction($uri, $tweetText)
    {
        // URL de partage public
        $uri = $this->getTinyUrl($uri);

        return $this->render('PolitizrFrontBundle:Navigation\\Social:_share.html.twig', array(
                    'uri' => $uri,
                    'tweetText' => $tweetText
            ));
    }

    /* ######################################################################################################## */
    /*          FONCTIONS PRIVEES
    /*          TODO > services dédiés
    /* ######################################################################################################## */

    /**
     *  URL Shortener avec TinyURL
     *
     *  @param $url
     *  @return string url
     */
    private function getTinyUrl($url)
    {
        $logger = $this->get('logger');
        $logger->info('*** getTinyUrl');

        // with tinyurl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://tinyurl.com/api-create.php?url=".$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $bookUrlShort = curl_exec($ch);
        curl_close($ch);

        return $bookUrlShort;
    }
}
