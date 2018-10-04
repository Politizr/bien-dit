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

        $owners = $this->get('politizr.functional.circle')->getAuthorizedOwnersByUser($user);

        return $this->render('PolitizrFrontBundle:Circle:menu.html.twig', array(
            'owners' => $owners,
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
        // @todo use service / manager to compute online topics in circle
        $topics = PCTopicQuery::create()
                    ->filterByPCircleId($circle->getId())
                    ->filterByOnline(true)
                    ->orderByRank()
                    ->find();

        return $this->render('PolitizrFrontBundle:Circle:detail.html.twig', array(
            'circle' => $circle,
            'topics' => $topics,
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
