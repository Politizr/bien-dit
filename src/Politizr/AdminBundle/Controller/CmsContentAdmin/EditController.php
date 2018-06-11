<?php

namespace Politizr\AdminBundle\Controller\CmsContentAdmin;

use Admingenerated\PolitizrAdminBundle\BaseCmsContentAdminController\EditController as BaseEditController;

use Politizr\Constant\CmsConstants;

use Politizr\Model\CmsContentAdmin;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
    * Check user credentials
    *
    * @param string $credentials
    * @param CmsContentAdmin $CmsContentAdmin
    * @return boolean
    */
    protected function validateCredentials($credentials, CmsContentAdmin $cmsContentAdmin = null)
    {
        if ($cmsContentAdmin->getId() == CmsConstants::CMS_CONTENT_ADMIN_HOMEPAGE) {
            return parent::validateCredentials($credentials, $cmsContentAdmin);
        } elseif (!$this->getUser()->hasRole('ROLE_SYSTEM')) {
            return false;
        }
    }
}
