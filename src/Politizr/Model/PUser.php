<?php

namespace Politizr\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use FOS\ElasticaBundle\Transformer\HighlightableModelInterface;

use Politizr\Model\om\BasePUser;

use \PDO;
use \Propel;
use \PropelPDO;
use \Criteria;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

class PUser extends BasePUser implements UserInterface, ContainerAwareInterface, HighlightableModelInterface
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
      const UPLOAD_PATH = '/../../../web/uploads/users/';
      const UPLOAD_WEB_PATH = '/uploads/users/';


    // *****************************  OBJET / STRING  ****************** //

    /**
     *
     */
    public function __toString()
    {
        return $this->getFullName();
    }


    // *****************************  ELASTIC SEARCH  ****************** //
    private $elasticaPersister;
    private $highlights;

    /**
     *
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
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postInsert(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

    /**
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postUpdate(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

    /**
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postDelete(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            $this->elasticaPersister->deleteOne($this);
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }

        // + gestion de l'upload
        $this->removeUpload();
    }


    /**
     *
     */
    public function getFullName()
    {
        return trim($this->getFirstname().' '.$this->getName());
    }

    /**
     *
     */
    public function getClassName()
    {
        return PDocument::TYPE_USER;
    }

    /**
     *  Renvoit la liste des tags qualifiant le user sous forme de tableau de chaines.
     *
     *  @return string
     */
    public function getArrayTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
                    ->select('Title')
                    ->filterByOnline(true)
                    ->setDistinct()
                    ;

        $tags = parent::getPuTaggedTPTags($query)->toArray();
        return $tags;
    }

    /**
     *  Appel au moment de l'indexation pour vérifier que l'objet est indexable
     *
     *  @return boolean
     */
    public function isIndexable()
    {
        $statusId = $this->getPUStatusId();
        if ($statusId == PUStatus::ACTIVED or $statusId == PUStatus::VALIDATION_PROCESS) {
            $status = true;
        } else {
            $status = false;
        }

        return  $this->getOnline()
                && $status
                ;
    }


    // *****************************  FIN ES  ****************** //

    /**
     *
     */
    public function getBirthdayText()
    {
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
        } elseif ($realname = $this->getRealname()) {
            $toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($realname);

            $slug = $this->cleanupSlugPart($toSlug);
        } else {
            $slug = parent::createRawSlug();
        }

        return $slug;
    }

    /**
     * Utilisateur en ligne si dernière activité enregistré il y a moins de 10 minutes.
     *
     * @return boolean
     */
    public function isActiveNow()
    {
        $delay = new \DateTime();
        $delay->modify('-10 minute');

        if ($this->getLastActivity() >= $delay) {
            return true;
        }

        return false;
    }


    // ************************************************************************************ //
    //                                        METHODES ADMIN GENERATOR
    // ************************************************************************************ //



    // ******************* SIMPLE UPLOAD MANAGEMENT **************** //
    // https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md

    // Colonnes virtuelles / fichiers
    public $uploadedFileName;
    public function setUploadedFileName($uploadedFileName)
    {
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
     *    Surcharge pour gérer la suppression physique.
     */
    public function setFileName($v)
    {
        if (!$v) {
            $this->removeUpload();
        }
        parent::setFileName($v);
    }

    /**
     *     Suppression physique des fichiers.
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
     * @param       Criteria                $query
     * @param       $andWhere               string
     *
     * @return      PropelObjectCollection  PUser[]
     */
    public function getFollowers(Criteria $query = null, $andWhere = '')
    {
        $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);

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
        $stmt->bindValue(1, $this->getPrimaryKey(), PDO::PARAM_INT);
        $stmt->bindValue(2, $this->getPrimaryKey(), PDO::PARAM_INT);
        $stmt->execute();

        $listPKs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $users = PUserQuery::create(null, $query)
            ->addUsingAlias(PUserPeer::ID, $listPKs, Criteria::IN)
            ->find($con);

        return $users;
    }

    /**
     * Renvoie les followers du user abonné aux notifications "publication d'un débat"
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getNotifDebateFollowers()
    {
        return $this->getFollowers(null, 'AND p_u_follow_u.notif_debate = true');
    }

    /**
     * Renvoie les followers du user abonné aux notifications "publication d'une réaction"
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getNotifReactionFollowers()
    {
        return $this->getFollowers(null, 'AND p_u_follow_u.notif_reaction= true');
    }

    /**
     * Renvoie les followers du user abonné aux notifications "publication d'un commentaire"
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getNotifCommentFollowers()
    {
        return $this->getFollowers(null, 'AND p_u_follow_u.notif_comment = true');
    }

    /**
     * Renvoie les followers qualifiés (élus)
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getFollowersQ()
    {
        $query = PUserQuery::create()->filterByQualified(true);
        $pUsers = $this->getFollowers($query);

        return $pUsers;
    }

    /**
     * Nombre de followers qualifiés (élus)
     *
     * @return     integer
     */
    public function countFollowersQ()
    {
        $pUsers = $this->getFollowersQ();

        return count($pUsers);
    }

    /**
     * Renvoie les followers citoyens
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getFollowersC()
    {
        $query = PUserQuery::create()->filterByQualified(false);
        $pUsers = $this->getFollowers($query);

        return $pUsers;
    }

    /**
     * Nombre de followers citoyens
     *
     * @return     integer
     */
    public function countFollowersC()
    {
        $pUsers = $this->getFollowersC();

        return count($pUsers);
    }



    /**
     * Renvoie les profils d'abonnements de l'utilisateur courant.
     *
     * @return     PropelCollection PUser
     */
    public function getSubscribers(Criteria $query = null, PropelPDO $con = null)
    {
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
    public function getSubscribersQ()
    {
        $query = PUserQuery::create()->filterByQualified(true);
        $pUsers = $this->getSubscribers($query);

        return $pUsers;
    }

    /**
     * Nombre d'abonnements qualifiés (élus)
     *
     * @return     integer
     */
    public function countPUserSubscribersQ()
    {
        $pUsers = $this->getSubscribersQ();

        return count($pUsers);
    }


    /**
     * Renvoie les abonnements citoyens
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getSubscribersC()
    {
        $query = PUserQuery::create()->filterByQualified(false);
        $pUsers = $this->getSubscribers($query);

        return $pUsers;
    }

    /**
     * Nombre d'abonnements citoyens
     *
     * @return     integer
     */
    public function countPUserSubscribersC()
    {
        $pUsers = $this->getSubscribersC();

        return count($pUsers);
    }

    // *****************************    QUALIFICATION    ************************* //

    /**
     *  Renvoie les mandats par ordre décroissant
     *
     * @return array PUMandate
     */
    public function getMandates()
    {
        $query = PUMandateQuery::create()
                    ->orderByBeginAt(\Criteria::DESC);

        return parent::getPUMandates($query);
    }

    /**
     *  Renvoie les mandats courants
     *
     * @return array    PQMandate
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

    // *****************************    AFFINITÉS POLITIQUES    ************************* //

    /**
     * Renvoie les organisation courantes
     *
     * @param int $typeId       Type d'organisation
     * @param boolean $online   En ligne
     * @return PropelCollection PQOrganization
     */
    public function getCurrentOrganizations($typeId = PQType::ID_ELECTIF, $online = true)
    {
        $query = PQOrganizationQuery::create()
                    ->filterByPQTypeId($typeId)
                    ->filterByOnline($online)
                    ->setDistinct();

        return parent::getPUCurrentQOPQOrganizations($query);
    }

    /**
     * Renvoie les organisation courantes
     *
     * @param int $typeId       Type d'organisation
     * @param boolean $online   En ligne
     * @return PropelCollection PQOrganization
     */
    public function getAffinityOrganizations($typeId = PQType::ID_ELECTIF, $online = true)
    {
        $query = PQOrganizationQuery::create()
                    ->filterByPQTypeId($typeId)
                    ->filterByOnline($online)
                    ->setDistinct();

        return parent::getPUAffinityQOPQOrganizations($query);
    }

    // *****************************    TAGS   ************************* //

    /**
     * Renvoie les tags taggant l'utilisateur
     *
     *
     * @return PTag PropelCollection
     */
    public function getTaggedTags($ptTagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
                    ->_if($ptTagTypeId)
                        ->filterByPTTagTypeId($ptTagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ->_if(null === $ptTagTypeId)
                        ->orderByPTTagTypeId()
                    ->_endif()
                    ->orderByTitle()
                    ->setDistinct()
                    ;

        return parent::getPuTaggedTPTags($query);
    }

    /**
     * Renvoie les tags suivis par l'utilisateur
     *
     *
     * @return PTag PropelCollection
     */
    public function getFollowTags($ptTagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
                    ->_if($ptTagTypeId)
                        ->filterByPTTagTypeId($ptTagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ->_if(null === $ptTagTypeId)
                        ->orderByPTTagTypeId()
                    ->_endif()
                    ->orderByTitle()
                    ->setDistinct()
                    ;

        return parent::getPuFollowTPTags($query);
    }

    // *****************************    DOCUMENTS > DEBATS, REACTIONS    ************************* //

    /**
     * Renvoie les documents associés à l'utilisateur
     *
     * @return PropelCollection PDDebate
     */
    public function getDocuments($online = true, $published = true)
    {
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
     * @return PropelCollection PDDebate
     */
    public function getDebates($online = true, $published = true)
    {
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
    public function countDebates($online = true, $published = true)
    {
        $debates = $this->getDebates($online, $published);

        return count($debates);
    }


    /**
     * Renvoie les réactions associé à l'utilisateur
     *
     * @return PropelCollection PDDebate
     */
    public function getReactions($online = true, $published = true)
    {
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
    public function countReactions($online = true, $published = true)
    {
        $reactions = $this->getReactions($online, $published);

        return count($reactions);
    }

    /**
     * Renvoie les débats suivis par l'utilisateur
     *
     * @return PropelCollection PDDebate
     */
    public function getFollowedDebates($online = true, $published = true)
    {
        $query = PDDebateQuery::create()
                    ->usePUFollowDDQuery()
                        ->filterByPUserId($this->getId())
                    ->endIf()
                    ->filterByOnline($online)
                    ->filterByPublished($published)
                    ->orderByCreatedAt(\Criteria::DESC);

        return $query->find();
    }



    // *****************************    DOCUMENTS > COMMENTAIRES    ************************* //

    /**
     * Renvoit les commentaires associés à des documents (débats + réactions) pour le user courant.
     *
     * @param   $online     boolean     Renvoit uniquement les commentaires en ligne
     *
     * @return array    Liste d'objets PDDComment
     */
    public function getComments($online = true)
    {
        $query = PDCommentQuery::create()
                    ->filterByOnline($online);

        return parent::getPDComments($query);
    }

    /**
     * Renvoie le nombre de comentaires associé à l'utilisateur
     *
     * @return     integer
     */
    public function countComments($online = true)
    {
        $comments = $this->getComments($online);

        return count($comments);
    }



    // *****************************    BADGES / REPUTATION    ************************* //

    /**
     *  Renvoie les badges
     *
     * @param $pRBadgeTypeId    integer     ID type de badge
     *
     * @return PRBadge PropelCollection
     */
    public function getBadges($prBadgeTypeId = null, $online = true)
    {
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
     *  Renvoie le "score" de réputation: somme des "score_evolution" associé à toutes les actions effectuées
     *  par l'utilisateur courant.
     *
     * @return integer
     */
    public function getReputationScore(PropelPDO $con = null)
    {
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


    // *****************************    NOTIFICATIONS    ************************* //

    /**
     * Renvoit vrai / faux si le follower en argument veut être notifié des MAJ du user courant
     * suivant le contexte entré en argument.
     *
     * @param $userFollowerId   integer
     * @param $context          string
     *
     * @return boolean
     */
    public function isNotified($userFollowerId, $context = 'debate')
    {
        $puFollowU = PUFollowUQuery::create()
            ->filterByPUserId($this->getId())
            ->filterByPUserFollowerId($userFollowerId)
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
