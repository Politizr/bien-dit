<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;

/**
 * Listing controller
 *
 * @author Lionel Bouzonville
 */
class ListingController extends Controller
{
    /**
     * Tag listing
     */
    public function tagAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** tagAction');
        $logger->info('$slug = '.print_r($slug, true));

        $tag = PTagQuery::create()->filterBySlug($slug)->findOne();
        if (!$tag) {
            throw new NotFoundHttpException('Tag "'.$slug.'" not found.');
        }
        if (!$tag->getOnline()) {
            throw new NotFoundHttpException('Tag "'.$slug.'" not online.');
        }

        return $this->render('PolitizrFrontBundle:Document:listingByTag.html.twig', array(
            'tag' => $tag
        ));
    }

    /**
     * Organization listing
     */
    public function organizationAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** organizationAction');
        $logger->info('$slug = '.print_r($slug, true));

        $organization = PQOrganizationQuery::create()->filterBySlug($slug)->findOne();
        if (!$organization) {
            throw new NotFoundHttpException('Organization "'.$slug.'" not found.');
        }
        if (!$organization->getOnline()) {
            throw new NotFoundHttpException('Organization "'.$slug.'" not online.');
        }

        return $this->render('PolitizrFrontBundle:Document:listingByOrganization.html.twig', array(
            'organization' => $organization
        ));
    }
}
