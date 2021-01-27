<?php
namespace ThaoHR\Services\Export;

class ExportAdapter {
    const EXPORT_CLIENT = 'ExportClient';
    const EXPORT_SALE_STAGE_FOREACH_STAFF = 'ExportSaleStageFollowingStaff';
    
    private static $instance = null;
    
    public static function getInstance(string $exportType): IExport {
        if (self::$instance == null) {
            $clazz = '\\ThaoHR\\Services\\Export\\' . $exportType;
            self::$instance = new $clazz();
        }
        return self::$instance;
    }
}