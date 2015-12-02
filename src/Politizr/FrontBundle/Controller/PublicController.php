<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

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
     * Homepage
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        // redirect if connected
        if ($profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix()) {
            return $this->redirect($this->generateUrl(sprintf('Homepage%s', $profileSuffix)));
        }

        // @todo most "active" debate today
        $activeDebate = PDDebateQuery::create()
            ->online()
            ->orderByLast()
            ->findOne();

        // most "followed" debate today
        $followedDebate = PDDebateQuery::create()
            ->online()
            // ->filterById($activeDebate->getId(), \Criteria::NOT_EQUAL)
            ->filterByLastDay()
            ->orderByMostFollowed()
            ->orderByLast()
            ->findOne();

        // search oldest debate if none found
        if (null === $followedDebate) {
            $followedDebate = PDDebateQuery::create()
                ->online()
                // ->filterById($activeDebate->getId(), \Criteria::NOT_EQUAL)
                ->filterByLastWeek()
                ->orderByMostFollowed()
                ->orderByLast()
                ->findOne();
            if (null === $followedDebate) {
                $followedDebate = PDDebateQuery::create()
                    ->online()
                    // ->filterById($activeDebate->getId(), \Criteria::NOT_EQUAL)
                    ->filterByLastMonth()
                    ->orderByMostFollowed()
                    ->orderByLast()
                    ->findOne();
                if (null === $followedDebate) {
                    $followedDebate = PDDebateQuery::create()
                        ->online()
                        // ->filterById($activeDebate->getId(), \Criteria::NOT_EQUAL)
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
        $electedUser = PUserQuery::create()
            ->online()
            ->filterByLastDay()
            ->filterByQualified(true)
            ->orderByMostFollowed()
            ->findOne();
        
        // search oldest users if none found
        if (null === $electedUser) {
            $electedUser = PUserQuery::create()
                ->online()
                ->filterByLastWeek()
                ->filterByQualified(true)
                ->orderByMostFollowed()
                ->findOne();
            if (null === $electedUser) {
                $electedUser = PUserQuery::create()
                    ->online()
                    ->filterByLastMonth()
                    ->filterByQualified(true)
                    ->orderByMostFollowed()
                    ->findOne();
                if (null === $electedUser) {
                    $electedUser = PUserQuery::create()
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
            'electedUser' => $electedUser,
        ));
    }

    /**
     * Help
     */
    public function helpAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** helpAction');

        return $this->render('PolitizrFrontBundle:Public:help.html.twig', array(
        ));
    }
}
