<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUser;

use \PDO;
use \Propel;
use \PropelPDO;
use \Criteria;

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

    /**
     *
     */
    public function getClassName() {
        return PDocument::TYPE_USER;
    }



    // *****************************  OBJET / STRING  ****************** //

    /**
     *
     */
    public function __toString() {
      return $this->getFullName();
    }

    /**
     *
     */
    public function getFullName() {
      return trim($this->getFirstname().' '.$this->getName());
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
    public function createRawSlug()
    {


        if ($this->getFirstname() && $this->getName()) {
            $toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($this->getFirstname() . '-' . $this->getName());

        	$slug = $this->cleanupSlugPart($toSlug);
        } elseif($realname = $this->getRealname()) {
            $toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($realname);

            $slug = $this->cleanupSlugPart($toSlug);
        } else {
            $slug = parent::createRawSlug();
        }

        return $slug;
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
        // TODO /!\ inscription citoyen step 1 > si un enregistrement en bdd sans email existe, déclenche l'erreur "email existe deja"

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
		$query = PUserQuery::create()->filterByQualified(true);
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
		$query = PUserQuery::create()->filterByQualified(false);
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
		$query = PUserQuery::create()->filterByQualified(true);
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
		$query = PUserQuery::create()->filterByQualified(false);
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
     *  Renvoie les mandats par ordre décroissant
     *
     * @return array PUMandate
     */
    public function getMandates() {
        $query = PUMandateQuery::create()
                    ->orderByBeginAt(\Criteria::DESC);

        return parent::getPUMandates($query);
    }

    /**
     *  Renvoie les mandats courants
     *
     * @return array    PQMandate
     */
    public function getCurrentMandates() {
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

    // *****************************    AFFINITÉS POLITIQUES    ************************* //

    /**
     *  Renvoie les organisation courantes
     *
     * @return array    PQOrganization
     */
    public function getCurrentOrganizations($online = true) {
        $query = PQOrganizationQuery::create()
                    ->filterByOnline($online)
                    ->setDistinct();

        return parent::getPUCurrentQOPQOrganizations($query);
    }


    // *****************************    TAGS   ************************* //

    /**
     * Renvoie les tags taggant l'utilisateur
     *
     *
     * @return PTag (collection)
     */
    public function getTaggedTags($ptTagTypeId = null, $online = true) {
        $query = PTagQuery::create()
                    ->_if($ptTagTypeId)
                        ->filterByPTTagTypeId($ptTagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ->setDistinct()
                    ;

        return parent::getPuTaggedTPTags($query);
    }

    /**
     * Renvoie les tags suivis par l'utilisateur
     *
     *
     * @return PTag (collection)
     */
    public function getFollowTags($ptTagTypeId = null, $online = true) {
        $query = PTagQuery::create()
                    ->_if($ptTagTypeId)
                        ->filterByPTTagTypeId($ptTagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ->setDistinct()
                    ;

        return parent::getPuFollowTPTags($query);
    }

    // *****************************    DOCUMENTS > DEBATS, REACTIONS    ************************* //

    /**
     * Renvoie les documents associés à l'utilisateur
     *
     * @return PDDebate (collection)
     */
    public function getDocuments($online = true, $published = true) {
        $query = PDocumentQuery::create()
                    ->filterByPUserId($this->getId())
                    ->filterByOnline($online)
                    ->filterByPublished($published)
                    ->orderByCreatedAt(\Criteria::DESC);

        return $query->find();
    }

    /**
     * Renvoie les débats associés à l'utilisateur
     *
     * @return PDDebate (collection)
     */
    public function getDebates($online = true, $published = true) {
        $query = PDDebateQuery::create()
                    ->filterByPUserId($this->getId())
                    ->filterByOnline($online)
                    ->filterByPublished($published)
                    ->orderByCreatedAt(\Criteria::DESC);

        return $query->find();
    }

    /**
     * Renvoie le nombre de débats associés à l'utilisateur
     *
     * @return     integer
     */
    public function countDebates($online = true, $published = true) {
        $debates = $this->getDebates($online, $published);

        return count($debates);
    }


    /**
     * Renvoie les réactions associé à l'utilisateur
     *
     * @return PDDebate (collection)
     */
    public function getReactions($online = true, $published = true) {
        $query = PDReactionQuery::create()
                    ->filterByPUserId($this->getId())
                    ->filterByOnline($online)
                    ->filterByPublished($published)
                    ->orderByCreatedAt(\Criteria::DESC);

        return $query->find();
    }

    /**
     * Renvoie le nombre de réactions associé à l'utilisateur
     *
     * @return     integer
     */
    public function countReactions($online = true, $published = true) {
        $reactions = $this->getReactions($online, $published);

        return count($reactions);
    }



    // *****************************    DOCUMENTS > COMMENTAIRES    ************************* //

    /**
     * Renvoit les commentaires associés à des documents (débats + réactions) pour le user courant.
     *
     * @param   $online     boolean     Renvoit uniquement les commentaires en ligne
     *
     * @return array    Liste d'objets PDDComment
     */
    public function getComments($online = true) {
        $query = PDCommentQuery::create()
                    ->filterByOnline($online);

        return parent::getPDComments($query);
    }

    /**
     * Renvoie le nombre de comentaires associé à l'utilisateur
     *
     * @return     integer
     */
    public function countComments($online = true) {
        $comments = $this->getComments($online);

        return count($comments);
    }



    // *****************************    BADGES / REPUTATION    ************************* //

    /**
     *  Renvoie les badges
     *
     * @param $pRBadgeTypeId    integer     ID type de badge
     *
     * @return PRBadge (collection)
     */
    public function getBadges($prBadgeTypeId = null, $online = true) {
        $query = PRBadgeQuery::create()
            ->filterByOnline($online)
            ->_if($prBadgeTypeId)
                ->filterByPRBadgeTypeId($prBadgeTypeId)
            ->_endif()
            ->orderByPRBadgeTypeId()
            ->orderByTitle()
            ;

        return parent::getPRBadges($query);
    }

    /**
     * @see addPuReputationRbPRBadge
     */
    public function addBadge(PRBadge $prBadge) {
        return parent::addPRBadge($prBadge);
    }

    /**
     * @see removePuReputationRbPRBadge
     */
    public function removeBadge(PRBadge $prBadge) {
        return parent::removePRBadge($prBadge);
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
    FROM p_u_reputation
    LEFT JOIN p_r_action ON p_u_reputation.p_r_action_id=p_r_action.id
    WHERE p_u_reputation.p_user_id = ?
    ";

        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $this->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $score = $result[0]['score'];
        if ($score == null) {
            $score = 0;
        }

        return $score;
    }


    // *****************************    SUGGESTIONS    ************************* //

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
    public function getTaggedDebates($typeId = null, $notFollowed = true) {
        // Récupère la liste des IDs des tags suivis
        $followedIds = PTagQuery::create()
                        ->select('Id')
                        ->_if($typeId)
                            ->filterByPTTagTypeId($typeId)
                        ->_endif()
                        ->usePuFollowTPTagQuery()
                            ->filterByPUserId($this->getId())
                        ->endUse()
                        ->setDistinct()
                        ->find();

        // Récupère les débats
        $debates = PDDebateQuery::create()
                        ->usePddTaggedTQuery()
                            ->filterByPTagId($followedIds->getData())
                        ->endUse()
                        ->_if($notFollowed)
                            ->where('p_d_debate.id NOT IN (SELECT p_d_debate_id FROM p_u_follow_d_d WHERE p_user_id = ?)', $this->getId())
                        ->_endif()
                        ->online()
                        ->last()
                        ->setDistinct()
                        ->find();

        return $debates;
    }


    /**
     * Renvoie les users taggés avec (au moins) un tag suivi par le user courant
     *
     *
     * @return PUser (collection)
     */
    public function getTaggedPUsers($typeId = null, $qualified = null, $notFollowed = true) {
        $followedTagsId = PTagQuery::create()
                        ->select('Id')
                        ->_if($typeId)
                            ->filterByPTTagTypeId($typeId)
                        ->_endif()
                        ->filterByOnline(true)
                        ->filterByPuFollowTPUser($this)
                        ->find();

        $users = PUserQuery::create()
                        ->usePuTaggedTPUserQuery()
                            ->filterByPTagId($followedTagsId->getData())
                        ->endUse()
                        ->_if($notFollowed)
                            ->where('p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_id = ?)', $this->getId())
                        ->_endif()
                        ->_if($qualified)
                            ->filterByQualified($qualified)
                        ->_endif()
                        ->filterById($this->getId(), \Criteria::NOT_EQUAL)
                        ->online()
                        ->last()
                        ->setDistinct()
                        ->find();

        return $users;
    }
    


}