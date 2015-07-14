<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUserQuery;

/**
 * Public controller
 *
 * @author  Lionel Bouzonville
 */
class PublicController extends Controller
{
    /**
     *  Accueil
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        // @todo most "active" debate today
        $activeDebate = PDDebateQuery::create()
            ->online()
            ->orderByLast()
            ->findOne();

        // most "followed" debate today
        $followedDebate = PDDebateQuery::create()
            ->online()
            ->filterByLastDay()
            ->orderByMostFollowed()
            ->orderByLast()
            ->findOne();

        // search oldest debate if none found
        if (null === $followedDebate) {
            $followedDebate = PDDebateQuery::create()
                ->online()
                ->filterByLastWeek()
                ->orderByMostFollowed()
                ->orderByLast()
                ->findOne();
            if (null === $followedDebate) {
                $followedDebate = PDDebateQuery::create()
                    ->online()
                    ->filterByLastMonth()
                    ->orderByMostFollowed()
                    ->orderByLast()
                    ->findOne();
                if (null === $followedDebate) {
                    $followedDebate = PDDebateQuery::create()
                        ->online()
                        ->orderByLast()
                        ->findOne();
                }
            }
        }

        // profil public le plus populaire
        $citizenUser = PUserQuery::create()
            ->online()
            ->filterByLastDay()
            ->filterByQualified(false)
            ->orderByMostFollowed()
            ->findOne();
        
        // search oldest users if none found
        if (null === $citizenUser) {
            $citizenUser = PUserQuery::create()
                ->online()
                ->filterByLastWeek()
                ->filterByQualified(false)
                ->orderByMostFollowed()
                ->findOne();
            if (null === $citizenUser) {
                $citizenUser = PUserQuery::create()
                    ->online()
                    ->filterByLastMonth()
                    ->filterByQualified(false)
                    ->orderByMostFollowed()
                    ->findOne();
                if (null === $citizenUser) {
                    $citizenUser = PUserQuery::create()
                        ->online()
                        ->filterByQualified(false)
                        ->orderByMostFollowed()
                        ->findOne();
                }
            }
        }

        // profil débatteur le plus populaire
        $qualifiedUser = PUserQuery::create()
            ->online()
            ->filterByLastDay()
            ->filterByQualified(true)
            ->orderByMostFollowed()
            ->findOne();
        
        // search oldest users if none found
        if (null === $qualifiedUser) {
            $qualifiedUser = PUserQuery::create()
                ->online()
                ->filterByLastWeek()
                ->filterByQualified(true)
                ->orderByMostFollowed()
                ->findOne();
            if (null === $qualifiedUser) {
                $qualifiedUser = PUserQuery::create()
                    ->online()
                    ->filterByLastMonth()
                    ->filterByQualified(true)
                    ->orderByMostFollowed()
                    ->findOne();
                if (null === $qualifiedUser) {
                    $qualifiedUser = PUserQuery::create()
                        ->online()
                        ->filterByQualified(true)
                        ->orderByMostFollowed()
                        ->findOne();
                }
            }
        }

        return $this->render('PolitizrFrontBundle:Public:homepage.html.twig', array(
            'activeDebate' => $activeDebate,
            'followedDebate' => $followedDebate,
            'citizenUser' => $citizenUser,
            'qualifiedUser' => $qualifiedUser,
        ));
    }

    /**
     *  Présentation
     */
    public function presentationAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** presentationAction');

        return $this->render('PolitizrFrontBundle:Public:presentation.html.twig', array(
            ));
    }
}
