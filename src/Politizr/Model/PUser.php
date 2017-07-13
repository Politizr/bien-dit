<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUser;

use Politizr\Exception\InconsistentDataException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\QualificationConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\ReputationConstants;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

/**
 * User object model
 *
 * @author Lionel Bouzonville
 */
class PUser extends BasePUser implements UserInterface
{
    // simple upload management
    public $uploadedFileName;

    // security
    protected $plainPassword;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->isNew()) {
            $this->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        }
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_USER;
    }

    /**
     *
     * @return DateTime
     */
    public function getPublishedAt()
    {
        return $this->getCreatedAt();
    }

    /**
     * Check if profile is qualified
     *
     * @return boolean
     */
    public function isQualified()
    {
        return $this->getQualified();
    }

    /**
     * Check if profile has been validated
     *
     * @return boolean
     */
    public function isValidated()
    {
        return $this->getValidated();
    }

    /**
     *
     */
    public function getFullName()
    {
        return trim($this->getFirstname().' '.$this->getName());
    }

    /**
     * Test if activity less than Xmn
     *
     * @return boolean
     */
    public function isActiveNow()
    {
        $delay = new \DateTime();
        $delay->modify('-5 minute');

        if ($this->getLastActivity() >= $delay) {
            return true;
        }

        return false;
    }

    /**
     * Override to manage accented characters
     *
     * @return string
     */
    public function createRawSlug()
    {
        if ($this->getFirstname() && $this->getName()) {
            $toSlug =  StudioEchoUtils::transliterateString($this->getFirstname() . '-' . $this->getName());

            $slug = $this->cleanupSlugPart($toSlug);
        } elseif ($realname = $this->getRealname()) {
            $toSlug =  StudioEchoUtils::transliterateString($realname);

            $slug = $this->cleanupSlugPart($toSlug);
        } else {
            $slug = parent::createRawSlug();
        }

        return $slug;
    }

    /**
     * Compute a user file name
     * @todo not used for the moment
     *
     * @return string
     */
    public function computeFileName()
    {
        $fileName = 'politizr-user-' . StudioEchoUtils::randomString();

        return $fileName;
    }

    // ************************************************************************************ //
    //                                          SECURITY
    // ************************************************************************************ //

    /**
     * @todo which functionality use serialization/deserialization & for what? oauth?
     *
     * @return array
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->id,
                $this->p_u_status_id,
                $this->username,
                $this->name,
                $this->firstname,
                $this->birthday,
                $this->email,
                $this->salt,
                $this->password,
                $this->expired,
                $this->locked,
                $this->credentials_expired,
                $this->qualified,
                $this->validated,
                $this->online,
                $this->_new,
            )
        );
    }

    /**
     * @todo which functionality use serialization/deserialization & for what? oauth?
     *
     * @param array
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 1, null));

        list(
                $this->id,
                $this->p_u_status_id,
                $this->username,
                $this->name,
                $this->firstname,
                $this->birthday,
                $this->email,
                $this->salt,
                $this->password,
                $this->expired,
                $this->locked,
                $this->credentials_expired,
                $this->qualified,
                $this->validated,
                $this->online,
                $this->_new,
        ) = $data;
    }

    /**
     * @see UserInterface::eraseCredentials
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     *
     * @param string
     * @return PUser
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    // ************************************************************************************ //
    //                                      VALIDATION
    // ************************************************************************************ //

    /**
     *
     * @param ClassMetadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new UniqueObject(array(
            'fields'  => 'email',
            'message' => 'Cet email est déjà utilisé.',
        )));
    
        $metadata->addConstraint(new UniqueObject(array(
            'fields'  => 'username',
            'message' => 'Cet identifiant est déjà pris.',
        )));
    }

    // ************************************************************************************ //
    //                                      FOLLOWERS / SUBSCRIBERS
    // ************************************************************************************ //

    /**
     *
     * @param PUser $user
     * @return PUser
     */
    public function addFollower(PUser $user)
    {
        $follower = new PUFollowU();

        $follower->setPUserId($this->getId());
        $follower->setPUserFollowerId($user->getId());

        $follower->save();

        return $this;
    }

    /**
     *
     * @param PUser $user
     * @return PUser
     */
    public function removeFollower(PUser $user)
    {
        PUFollowUQuery::create()
            ->filterByPUserId($this->getId())
            ->filterByPUserFollowerId($user->getId())
            ->delete();

        return $this;
    }

    /**
     *
     * @param PUser $user
     * @return PUser
     */
    public function addSubscriber(PUser $user)
    {
        $follower = new PUFollowU();

        $follower->setPUserId($user->getId());
        $follower->setPUserFollowerId($this->getId());

        $follower->save();

        return $this;
    }

    /**
     *
     * @param PUser $user
     * @return PUser
     */
    public function removeSubscriber(PUser $user)
    {
        PUFollowUQuery::create()
            ->filterByPUserId($user->getId())
            ->filterByPUserFollowerId($this->getId())
            ->delete();

        return $this;
    }

    /**
     * Users' subscribers
     * Equal nest management
     *
     * @param \Criteria $query
     * @param string $andWhere
     * @return PropelObjectCollection[PUser]
     */
    public function getFollowers(\Criteria $query = null, $andWhere = '')
    {
        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $sql = "
    SELECT DISTINCT p_user.id
    FROM p_user
    INNER JOIN p_u_follow_u ON
    p_user.id = p_u_follow_u.p_user_id
    OR
    p_user.id = p_u_follow_u.p_user_follower_id
    WHERE
    p_user.id IN (
        SELECT p_u_follow_u.p_user_follower_id
        FROM p_u_follow_u
        WHERE p_u_follow_u.p_user_id = ?
        %s
    )";
        $sql = sprintf($sql, $andWhere);

        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $this->getPrimaryKey(), \PDO::PARAM_INT);
        $stmt->bindValue(2, $this->getPrimaryKey(), \PDO::PARAM_INT);

        $stmt->execute();

        $listPKs = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $users = PUserQuery::create(null, $query)
            ->addUsingAlias(PUserPeer::ID, $listPKs, \Criteria::IN)
            ->find($con);

        return $users;
    }

    /**
     *
     * @return integer
     */
    public function countFollowers()
    {
        $users = $this->getFollowers();

        return count($users);
    }

    /**
     *
     * @return PropelObjectCollection[PUser]
     */
    public function getFollowersQ()
    {
        $query = PUserQuery::create()
            ->filterByQualified(true);
        
        $users = $this->getFollowers($query);

        return $users;
    }

    /**
     *
     * @return integer
     */
    public function countFollowersQ()
    {
        $users = $this->getFollowersQ();

        return count($users);
    }

    /**
     *
     * @return PropelObjectCollection[PUser]
     */
    public function getFollowersC()
    {
        $query = PUserQuery::create()->filterByQualified(false);
        $users = $this->getFollowers($query);

        return $users;
    }

    /**
     *
     * @return integer
     */
    public function countFollowersC()
    {
        $users = $this->getFollowersC();

        return count($users);
    }

    /**
     * Users' subscribers
     * Equal nest management
     *
     * @return PropelCollection[PUser]
     */
    public function getSubscribers(\Criteria $query = null, \PropelPDO $con = null)
    {
        if ($con === null) {
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        }

        $sql = "
    SELECT DISTINCT p_user.id
    FROM p_user
    INNER JOIN p_u_follow_u ON
    p_user.id = p_u_follow_u.p_user_id
    OR
    p_user.id = p_u_follow_u.p_user_follower_id
    WHERE
    p_user.id IN (
        SELECT p_u_follow_u.p_user_id
        FROM p_u_follow_u
        WHERE p_u_follow_u.p_user_follower_id = ?
    )";

        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $this->getPrimaryKey(), \PDO::PARAM_INT);
        $stmt->execute();

        $listPKs = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $users = PUserQuery::create(null, $query)
            ->addUsingAlias(PUserPeer::ID, $listPKs, \Criteria::IN)
            ->find($con);

        return $users;
    }

    /**
     *
     * @return integer
     */
    public function countSubscribers()
    {
        $users = $this->getSubscribers();

        return count($users);
    }

    /**
     *
     * @return PropelCollection[PUser]
     */
    public function getSubscribersQ()
    {
        $query = PUserQuery::create()->filterByQualified(true);
        $users = $this->getSubscribers($query);

        return $users;
    }

    /**
     *
     * @return integer
     */
    public function countSubscribersQ()
    {
        $users = $this->getSubscribersQ();

        return count($users);
    }

    /**
     *
     * @return PropelCollection[PUser]
     */
    public function getSubscribersC()
    {
        $query = PUserQuery::create()->filterByQualified(false);
        $users = $this->getSubscribers($query);

        return $users;
    }

    /**
     *
     * @return integer
     */
    public function countSubscribersC()
    {
        $users = $this->getSubscribersC();

        return count($users);
    }

    /**
     *
     * @param integer $userId
     * @return boolean
     */
    public function isFollowedBy($userId)
    {
        $followers = PUFollowUQuery::create()
            ->filterByPUserId($this->getId())
            ->filterByPUserFollowerId($userId)
            ->count();

        if ($followers > 0) {
            return true;
        }

        return false;
    }

    // ************************************************************************************ //
    //                                          QUALIFICATION
    // ************************************************************************************ //

    /**
     * User's user mandates
     *
     * @return PropelCollection[PUMandate]
     */
    public function countMandates()
    {
        return parent::countPUMandates();
    }

    /**
     * User's user mandates
     *
     * @return PropelCollection[PUMandate]
     */
    public function getMandates($nbMax = null)
    {
        $query = PUMandateQuery::create()
            ->_if($nbMax)
                ->setLimit($nbMax)
            ->_endIf()
            ->orderByBeginAt('desc');

        return parent::getPUMandates($query);
    }

    /**
     * User's current mandates
     *
     * @return PropelCollection[PQMandate]
     */
    public function getCurrentMandates()
    {
        $pqMandate = PQMandateQuery::create()
            ->usePUMandateQuery()
                ->filterByPUserId($this->getId())
                ->filterByEndAt(array('min' => time()))
                    ->_or()
                ->filterByEndAt(null)
            ->endUse()
            ->find();

        return $pqMandate;
    }

    // ************************************************************************************ //
    //                                          ORGANIZATIONS
    // ************************************************************************************ //

    /**
     * User's current organizations
     *
     * @param int $typeId Organization type
     * @param boolean $online
     * @return PropelCollection[PQOrganization]
     */
    public function getCurrentOrganizations($typeId = QualificationConstants::TYPE_ELECTIV, $online = true)
    {
        $query = PQOrganizationQuery::create()
            ->filterIfPQTypeId($typeId)
            ->filterIfOnline($online)
            ->setDistinct();

        return parent::getPUCurrentQOPQOrganizations($query);
    }

    /**
     * User's affinity organizations
     *
     * @param int $typeId Organization type
     * @param boolean $online
     * @return PropelCollection[PQOrganization]
     */
    public function getAffinityOrganizations($typeId = QualificationConstants::TYPE_ELECTIV, $online = true)
    {
        $query = PQOrganizationQuery::create()
            ->filterIfPQTypeId($typeId)
            ->filterIfOnline($online)
            ->setDistinct();

        return parent::getPUAffinityQOPQOrganizations($query);
    }

    /**
     * User's PUCurrentQO
     * /!\ functionaly limited to one single & current linked object
     *
     * @param int $typeId Organization type
     * @return PUCurrentQO
     */
    public function getPUCurrentQO($typeId = QualificationConstants::TYPE_ELECTIV)
    {
        $puCurrentQo = PUCurrentQOQuery::create()
            ->filterByPUserId($this->getId())
            ->usePUCurrentQOPQOrganizationQuery()
                ->filterByPQTypeId(QualificationConstants::TYPE_ELECTIV)
            ->endUse()
            ->findOne();

        return $puCurrentQo;
    }

    // ************************************************************************************ //
    //                                          TAGS
    // ************************************************************************************ //

    /**
     * User's array tags
     * /!\ used by elastica indexation
     *
     * @param integer $tagTypeId
     * @param boolean $online
     * @return PropelCollection[PTag]
     */
    public function getArrayTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->select('Title')
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            ->setDistinct();

        return parent::getPuTaggedTPTags($query)->toArray();
    }

    /**
     * User's tags
     *
     * @param integer $tagTypeId
     * @param boolean $withHidden with hidden user's tags
     * @param boolean $online
     * @return PropelCollection[PTag]
     */
    public function getTags($tagTypeId = null, $hidden = false, $online = true)
    {
        $query = PTagQuery::create()
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            ->usePuTaggedTPTagQuery()
                ->filterIfHidden($hidden)
            ->endUse()
            ->withColumn('p_u_tagged_t.hidden', 'hidden')
            ->orderByTitle()
            ->setDistinct();

        return parent::getPuTaggedTPTags($query);
    }

    /**
     * Check if user is tagged with tagId
     *
     * @param int $tagId
     * @return boolean
     */
    public function isTagged($tagId)
    {
        $nb = PUTaggedTQuery::create()
            ->filterByPUserId($this->getId())
            ->filterByPTagId($tagId)
            ->count();

        if ($nb > 0) {
            return true;
        }

        return false;
    }
    
    // ************************************************************************************ //
    //                                          DOCUMENTS
    // ************************************************************************************ //

    /**
     * User's debates
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDDebate]
     */
    public function getDebates($online = true, $published = true)
    {
        $debates = PDDebateQuery::create()
            ->filterByPUserId($this->getId())
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->orderByCreatedAt('desc')
            ->find();

        return $debates;
    }

    /**
     * User's debates count
     *
     * @param boolean $online
     * @param boolean $published
     * @return integer
     */
    public function countDebates($online = true, $published = true)
    {
        $nbDebates = PDDebateQuery::create()
            ->filterByPUserId($this->getId())
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->count();

        return $nbDebates;
    }

    /**
     * User's reactions
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDReaction]
     */
    public function getReactions($online = true, $published = true)
    {
        $reactions = PDReactionQuery::create()
            ->filterByPUserId($this->getId())
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->orderByCreatedAt('desc')
            ->find();

        return $reactions;
    }

    /**
     * User's reactions count
     *
     * @param boolean $online
     * @param boolean $published
     * @return integer
     */
    public function countReactions($online = true, $published = true)
    {
        $nbReactions = PDReactionQuery::create()
            ->filterByPUserId($this->getId())
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->count();

        return $nbReactions;
    }

    /**
     * User's comments count
     *
     * @param boolean $online
     * @return integer
     */
    public function countComments($online = true)
    {
        $nbDComments = PDDCommentQuery::create()
            ->filterByPUserId($this->getId())
            ->filterIfOnline($online)
            ->count();

        $nbRComments = PDRCommentQuery::create()
            ->filterByPUserId($this->getId())
            ->filterIfOnline($online)
            ->count();

        return $nbDComments + $nbRComments;
    }

    /**
     * User's debates + reactions count + comments count
     *
     * @param boolean $online
     * @param boolean $published
     * @return integer
     */
    public function countPublications($online = true, $published = true)
    {
        $nbPublications = 
            $this->countDebates($online, $published)
            + $this->countReactions($online, $published)
            + $this->countComments($online)
            ;

        return $nbPublications;
    }

    /**
     * User's followed debates
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDDebate]
     */
    public function getFollowedDebates($online = true, $published = true)
    {
        $debates = PDDebateQuery::create()
            ->usePUFollowDDQuery()
                ->filterByPUserId($this->getId())
            ->endIf()
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->orderByCreatedAt('desc')
            ->find();

        return $debates;
    }

    /**
     * User's debates' comments
     *
     * @param boolean $online
     * @return PropelCollection[PDDebate]
     */
    public function getDComments($online = true)
    {
        $query = PDDCommentQuery::create()
            ->filterIfOnline($online)
            ->orderByCreatedAt('desc')
            ;

        return parent::getPDDComments($query);
    }

    /**
     * User's reactions' comments
     *
     * @param boolean $online
     * @return PropelCollection[PDDebate]
     */
    public function getRComments($online = true)
    {
        $query = PDRCommentQuery::create()
            ->filterIfOnline($online)
            ->orderByCreatedAt('desc')
            ;

        return parent::getPDRComments($query);
    }


    // ************************************************************************************ //
    //                                          REPUTATION
    // ************************************************************************************ //

    /**
     * @see addPuReputationRbPRBadge
     */
    public function addBadge(PRBadge $badge)
    {
        $userBadge = new PUBadge();

        $userBadge->setPUserId($this->getId());
        $userBadge->setPRBadgeId($badge->getId());

        $userBadge->save();

        return $this;
        // cf #70
        // return parent::addPRBadge($prBadge);
    }

    /**
     * @param PRBadge $badge
     */
    public function removeBadge(PRBadge $badge)
    {
        PUBadgeQuery::create()
            ->filterByPUserId($this->getId())
            ->filterByPRBadgeId($badge->getId())
            ->delete();

        return $this;
        // cf #70
        // return parent::removePRBadge($prBadge);
    }

    /**
     * User's badges
     *
     * @param integer $badgeTypeId
     * @param integer $metalTypeId
     * @param boolean $online
     * @return PropelCollection[PRBadge]
     */
    public function getBadges($badgeTypeId = null, $metalTypeId = null, $online = true)
    {
        $query = PRBadgeQuery::create()
            ->filterIfTypeId($badgeTypeId)
            ->filterIfMetalTypeId($metalTypeId)
            ->filterIfOnline($online)
            ->orderByTitle();

        return parent::getPRBadges($query);
    }

    /**
     * User's nb badges
     *
     * @param integer $badgeTypeId
     * @param integer $metalTypeId
     * @param boolean $online
     * @return int
     */
    public function countBadges($badgeTypeId = null, $metalTypeId = null, $online = true)
    {
        $query = PRBadgeQuery::create()
            ->filterIfTypeId($badgeTypeId)
            ->filterIfMetalTypeId($metalTypeId)
            ->filterIfOnline($online)
            ->orderByTitle();

        return parent::countPRBadges($query);
    }

    /**
     * Update reputation
     *
     * @param int $evolution
     * @return PUser
     */
    public function updateReputation($evolution = 0)
    {
        $con = \Propel::getConnection('default');
        if ($evolution > 0) {
            $con->beginTransaction();
            try {
                for ($i = 0; $i < $evolution; $i++) {
                    $puReputation = new PUReputation();
                    $puReputation->setPRActionId(ReputationConstants::ACTION_ID_R_ADMIN_POS);
                    $puReputation->setPUserId($this->getId());
                    $puReputation->save();
                }

                $con->commit();
            } catch (\Exception $e) {
                $con->rollback();
                throw new InconsistentDataException(sprintf('Rollback reputation evolution user id-%s.', $this->getId()));
            }
        } elseif ($evolution < 0) {
            $con->beginTransaction();
            try {
                for ($i = 0; $i > $evolution; $i--) {
                    $puReputation = new PUReputation();
                    $puReputation->setPRActionId(ReputationConstants::ACTION_ID_R_ADMIN_NEG);
                    $puReputation->setPUserId($this->getId());
                    $puReputation->save();
                }

                $con->commit();
            } catch (\Exception $e) {
                $con->rollback();
                throw new InconsistentDataException(sprintf('Rollback reputation evolution user id-%s.', $this->getId()));
            }
        }
    }

    /**
     * Sum user's "score_evolution" in PUReputation
     *
     * @param \DateTime $untilAt
     * @param \DateTime $fromAt
     * @param int $untilId
     * @return integer
     */
    public function getReputationScore($untilAt = null, $fromAt = null, $untilId = null)
    {
        $sql = "
    SELECT SUM(score_evolution) as score
    FROM p_u_reputation
    LEFT JOIN p_r_action ON p_u_reputation.p_r_action_id=p_r_action.id
    WHERE p_u_reputation.p_user_id = ?
    AND p_r_action.online = 1
    ";

        if ($untilAt) {
            $sql .= sprintf("AND p_u_reputation.created_at < '%s'", $untilAt->format('Y-m-d H:i:s'));
        }
        if ($fromAt) {
            $sql .= sprintf("AND p_u_reputation.created_at >= '%s'", $fromAt->format('Y-m-d H:i:s'));
        }
        if ($untilId) {
            $sql .= sprintf("AND p_u_reputation.id < %s", $untilId);
        }

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $this->getId(), \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $score = $result[0]['score'];
        if ($score === null) {
            $score = 0;
        }

        return $score;
    }

    /**
     * Count user's reputation evolution
     *
     * @param int $offset
     * @return int
     */
    public function countReputationEvolution($offset = 0)
    {
        $evolutions = PUReputationQuery::create()
            ->usePRActionQuery('action', 'left join')
            ->endUse()
            ->filterByPUserId($this->getId())
            ->orderByCreatedAt('desc')
            ->limit(ListingConstants::REPUTATION_CHARTS_PAGINATION)
            ->offset($offset)
            ->count();

        return $evolutions;
    }

    // ************************************************************************************ //
    //                                          LOCALISATION
    // ************************************************************************************ //

    /**
     * Return user's PLCity
     *
     * @param PLCity
     */
    public function getCity()
    {
        return $this->getPLCity();
    }

    /**
     * Return user's PLDepartment
     *
     * @param PLDepartment
     */
    public function getDepartment()
    {
        if (!$this->getPLCity()) {
            return null;
        }

        return $this->getPLCity()->getPLDepartment();
    }

    /**
     * Return user's PLRegion
     *
     * @param PLRegion
     */
    public function getRegion()
    {
        if (!$this->getPLCity()) {
            return null;
        }

        if (!$this->getPLCity()->getPLDepartment()) {
            return null;
        }

        return $this->getPLCity()->getPLDepartment()->getPLRegion();
    }

    /**
     * Return user's city name
     *
     * @param string
     */
    public function getCityStr()
    {
        if (!$this->getPLCity()) {
            return null;
        }

        return $this->getPLCity()->getName();
    }

    /**
     * Return user's department name
     *
     * @param string
     */
    public function getDepartmentStr()
    {
        if (!$this->getPLCity()) {
            return null;
        }

        return $this->getPLCity()->getPLDepartment()->getTitle();
    }

    // ************************************************************************************ //
    //                                          OPERATION
    // ************************************************************************************ //

    /**
     * Get user associated operations 
     *
     * @param boolean $online
     * @return boolean
     */
    public function getOperations($online = true)
    {
        $query = PEOperationQuery::create()
            ->filterIfOnline($online);

        return parent::getPEOperations($query);
    }

    /**
     * Is user associated with an operation 
     *
     * @param boolean $online
     * @return boolean
     */
    public function isWithOperation($online = true)
    {
        $query = PEOperationQuery::create()
            ->filterIfOnline($online);

        $nbResults = parent::countPEOperations($query);
        if ($nbResults > 0) {
            return true;
        }

        return false;
    }
}
