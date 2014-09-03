<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUser;

use \PDO;
use \Propel;
use \PropelPDO;
use \Criteria;

use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PUStatus;
use Politizr\Model\PUserPeer;
use Politizr\Model\PRBadge;

use Politizr\Model\PUserQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUFollowDDQuery;

use Politizr\Model\PUQualificationQuery;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;


class PUser extends BasePUser implements UserInterface
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
  	const UPLOAD_PATH = '/../../../web/uploads/users/';
  	const UPLOAD_WEB_PATH = '/uploads/users/';



	// *****************************  OBJET / STRING  ****************** //

	/**
	 *
	 */
	public function __toString() {
		return $this->getFirstname().' '.$this->getName();
	}

    /**
     *
     */
    public function getBirthdayText() {
        return $this->getBirthday('d/m/Y');
    }



 	/**
	 * Override to manage accented characters
     *
	 * @return string
	 */
	protected function createRawSlug()
	{
        $slug = parent::createRawSlug();
        
        if ($firstname = $this->getFirstname() && $name = $this->getName()) {
    		$toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($firstname.'-'.$name);
    		$slug = $this->cleanupSlugPart($toSlug);
        } elseif($realname = $this->getRealname()) {
            $toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($realname);
            $slug = $this->cleanupSlugPart($toSlug);
        }

		return $slug;
	}

    /*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/

    public function getBlockDebates() {
    }
    public function getBlockReactions() {
    }
    public function getBlockCommentsD() {
    }
    public function getBlockCommentsR() {
    }
    public function getBlockFollowersQ() {
    }
    public function getBlockFollowersC() {
    }
    public function getBlockSubscribersQ() {
    }
    public function getBlockSubscribersC() {
    }
    public function getBlockTagsMandat() {
    }
    public function getBlockTagsGeo() {
    }
    public function getBlockBadgesDebat() {
    }
    public function getBlockBadgesComment() {
    }
    public function getBlockBadgesParticipation() {
    }
    public function getBlockBadgesModeration() {
    }
    public function getBlockBadgesAutres() {
    }


	// ************************************************************************************ //
	//										METHODES ADMIN GENERATOR
	// ************************************************************************************ //



	// ******************* SIMPLE UPLOAD MANAGEMENT **************** //
	// https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md

	// Colonnes virtuelles / fichiers
    public $uploadedFileName;
    public function setUploadedFileName($uploadedFileName) {
        $this->uploadedFileName = $uploadedFileName;
    }

    /**
     *
     */
    public function getUploadedFileNameWebPath()
    {
        return PUser::UPLOAD_WEB_PATH . $this->file_name;
    }
    
	/**
	 * 
	 */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . PUser::UPLOAD_PATH . $this->file_name
            );
        }

        return null;
    }

    /**
     *  Gestion physique de l'upload
     */
	public function upload($file = null)
	{
		if (null === $file) {
		  	return;
		}

		// Extension et nom de fichier
		$extension = $file->guessExtension();
		if (!$extension) {
			  $extension = 'bin';
		}
		$fileName = 'p-u-' . \StudioEcho\Lib\StudioEchoUtils::randomString() . '.' . $extension;

		// move takes the target directory and then the target filename to move to
		$fileUploaded = $file->move(__DIR__ . PUser::UPLOAD_PATH, $fileName);

		// file_name
		return $fileName;
	}    

	/**
	 *	Surcharge pour gérer la suppression physique.
	 */
	public function setFileName($v)
	{
		if (!$v) {
			$this->removeUpload();
		}
		parent::setFileName($v);
	}

	/**
	 *  Surcharge pour gérer la suppression physique.
	 */
	public function postDelete(\PropelPDO $con = null)
	{
		$this->removeUpload();
	}

	/**
	 * 	Suppression physique des fichiers.
	 */
	public function removeUpload($uploadedFileName = true)
	{
		if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PUser::UPLOAD_PATH . $this->file_name)) {
		  	unlink(__DIR__ . PUser::UPLOAD_PATH . $this->file_name);
		}
	}


    // ************************************************************************************ //
    //                      METHODES SECURITE / INSCRIPTION / LOGIN
    // ************************************************************************************ //


    /**
     * Plain password. Used when changing the password.
     *
     * @var string
     */
    protected $plainPassword;


    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->id,
                $this->username,
                $this->name,
                $this->firstname,
                $this->birthday,
                $this->email,
                $this->profile_completed,
                $this->salt,
                $this->password,
                $this->expired,
                $this->locked,
                $this->credentials_expired,
                $this->enabled,
                $this->_new,
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 1, null));

        list(
                $this->id,
                $this->username,
                $this->name,
                $this->firstname,
                $this->birthday,
                $this->email,
                $this->profile_completed,
                $this->salt,
                $this->password,
                $this->expired,
                $this->locked,
                $this->credentials_expired,
                $this->enabled,
                $this->_new,
        ) = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritDoc}
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }


    /**
     * @param Array
     */
    public function setOAuthData($oAuthData)
    {
        if (isset($oAuthData['provider'])) {
            $this->setProvider($oAuthData['provider']);
        }
        if (isset($oAuthData['providerId'])) {
            $this->setProviderId($oAuthData['providerId']);
        }
        if (isset($oAuthData['nickname'])) {
            $this->setNickname($oAuthData['nickname']);
        }
        if (isset($oAuthData['realname'])) {
            $this->setRealname($oAuthData['realname']);
        }
        if (isset($oAuthData['email'])) {
            $this->setEmail($oAuthData['email']);
        }
        if (isset($oAuthData['accessToken'])) {
            $this->setConfirmationToken($oAuthData['accessToken']);
        }
    }


    // ************************************************************************************ //
    //                      VALIDATION
    // ************************************************************************************ //


    /**
     *  Email est un identifiant unique
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new UniqueObject(array(
            'fields'  => 'email',
            'message' => 'Cette adresse email existe déja.',
        )));

        $metadata->addConstraint(new UniqueObject(array(
            'fields'  => 'username',
            'message' => 'Cet identifiant est déjà pris.',
        )));
    }    


    // ************************************************************************************ //
    //                      METHODES PUBLIQUES
    // ************************************************************************************ //



    // *****************************    FOLLOWERS / SUBSCRIBERS    ************************* //


	/**
	 * Renvoie les followers
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowers(Criteria $query = null, PropelPDO $con = null) {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
        SELECT p_u_follow_u.p_user_follower_id
        FROM p_u_follow_u
        WHERE p_u_follow_u.p_user_id = ?
    )";

        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $this->getPrimaryKey(), PDO::PARAM_INT);
        $stmt->bindValue(2, $this->getPrimaryKey(), PDO::PARAM_INT);
        $stmt->execute();

        $listPKs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $pUsers = PUserQuery::create(null, $query)
            ->addUsingAlias(PUserPeer::ID, $listPKs, Criteria::IN)
            ->find($con);        

		return $pUsers;
	}

	/**
     * Renvoie les followers qualifiés (élus)
     *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowersQ() {
		$query = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_QUALIFIE);
		$pUsers = $this->getPUserFollowers($query);

		return $pUsers;
	}

    /**
     * Nombre de followers qualifiés (élus)
     *
     * @return     integer
     */
    public function countPUserFollowersQ() {
        $pUsers = $this->getPUserFollowersQ();

        return count($pUsers);
    }

	/**
	 * Renvoie les followers citoyens
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowersC() {
		$query = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_CITOYEN);
		$pUsers = $this->getPUserFollowers($query);

		return $pUsers;
	}

    /**
     * Nombre de followers citoyens
     *
     * @return     integer
     */
    public function countPUserFollowersC() {
        $pUsers = $this->getPUserFollowersC();

        return count($pUsers);
    }



	/**
	 * Renvoie les abonnements
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserSubscribers(Criteria $query = null, PropelPDO $con = null) {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
        $stmt->bindValue(1, $this->getPrimaryKey(), PDO::PARAM_INT);
        $stmt->bindValue(2, $this->getPrimaryKey(), PDO::PARAM_INT);
        $stmt->execute();

        $listPKs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $pUsers = PUserQuery::create(null, $query)
            ->addUsingAlias(PUserPeer::ID, $listPKs, Criteria::IN)
            ->find($con);        

		return $pUsers;
	}


	/**
	 * Renvoie les abonnements qualifiés (élus)
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserSubscribersQ() {
		$query = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_QUALIFIE);
		$pUsers = $this->getPUserSubscribers($query);

		return $pUsers;
	}

    /**
     * Nombre d'abonnements qualifiés (élus)
     *
     * @return     integer
     */
    public function countPUserSubscribersQ() {
        $pUsers = $this->getPUserSubscribersQ();

        return count($pUsers);
    }


	/**
	 * Renvoie les abonnements citoyens
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserSubscribersC() {
		$query = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_CITOYEN);
		$pUsers = $this->getPUserSubscribers($query);

		return $pUsers;
	}

    /**
     * Nombre d'abonnements citoyens
     *
     * @return     integer
     */
    public function countPUserSubscribersC() {
        $pUsers = $this->getPUserSubscribersC();

        return count($pUsers);
    }



    // *****************************    QUALIFICATION    ************************* //

    /**
     *  Renvoie les qualifications par ordre décroissant
     *
     * @return PUQualification
     */
    public function getQualifications() {
        $query = PUQualificationQuery::create()
                    ->orderByBeginAt(\Criteria::DESC);

        return parent::getPUQualifications($query);
    }

    /**
     *  Renvoie la qualification courante
     *
     * @return PUQualification
     */
    public function getCurrentQualification() {
        $puQualification = PUQualificationQuery::create()
            ->filterByPUserId($this->getId())
            ->filterByEndAt(array('min' => time()))
                ->_or()
            ->filterByEndAt(null)
            ->findOne();

        return $puQualification;
    }

    // *****************************    TAGS   ************************* //

    /**
     * Renvoie les tags taggant l'utilisateur
     *
     *
     * @return PTag (collection)
     */
    public function getTaggedPTags($ptTagTypeId = null, $online = true) {
        $query = PTagQuery::create()
                    ->_if($ptTagTypeId)
                        ->filterByPTTagTypeId($ptTagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ;

        return parent::getPuTaggedTPTags($query);
    }

    // *****************************    DOCUMENTS > DEBATS, REACTIONS    ************************* //

    /**
     * Renvoie les débats actifs associés à l'utilisateur
     *
     * @return PDDebate (collection)
     */
    public function getDebates() {
        $query = PDDebateQuery::create()
                    ->online()
                    ->orderByCreatedAt(\Criteria::DESC);

        return parent::getPDDebates($query);
    }

    /**
     * Renvoie les réactions actives associées à l'utilisateur
     *
     * @return PDDebate (collection)
     */
    public function getReactions() {
        $query = PDReactionQuery::create()
                    ->online()
                    ->orderByCreatedAt(\Criteria::DESC);

        return parent::getPDReactions($query);
    }


    /**
     *  Renvoie la liste des réactions associées aux débats suivis par l'utilisateur
     *  triée de la plus récente à la plus ancienne.
     *
     * @return PDReaction (collection)
     */
    public function getReactionsFollowedDebates() {
        // Récupère la liste des IDs des débats suivis
        $followedIds = PUFollowDDQuery::create()
                            ->select('PDDebateId')
                            ->filterByPUserId($this->getId())
                            ->find();

        // Récupère les réactions
        $pDReactions = PDReactionQuery::create()
            ->filterByPDDebateId($followedIds->getData())
            ->online()
            ->orderByPublishedAt(\Criteria::DESC)
            ->find();

        return $pDReactions;
    }

    /**
     * Renvoie un ensemble de débats correspondant aux tags du type spécifié en argument
     *
     * TODO: algo à affiner > idée de ne conserver que les plus pertinents > dépend du nombre de tags et du nombre de résultats
     *
     * @param $typeId integer type de tag PTTagType id
     * @param $notFollowed boolean  renvoie uniquement les débats non suivis
     *
     * @return PDDebate (collection)
     */
    public function getDebatesTag($typeId, $notFollowed = true) {
        // Récupère la liste des IDs des tags suivis
        $followedIds = PTagQuery::create()
                            ->select('Id')
                            ->filterByPTTagTypeId($typeId)
                            ->usePuFollowTPTagQuery()
                                ->filterByPUserId($this->getId())
                            ->endUse()
                            ->setDistinct()
                            ->find();

        // Récupère les débats
        $pDDebates = PDDebateQuery::create()
                        ->usePddTaggedTPDDebateQuery()
                            ->filterByPTagId($followedIds->getData())
                        ->endUse()
                        ->online()
                        ->popularity()
                        ->_if($notFollowed)
                            ->where('p_d_debate.id NOT IN (SELECT p_d_debate_id FROM p_u_follow_d_d WHERE p_user_id = ?)', $this->getId())
                        ->_endif()
                        ->setDistinct()
                        ->find();

        return $pDDebates;
    }

    // *****************************    DOCUMENTS > COMMENTAIRES    ************************* //

    /**
     * Renvoit les commentaires associés à des débats pour le user courant.
     *
     * @param   $online     boolean     Renvoit uniquement les commentaires en ligne
     *
     * @return array    Liste d'objets PDDComment
     */
    public function getCommentsD($online = true) {
        $query = PDDCommentQuery::create()
                    ->filterByOnline($online);

        return parent::getPDDComments($query);
    }


    /**
     * Renvoit les commentaires associés à des réactions pour le user courant.
     *
     * @param   $online     boolean     Renvoit uniquement les commentaires en ligne
     *
     * @return array    Liste d'objets PDRComment
     */
    public function getCommentsR($online = true) {
        $query = PDRCommentQuery::create()
                    ->filterByOnline($online);

        return parent::getPDRComments($query);
    }



    // *****************************    BADGES / REPUTATION    ************************* //

    /**
     *  Renvoie les badges
     *
     * @param $pRBadgeTypeId    integer     ID type de badge
     *
     * @return PRBadge (collection)
     */
    public function getPRBadges($prBadgeTypeId = null, $online = true) {
        $query = PRBadgeQuery::create()
            ->filterByOnline($online)
            ->_if($prBadgeTypeId)
                ->filterByPRBadgeTypeId($prBadgeTypeId)
            ->_endif();

        return parent::getPuReputationRbPRBadges($query);
    }

    /**
     * @see addPuReputationRbPRBadge
     */
    public function addPRBadge(PRBadge $prBadge) {
        return parent::addPuReputationRbPRBadge($prBadge);
    }

    /**
     * @see removePuReputationRbPRBadge
     */
    public function removePRBadge(PRBadge $prBadge) {
        return parent::removePuReputationRbPRBadge($prBadge);
    }

    /**
     *  Renvoie le "score" de réputation: somme des "score_evolution" associé à toutes les actions effectuées
     *  par l'utilisateur courant.
     *
     * @return integer
     */
    public function getReputationScore(PropelPDO $con = null) {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $sql = "
    SELECT SUM(score_evolution) as score
    FROM p_u_reputation_r_a
    LEFT JOIN p_r_action ON p_u_reputation_r_a.p_r_action_id=p_r_action.id
    WHERE p_u_reputation_r_a.p_user_id = ?
    ";

        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $this->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['score'];
    }


}