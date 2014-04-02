<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUser;

use \PDO;
use \Propel;
use \PropelPDO;
use \Criteria;
use Politizr\Model\PUser;
use Politizr\Model\PUserPeer;
use Politizr\Model\PUserQuery;

class PUser extends BasePUser
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
	const TYPE_CITOYEN = 1;
	const TYPE_QUALIFIE = 2;

	const STATUS_ACTIV = 1;
	const STATUS_ARCHIVE = 2;

  	const UPLOAD_PATH = '/../../../web/uploads/users/';
  	const UPLOAD_WEB_PATH = '/uploads/users/';
	// ************************************************************************************ //

	/**
	 *
	 */
	public function __toString() {
		return $this->getName();
	}

 	/**
	 * Override to manage accented characters
	 * @return string
	 */
	protected function createRawSlug()
	{
		$toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($this->getFirstname().'-'.$this->getName());
		$slug = $this->cleanupSlugPart($toSlug);
		return $slug;
	}



	// ************************************************************************************ //
	//										METHODES ADMIN GENERATOR
	// ************************************************************************************ //



	// ******************* SIMPLE UPLOAD MANAGEMENT **************** //
	// https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md

	// Colonnes virtuelles / fichiers
	public $uploaded_file_name;

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
    public function getAbsolutePath() {
    	return __DIR__ . PUser::UPLOAD_PATH . $this->getFileName();
    }
    
	/**
	 * 
	 */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                $this->getAbsolutePath()
            );
        }

        return null;
    }

    /**
     *	Gestion physique de l'upload
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
			$this->removeUpload(true);
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
	public function removeUpload($uploaded_file_name = true)
	{
		if ($uploaded_file_name && $this->file_name && file_exists(__DIR__ . PUser::UPLOAD_PATH . $this->file_name)) {
		  	unlink(__DIR__ . PUser::UPLOAD_PATH . $this->file_name);
		}
	}


	/******************************************************************************/

	/**
	 * Renvoie les followers
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowers(Criteria $criteria = null, PropelPDO $con = null) {
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

        $pUsers = PUserQuery::create(null, $criteria)
            ->addUsingAlias(PUserPeer::ID, $listPKs, Criteria::IN)
            ->find($con);        

		return $pUsers;
	}

	/**
	 *
	 */
	public function getPUserFollowersQ() {
		$criteria = PUserQuery::create()->filterByType(PUser::TYPE_QUALIFIE);
		$pUsers = $this->getPUserFollowers($criteria);

		return $pUsers;
	}

	/**
	 * Renvoie les followers citoyens
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowersC() {
		$criteria = PUserQuery::create()->filterByType(PUser::TYPE_CITOYEN);
		$pUsers = $this->getPUserFollowers($criteria);

		return $pUsers;
	}

	/**
	 * Renvoie les subscribers qualifiés
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserSubscribers(Criteria $criteria = null, PropelPDO $con = null) {
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

        $pUsers = PUserQuery::create(null, $criteria)
            ->addUsingAlias(PUserPeer::ID, $listPKs, Criteria::IN)
            ->find($con);        

		return $pUsers;
	}


	/**
	 * Renvoie les subscribers qualifiés
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserSubscribersQ() {
		$criteria = PUserQuery::create()->filterByType(PUser::TYPE_QUALIFIE);
		$pUsers = $this->getPUserSubscribers($criteria);

		return $pUsers;
	}

	/**
	 * Renvoie les abonnements citoyens
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserSubscribersC() {
		$criteria = PUserQuery::create()->filterByType(PUser::TYPE_CITOYEN);
		$pUsers = $this->getPUserSubscribers($criteria);

		return $pUsers;
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
}
