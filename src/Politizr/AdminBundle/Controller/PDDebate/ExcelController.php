<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ExcelController as BaseExcelController;

/**
 * ExcelController
 */
class ExcelController extends BaseExcelController
{
    protected function getSpreadsheetFileName($fileType)
    {
        return $this->fixSpreadsheetExtension('app-subjects-'.time(), $fileType);
    }

    protected function getExcelFileName($fileType)
    {
        return $this->fixExtension('app-subjects-'.time(), $fileType);
    }
}
