<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\ExcelController as BaseExcelController;

/**
 * ExcelController
 */
class ExcelController extends BaseExcelController
{
    protected function getSpreadsheetFileName($fileType)
    {
        return $this->fixSpreadsheetExtension('app-reactions-'.time(), $fileType);
    }

    protected function getExcelFileName($fileType)
    {
        return $this->fixExtension('app-reactions-'.time(), $fileType);
    }
}
