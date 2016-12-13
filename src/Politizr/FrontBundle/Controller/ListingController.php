<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Constant\LocalizationConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PUser;
use Politizr\Model\PTag;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PLCountryQuery;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLCityQuery;
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
     * Redirect 301
     * @todo w. htaccess
     */
    public function tagClassicAction($slug)
    {
        return $this->redirect($this->generateUrl('UserDetail', array('slug' => $slug)));
    }


    /**
     * Tag listing w. minimal url or user detail
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

        // @todo > test si slug appartient à p_l_country / p_l_region / p_l_department / p_l_city
        // => redirect screen publications + zoom carte ok
        $country = PLCountryQuery::create()->filterBySlug($slug)->findOne();

        if ($country) {
            return $this->redirect($this->generateUrl('ListingSearchPublications', array('slug' => $slug)));
        } else {
            $region = PLRegionQuery::create()->filterBySlug($slug)->findOne();
        
            if ($region) {
                return $this->redirect($this->generateUrl('ListingSearchPublications', array('slug' => $slug)));
            } else {
                $department = PLDepartmentQuery::create()->filterBySlug($slug)->findOne();

                if ($department) {
                    return $this->redirect($this->generateUrl('ListingSearchPublications', array('slug' => $slug)));
                } else {
                    $city = PLCityQuery::create()->filterBySlug($slug)->findOne();

                    if ($city) {
                        return $this->redirect($this->generateUrl('ListingSearchPublications', array('slug' => $slug)));
                    }
                }
            }
        }

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
    public function searchPublicationsAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** searchPublicationsAction');

        // Map ids
        $france = PLCountryQuery::create()->findPk(LocalizationConstants::FRANCE_ID);
        $fom = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_FOM);

        $mapUuids = $this->get('politizr.functional.localization')->getRegionUuids();

        // preset the map w. given localization
        $currentUuid = null;
        $currentType = null;
        if ($slug) {
            $country = PLCountryQuery::create()->filterBySlug($slug)->findOne();

            if (!$country) {
                $region = PLRegionQuery::create()->filterBySlug($slug)->findOne();
            
                if (!$region) {
                    $department = PLDepartmentQuery::create()->filterBySlug($slug)->findOne();

                    if (!$department) {
                        $city = PLCityQuery::create()->filterBySlug($slug)->findOne();

                        if (!$city) {
                            throw new NotFoundHttpException('Localization "'.$slug.'" not found.');
                        } else {
                            $currentUuid = $city->getUuid();
                            $currentType = LocalizationConstants::TYPE_CITY;
                        }
                    } else {
                        $currentUuid = $department->getUuid();
                        $currentType = LocalizationConstants::TYPE_DEPARTMENT;
                    }
                } else {
                    $currentUuid = $region->getUuid();
                    $currentType = LocalizationConstants::TYPE_REGION;
                }
            } else {
                $currentUuid = $country->getUuid();
                $currentType = LocalizationConstants::TYPE_COUNTRY;
            }
        }

        // Get current user localization
        $user = $this->getUser();

        $cityUuid = null;
        $departmentUuid = null;
        $regionUuid = null;

        if ($user) {
            $city = $user->getPLCity();
            if ($city) {
                $cityUuid = $city->getUuid();
                $department = $city->getPLDepartment();
                if ($department) {
                    $departmentUuid = $department->getUuid();
                    $region = $department->getPLRegion();
                    if ($region) {
                        $regionUuid = $region->getUuid();
                    }
                }
            }
        }

        return $this->render('PolitizrFrontBundle:Search:listingBySearchPublications.html.twig', array(
            'searchPublications' => true,
            'france' => $france,
            'fom' => $fom,
            'mapUuids' => $mapUuids,
            'currentUuid' => $currentUuid,
            'currentType' => $currentType,
            'cityUuid' => $cityUuid,
            'departmentUuid' => $departmentUuid,
            'regionUuid' => $regionUuid,
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
        $france = PLCountryQuery::create()->findPk(LocalizationConstants::FRANCE_ID);
        $fom = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_FOM);

        $mapUuids = $this->get('politizr.functional.localization')->getRegionUuids();

        // Current user localization
        $user = $this->getUser();

        $cityUuid = null;
        $departmentUuid = null;
        $regionUuid = null;
        if ($user) {
            $city = $user->getPLCity();
            if ($city) {
                $cityUuid = $city->getUuid();
                $department = $city->getPLDepartment();
                if ($department) {
                    $departmentUuid = $department->getUuid();
                    $region = $department->getPLRegion();
                    if ($region) {
                        $regionUuid = $region->getUuid();
                    }
                }
            }
        }

        return $this->render('PolitizrFrontBundle:Search:listingBySearchUsers.html.twig', array(
            'searchUsers' => true,
            'france' => $france,
            'fom' => $fom,
            'mapUuids' => $mapUuids,
            'cityUuid' => $cityUuid,
            'departmentUuid' => $departmentUuid,
            'regionUuid' => $regionUuid,
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