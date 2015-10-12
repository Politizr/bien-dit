<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUser;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

use FOS\ElasticaBundle\Transformer\HighlightableModelInterface;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\QualificationConstants;
use Politizr\Constant\UserConstants;
use Politizr\Constant\ListingConstants;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

/**
 * User object model
 *
 * @author Lionel Bouzonville
 */
class PUser extends BasePUser implements UserInterface, ContainerAwareInterface, HighlightableModelInterface
{
    // simple upload management
    public $uploadedFileName;

    // elastica search
    private $elasticaPersister;
    private $highlights;

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
     * Check if profile is qualified and has been validated
     *
     * @return boolean
     */
    public function isQualified()
    {
        if ($this->getQualified() && $this->getValidated()) {
            return true;
        }

        return false;
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
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if ($container) {
            $this->elasticaPersister = $container->get('fos_elastica.object_persister.politizr.p_user');
        }
    }

    /**
     *
      */
    public function getHighlights()
    {
        return $this->highlights;
    }

    /**
     * Set ElasticSearch highlight data.
     *
     * @param array $highlights array of highlight strings
     */
    public function setElasticHighlights(array $highlights)
    {
        $this->highlights = $highlights;
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postInsert(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                // $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postUpdate(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                // $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postDelete(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            // $this->elasticaPersister->deleteOne($this);
        } else {
            throw new \Exception('Indexation service not found');
        }

        // @todo refactor to command
        $this->removeUpload();
    }

    /**
     * Indexation process call to know if object is indexable
     *
     * @return boolean
     */
    public function isIndexable()
    {
        $statusId = $this->getPUStatusId();
        if ($statusId == UserConstants::STATUS_ACTIVED or $statusId == UserConstants::STATUS_VALIDATION_PROCESS) {
            $status = true;
        } else {
            $status = false;
        }

        return  $this->getOnline()
                && $status
                ;
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

    /* ######################################################################################################## */
    /*                                      SIMPLE UPLOAD MANAGEMENT                                            */
    /* ######################################################################################################## */

    /**
     *
     * @param string $uploadedFileName
     */
    public function setUploadedFileName($uploadedFileName)
    {
        $this->uploadedFileName = $uploadedFileName;
    }

    /**
     *
     * @return string
     */
    public function getUploadedFileNameWebPath()
    {
        return PathConstants::USER_UPLOAD_WEB_PATH . $this->file_name;
    }
    
    /**
     *
     * @return File
     */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new File(
                __DIR__ . PathConstants::USER_UPLOAD_PATH . $this->file_name
            );
        }

        return null;
    }

    /**
     *
     * @param File $file
     * @return string file name
     */
    public function upload($file = null)
    {
        if (null === $file) {
              return;
        }

        // extension
        $extension = $file->guessExtension();
        if (!$extension) {
              $extension = 'bin';
        }

        // file name
        $fileName = $this->computeFileName() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PathConstants::USER_UPLOAD_PATH, $fileName);

        // file name
        return $fileName;
    }

    /**
     * @todo migrate physical deletion in special command instead of save
     */
    public function setFileName($fileName)
    {
        if (null !== $fileName) {
            $this->removeUpload();
        }
        parent::setFileName($fileName);
    }

    /**
     *
     * @param $uploadedFileName
     */
    public function removeUpload($uploadedFileName = true)
    {
        if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PathConstants::USER_UPLOAD_PATH . $this->file_name)) {
            unlink(__DIR__ . PathConstants::USER_UPLOAD_PATH . $this->file_name);
        }
    }

    /**
     * Compute a user file name
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
        // @todo /!\ inscription citoyen step 1 > si un enregistrement en bdd sans email existe, déclenche l'erreur "email existe deja"

        // @todo label constant
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
     * User's debate's notification of user's followers
     *
     * @return PropelObjectCollection[PUser]
     */
    public function getNotifDebateFollowers()
    {
        return $this->getFollowers(null, 'AND p_u_follow_u.notif_debate = true');
    }

    /**
     * User's reaction's notification of user's followers
     *
     * @return PropelObjectCollection[PUser]
     */
    public function getNotifReactionFollowers()
    {
        return $this->getFollowers(null, 'AND p_u_follow_u.notif_reaction= true');
    }

    /**
     * User's comment's notification of user's followers
     *
     * @return PropelObjectCollection[PUser]
     */
    public function getNotifCommentFollowers()
    {
        return $this->getFollowers(null, 'AND p_u_follow_u.notif_comment = true');
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
    public function countPUserSubscribersQ()
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
    public function countPUserSubscribersC()
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
    public function getMandates()
    {
        $query = PUMandateQuery::create()
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
     * User's tagged tags
     *
     * @param integer $tagTypeId
     * @param boolean $online
     * @return PropelCollection[PTag]
     */
    public function getTaggedTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            ->orderByTitle()
            ->setDistinct();

        return parent::getPuTaggedTPTags($query);
    }

    /**
     * User's following tags
     *
     * @param integer $tagTypeId
     * @param boolean $online
     * @return PropelCollection[PTag]
     */
    public function getFollowTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            ->orderByTitle()
            ->setDistinct();

        return parent::getPuFollowTPTags($query);
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
     * @param boolean $published
     * @return PropelCollection[PDDebate]
     */
    public function getDComments($online = true)
    {
        $comments = PDRCommentQuery::create()
            ->filterIfOnline($online)
            ->orderByCreatedAt('desc')
            ->find();

        return $comments;
    }

    /**
     * User's reactions' comments
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDDebate]
     */
    public function getRComments($online = true)
    {
        $comments = PDRCommentQuery::create()
            ->filterIfOnline($online)
            ->orderByCreatedAt('desc')
            ->find();

        return $comments;
    }


    // ************************************************************************************ //
    //                                          REPUTATION
    // ************************************************************************************ //

    /**
     * @see addPuReputationRbPRBadge
     */
    public function addBadge(PRBadge $prBadge)
    {
        return parent::addPRBadge($prBadge);
    }

    /**
     * @see removePuReputationRbPRBadge
     */
    public function removeBadge(PRBadge $prBadge)
    {
        return parent::removePRBadge($prBadge);
    }

    /**
     * User's badges
     *
     * @param integer $badgeTypeId
     * @param boolean $online
     * @return PropelCollection[PRBadge]
     */
    public function getBadges($badgeTypeId = null, $online = true)
    {
        $query = PRBadgeQuery::create()
            ->filterIfOnline($online)
            ->filterIfTypeId($badgeTypeId)
            ->orderByTitle();

        return parent::getPRBadges($query);
    }

    /**
     * Sum user's "score_evolution" in PUReputation
     *
     * @param int $untilId
     * @param \DateTime $untilAt
     * @param \DateTime $fromAt
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
        if ($score == null) {
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
    //                                          NOTIFICATIONS
    // ************************************************************************************ //

    /**
     * Check if follower $userId wants to be notified of user update in the scope of $context
     *
     * @param integer $userId
     * @param string $context   @todo refactor migrate constant
     * @return boolean
     */
    public function isNotified($userId, $context = 'reaction')
    {
        $puFollowU = PUFollowUQuery::create()
            ->filterByPUserId($userId)
            ->filterByPUserFollowerId($this->getId())
            ->findOne();

        if ($context == 'debate' && $puFollowU && $puFollowU->getNotifDebate()) {
            return true;
        } elseif ($context == 'reaction' && $puFollowU && $puFollowU->getNotifReaction()) {
            return true;
        } elseif ($context == 'comment' && $puFollowU && $puFollowU->getNotifComment()) {
            return true;
        }

        return false;
    }
}
