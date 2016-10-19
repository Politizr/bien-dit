<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Constant\TagConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PUser;
use Politizr\Model\PTag;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUserQuery;
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
     * Common document "check" validity
     * code beta
     *
     * @param PDocument
     * @param boolean $online
     * @param boolean $published
     * @param boolean
     */
    private function checkDocument(PDocumentInterface $document = null, $online = true, $published = true)
    {
        if (!$document) {
            throw new NotFoundHttpException(sprintf('Document not found.'));
        }
        if ($online && !$document->getOnline()) {
            throw new NotFoundHttpException(sprintf('Document not online.'));
        }
        if ($published && !$document->getPublished()) {
            throw new NotFoundHttpException(sprintf('Document not published.'));
        }

        return true;
    }

    /**
     * Common user "check" validity
     * code beta
     *
     * @param PDocument
     * @param boolean $online
     * @param boolean $published
     * @param boolean
     */
    private function checkUser(PUser $user = null, $online = true)
    {
        if (!$user) {
            throw new NotFoundHttpException(sprintf('User not found.'));
        }
        if ($online && !$user->getOnline()) {
            throw new NotFoundHttpException(sprintf('User not online.'));
        }

        return true;
    }

    // *************************************************************************************************** //
    //                                      DOCUMENT LISTING
    // *************************************************************************************************** //

    /**
     * Top listing
     * code beta
     */
    public function recommendAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** recommendAction');

        $now = new \DateTime();
        $month = $this->get('politizr.tools.global')->getLabelFromMonthNum($now->format('n'));
        $year = $now->format('Y');

        return $this->redirect($this->generateUrl('ListingByRecommendMonthYear', array('month' => $month, 'year' => $year)));
    }

    /**
     * Top listing
     * code beta
     */
    public function recommendMonthYearAction($month, $year)
    {
        $logger = $this->get('logger');
        $logger->info('*** recommendMonthYearAction');

        $numMonth = $this->get('politizr.tools.global')->getNumFromMonthLabel($month);

        $now = new \DateTime();
        $search = new \DateTime();
        $search->setDate($year, $numMonth, 1);

        if ($search > $now) {
            throw new NotFoundHttpException('Cannot recommend with future date');
        }

        return $this->render('PolitizrFrontBundle:Document:listingByRecommend.html.twig', array(
            'top' => true,
            'numMonth' => $numMonth,
            'month' => $month,
            'year' => $year,
        ));
    }

    /**
     * Tag listing w. minimal url
     * code beta
     */
    public function tagRawAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** tagAction');
        $logger->info('$slug = '.print_r($slug, true));

        return $this->redirect($this->generateUrl('ListingByTag', array('slug' => $slug)));
    }

    /**
     * Tag listing
     * code beta
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
     * code beta
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

        return $this->render('PolitizrFrontBundle:PaginatedList:listingByOrganization.html.twig', array(
            'organization' => $organization
        ));
    }

    /**
     * Search publications listing
     * code beta
     */
    public function searchPublicationsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** searchPublicationsAction');

        // Map ids
        $franceTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_FRANCE_ID);
        $fomTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_FOM);
        $europeTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_EUROPE_ID);
        $worldTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_WORLD_ID);

        $mapTagUuids = $this->get('politizr.functional.tag')->getRegionUuids();

        // Current user localization
        $user = $this->getUser();

        $cityUuid = null;
        $departmentUuid = null;
        $regionUuid = null;
        if ($user) {
            $city = $user->getPLCity();
            if ($city) {
                $cityUuid = $city->getUuid();
                $departmentUuid = $this->get('politizr.functional.localization')->getDepartmentTagUuidByCityId($city->getId());
                $regionUuid = $this->get('politizr.functional.localization')->getRegionTagUuidByCityId($city->getId());
            }
        }

        return $this->render('PolitizrFrontBundle:Search:listingBySearchPublications.html.twig', array(
            'searchPublications' => true,
            'franceTag' => $franceTag,
            'fomTag' => $fomTag,
            'europeTag' => $europeTag,
            'worldTag' => $worldTag,
            'mapTagUuids' => $mapTagUuids,
            'cityUuid' => $cityUuid,
            'departmentUuid' => $departmentUuid,
            'regionUuid' => $regionUuid,
            'tags' => array(),
        ));
    }

    /**
     * Search uers listing
     * code beta
     */
    public function searchUsersAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** searchUsersAction');

        // Users listing only for connected people
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('Homepage'));
        }

        // Map ids
        $franceTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_FRANCE_ID);
        $fomTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_FOM);
        $europeTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_EUROPE_ID);
        $worldTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_WORLD_ID);

        $mapTagUuids = $this->get('politizr.functional.tag')->getRegionUuids();

        // Current user localization
        $city = $user->getPLCity();

        $cityUuid = null;
        $departmentUuid = null;
        $regionUuid = null;
        if ($city) {
            $cityUuid = $city->getUuid();
            $departmentUuid = $this->get('politizr.functional.localization')->getDepartmentTagUuidByCityId($city->getId());
            $regionUuid = $this->get('politizr.functional.localization')->getRegionTagUuidByCityId($city->getId());
        }

        return $this->render('PolitizrFrontBundle:Search:listingBySearchUsers.html.twig', array(
            'searchUsers' => true,
            'franceTag' => $franceTag,
            'fomTag' => $fomTag,
            'europeTag' => $europeTag,
            'worldTag' => $worldTag,
            'mapTagUuids' => $mapTagUuids,
            'cityUuid' => $cityUuid,
            'departmentUuid' => $departmentUuid,
            'regionUuid' => $regionUuid,
            'tags' => array(),
        ));
    }


    // *************************************************************************************************** //
    //                                      USER LISTING
    // *************************************************************************************************** //
    
    /**
     * Debate followers
     * code beta
     */
    public function debateFollowersAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateFollowers');
        $logger->info('$slug = '.print_r($slug, true));

        $debate = PDDebateQuery::create()->filterBySlug($slug)->findOne();
        $this->checkDocument($debate);

        return $this->render('PolitizrFrontBundle:User:listingDebateFollowers.html.twig', array(
            'debate' => $debate,
        ));
    }
    
    /**
     * User followers
     * code beta
     */
    public function userFollowersAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** userFollowers');
        $logger->info('$slug = '.print_r($slug, true));

        $user = PUserQuery::create()->filterBySlug($slug)->findOne();
        $this->checkUser($user);

        return $this->render('PolitizrFrontBundle:User:listingUserFollowers.html.twig', array(
            'user' => $user,
        ));
    }
    
    /**
     * User subscribers
     * code beta
     */
    public function userSubscribersAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** userSubscribers');
        $logger->info('$slug = '.print_r($slug, true));

        $user = PUserQuery::create()->filterBySlug($slug)->findOne();
        $this->checkUser($user);

        return $this->render('PolitizrFrontBundle:User:listingUserSubscribers.html.twig', array(
            'user' => $user,
        ));
    }

    // *************************************************************************************************** //
    //                                      USER LISTING
    // *************************************************************************************************** //
    
    /**
     * Tag alphabetical
     * code beta
     */
    public function tagAlphabeticalAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** tagAlphabetical');

        $tags = $this->get('politizr.functional.tag')->getAlphabeticalTagsListing();

        return $this->render('PolitizrFrontBundle:Tag:listingTagAlphabetical.html.twig', array(
            'tags' => $tags,
        ));
    }
}