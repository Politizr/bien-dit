<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PCircleQuery;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PDDebateQuery;

use Politizr\Constant\CircleConstants;

/**
 * Circle controller
 *
 * @author Lionel Bouzonville
 */
class CircleController extends Controller
{
    /**
     * Circle menu
     * beta
     */
    public function menuAction()
    {
        // $logger = $this->get('logger');
        // $logger->info('*** menuAction');

        $user = $this->getUser();
        if (!$user) {
            throw new InconsistentDataException('Current user not found.');
        }

        $circles = $this->get('politizr.functional.circle')->getAuthorizedCirclesByUser($user);

        return $this->render('PolitizrFrontBundle:Circle:menu.html.twig', array(
            'circles' => $circles,
        ));
    }

    /**
     * Circle detail
     * beta
     */
    public function detailAction($slug)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** detailAction');
        // $logger->info('$slug = '.print_r($slug, true));

        $circle = PCircleQuery::create()->filterBySlug($slug)->findOne();
        if (!$circle) {
            throw new NotFoundHttpException('Circle "'.$slug.'" not found.');
        }
        if (!$circle->getOnline()) {
            throw new NotFoundHttpException('Circle "'.$slug.'" not online.');
        }

        // check access granted
        $this->denyAccessUnlessGranted('circle_detail', $circle);

        // get circle's topics
        $topics = PCTopicQuery::create()
                    ->filterByPCircleId($circle->getId())
                    ->filterByOnline(true)
                    ->find();

        // get users authorized reactions list > generic or dedicated
        $authorizedUsers = $this->get('politizr.functional.circle')->getAuthorizedReactionUsersByCircle($circle);

        // get template path > generic or dedicated
        $templatePath = 'Circle';
        if ($circle->getId() == CircleConstants::CD09_ID_CIRCLE) {
            $templatePath = 'Circle\\cd09';
        }

        return $this->render('PolitizrFrontBundle:'.$templatePath.':detail.html.twig', array(
            'circle' => $circle,
            'topics' => $topics,
            'authorizedUsers' => $authorizedUsers,
        ));
    }

    /**
     * Topic detail
     * beta
     */
    public function topicDetailAction($circleSlug, $slug)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** topicDetailAction');
        // $logger->info('$circleSlug = '.print_r($circleSlug, true));
        // $logger->info('$slug = '.print_r($slug, true));

        $circle = PCircleQuery::create()->filterBySlug($circleSlug)->findOne();
        if (!$circle) {
            throw new NotFoundHttpException('Circle "'.$circleSlug.'" not found.');
        }
        if (!$circle->getOnline()) {
            throw new NotFoundHttpException('Circle "'.$circleSlug.'" not online.');
        }

        $topic = PCTopicQuery::create()->filterBySlug($slug)->findOne();
        if (!$topic) {
            throw new NotFoundHttpException('Topic "'.$slug.'" not found.');
        }
        if (!$topic->getOnline()) {
            throw new NotFoundHttpException('Topic "'.$slug.'" not online.');
        }

        // check access granted
        $this->denyAccessUnlessGranted('topic_detail', $topic);

        // @todo XHR loading
        // get topic's debates
        $debates = PDDebateQuery::create()
                    ->filterByPCTopicId($topic->getId())
                    ->filterByOnline(true)
                    ->find();

        return $this->render('PolitizrFrontBundle:Topic:detail.html.twig', array(
            'circle' => $circle,
            'topic' => $topic,
            'debates' => $debates,
        ));
    }
}
