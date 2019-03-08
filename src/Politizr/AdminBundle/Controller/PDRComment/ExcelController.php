<?php

namespace Politizr\AdminBundle\Controller\PDRComment;

use Admingenerated\PolitizrAdminBundle\BasePDRCommentController\ExcelController as BaseExcelController;

/**
 * ExcelController
 */
class ExcelController extends BaseExcelController
{
    protected function getSpreadsheetFileName($fileType)
    {
        return $this->fixSpreadsheetExtension('app-reaction-comments'.time(), $fileType);
    }

    protected function getExcelFileName($fileType)
    {
        return $this->fixExtension('app-reaction-comments'.time(), $fileType);
    }
}
