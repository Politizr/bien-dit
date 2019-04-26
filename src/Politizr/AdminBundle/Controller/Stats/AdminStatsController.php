<?php
namespace Politizr\AdminBundle\Controller\Stats;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\TagConstants;

/**
 * Description
 *
 * @author lionel
 */
class AdminStatsController extends Controller {
    /**
     * User stats
     */
    public function userAction()
    {
        $stats = $this->get('politizr.functional.tag')->getUserTopTagsStats(TagConstants::TAG_STATS_USER_LIMIT);
        $keys = $stats[0];
        $labels = $stats[1];
        return $this->render('PolitizrAdminBundle:Stats:user.html.twig', array(
            'keys' => $keys,
            'labels' => $labels,
        ));
    }
    
    /**
     * Debate stats
     */
    public function debateAction()
    {
        $stats = $this->get('politizr.functional.tag')->getDebateTopTagsStats(TagConstants::TAG_STATS_DEBATE_LIMIT);
        $keys = $stats[0];
        $labels = $stats[1];
        return $this->render('PolitizrAdminBundle:Stats:debate.html.twig', array(
            'keys' => $keys,
            'labels' => $labels,
        ));
    }
}