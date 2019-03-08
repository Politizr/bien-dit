<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\ExcelController as BaseExcelController;

/**
 * ExcelController
 */
class ExcelController extends BaseExcelController
{
    protected function getSpreadsheetFileName($fileType)
    {
        return $this->fixSpreadsheetExtension('app-users-'.time(), $fileType);
    }

    protected function getExcelFileName($fileType)
    {
        return $this->fixExtension('app-users-'.time(), $fileType);
    }
}
