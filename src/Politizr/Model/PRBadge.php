<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadge;

use Politizr\Constant\ReputationConstants;
use Politizr\Constant\PathConstants;

use Politizr\Exception\InconsistentDataException;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

/**
 *
 * @author Lionel Bouzonville
 */
class PRBadge extends BasePRBadge
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

     /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  StaticTools::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }


    // ******************* SIMPLE UPLOAD MANAGEMENT **************** //

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
        return PathConstants::BADGES_UPLOAD_WEB_PATH . $this->file_name;
    }
    
    /**
     *
     */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . PathConstants::BADGES_UPLOAD_PATH . $this->file_name
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
        $fileName = 'badge-' . StaticTools::randomString() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PathConstants::BADGES_UPLOAD_PATH, $fileName);

        // file_name
        return $fileName;
    }

    /**
     *    Surcharge pour gérer la suppression physique.
     */
    public function setFileName($v)
    {
        if (!$v) {
            $this->removeUpload(true);
        }
        parent::setFileName($v);
    }

    /**
     *     Suppression physique des fichiers.
     */
    public function removeUpload($uploadedFileName = true)
    {
        if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PathConstants::BADGES_UPLOAD_PATH . $this->file_name)) {
            unlink(__DIR__ . PathConstants::BADGES_UPLOAD_PATH . $this->file_name);
        }
    }
    // ******************* END SIMPLE UPLOAD MANAGEMENT **************** //

    /**
     * Compute family's description combined with current level to reach
     *
     * @return string
     */
    public function getDescription()
    {
        $family = $this->getPRBadgeFamily();

        switch($this->getId()) {
            case ReputationConstants::BADGE_ID_QUERELLE:
                $description = sprintf($family->getDescription(), ReputationConstants::QUERELLE_NB_REACTIONS);
                break;
            case ReputationConstants::BADGE_ID_CONTROVERSE:
                $description = sprintf($family->getDescription(), ReputationConstants::CONTROVERSE_NB_REACTIONS);
                break;
            case ReputationConstants::BADGE_ID_POLEMIQUE:
                $description = sprintf($family->getDescription(), ReputationConstants::POLEMIQUE_NB_REACTIONS);
                break;
            case ReputationConstants::BADGE_ID_REDACTEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::REDACTEUR_NB_DOCUMENTS, ReputationConstants::REDACTEUR_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_REDACTEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::REDACTEUR_NB_DOCUMENTS, ReputationConstants::REDACTEUR_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_AUTEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::AUTEUR_NB_DOCUMENTS, ReputationConstants::AUTEUR_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_ECRIVAIN:
                $description = sprintf($family->getDescription(), ReputationConstants::ECRIVAIN_NB_DOCUMENTS, ReputationConstants::ECRIVAIN_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_ECLAIREUR:
                $description = sprintf($family->getDescription(), ReputationConstants::ECLAIREUR_NB_DEBATES);
                break;
            case ReputationConstants::BADGE_ID_AVANT_GARDE:
                $description = sprintf($family->getDescription(), ReputationConstants::AVANT_GARDE_NB_DEBATES);
                break;
            case ReputationConstants::BADGE_ID_GUIDE:
                $description = sprintf($family->getDescription(), ReputationConstants::GUIDE_NB_DEBATES);
                break;
            case ReputationConstants::BADGE_ID_ANNOTATEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::ANNOTATEUR_NB_COMMENTS);
                break;
            case ReputationConstants::BADGE_ID_GLOSSATEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::GLOSSATEUR_NB_COMMENTS);
                break;
            case ReputationConstants::BADGE_ID_COMMENTATEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::COMMENTATEUR_NB_COMMENTS);
                break;
            case ReputationConstants::BADGE_ID_EFFRONTE:
                $description = sprintf($family->getDescription(), ReputationConstants::EFFRONTE_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_IMPERTINENT:
                $description = sprintf($family->getDescription(), ReputationConstants::IMPERTINENT_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_AUDACIEUX:
                $description = sprintf($family->getDescription(), ReputationConstants::AUDACIEUX_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_STUDIEUX:
                $description = $family->getDescription();
                break;
            case ReputationConstants::BADGE_ID_TAGUEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::TAGUEUR_NB_TAGS);
                break;
            case ReputationConstants::BADGE_ID_SURVEILLANT:
                $description = sprintf($family->getDescription(), ReputationConstants::SURVEILLANT_NB_MODERATIONS);
                break;
            case ReputationConstants::BADGE_ID_FOUGUEUX:
                $description = sprintf($family->getDescription(), ReputationConstants::FOUGUEUX_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_ENTHOUSIASTE:
                $description = sprintf($family->getDescription(), ReputationConstants::ENTHOUSIASTE_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_PASSIONNE:
                $description = sprintf($family->getDescription(), ReputationConstants::PASSIONNE_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_PERSIFLEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::PERSIFLEUR_NB_NOTENEG);
                break;
            case ReputationConstants::BADGE_ID_REPROBATEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::REPROBATEUR_NB_NOTENEG);
                break;
            case ReputationConstants::BADGE_ID_CRITIQUE:
                $description = sprintf($family->getDescription(), ReputationConstants::CRITIQUE_NB_NOTENEG);
                break;
            case ReputationConstants::BADGE_ID_ATTENTIF:
                $description = sprintf($family->getDescription(), ReputationConstants::ATTENTIF_NB_DAYS);
                break;
            case ReputationConstants::BADGE_ID_ASSIDU:
                $description = sprintf($family->getDescription(), ReputationConstants::ASSIDU_NB_DAYS);
                break;
            case ReputationConstants::BADGE_ID_FIDELE:
                $description = sprintf($family->getDescription(), ReputationConstants::FIDELE_NB_DAYS);
                break;
            case ReputationConstants::BADGE_ID_SUIVEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::SUIVEUR_NB_SUBSCRIBES);
                break;
            case ReputationConstants::BADGE_ID_DISCIPLE:
                $description = sprintf($family->getDescription(), ReputationConstants::DISCIPLE_NB_SUBSCRIBES);
                break;
            case ReputationConstants::BADGE_ID_INCONDITIONNEL:
                $description = sprintf($family->getDescription(), ReputationConstants::INCONDITIONNEL_NB_SUBSCRIBES);
                break;
            case ReputationConstants::BADGE_ID_IMPORTANT:
                $description = sprintf($family->getDescription(), ReputationConstants::IMPORTANT_NB_FOLLOWERS);
                break;
            case ReputationConstants::BADGE_ID_INFLUENT:
                $description = sprintf($family->getDescription(), ReputationConstants::INFLUENT_NB_FOLLOWERS);
                break;
            case ReputationConstants::BADGE_ID_INCONTOURNABLE:
                $description = sprintf($family->getDescription(), ReputationConstants::INCONTOURNABLE_NB_FOLLOWERS);
                break;
            case ReputationConstants::BADGE_ID_PORTE_VOIX:
                $description = sprintf($family->getDescription(), ReputationConstants::PORTE_VOIX_NB_SHARE);
                break;
            case ReputationConstants::BADGE_ID_FAN:
                $description = sprintf($family->getDescription(), ReputationConstants::FAN_NB_SHARE);
                break;
            case ReputationConstants::BADGE_ID_AMBASSADEUR:
                $description = sprintf($family->getDescription(), ReputationConstants::AMBASSADEUR_NB_SHARE);
                break;
            default:
                throw new InconsistentDataException(sprintf('Badge ID-%s not recognized', $badge->getId()));
        }

        return $description;
    }
}
