<?php

namespace Politizr\AdminBundle\Controller\PDDComment;

use Admingenerated\PolitizrAdminBundle\BasePDDCommentController\ExcelController as BaseExcelController;

/**
 * ExcelController
 */
class ExcelController extends BaseExcelController
{
    protected function getSpreadsheetFileName($fileType)
    {
        return $this->fixSpreadsheetExtension('app-subject-comments'.time(), $fileType);
    }

    protected function getExcelFileName($fileType)
    {
        return $this->fixExtension('app-subject-comments'.time(), $fileType);
    }
}
