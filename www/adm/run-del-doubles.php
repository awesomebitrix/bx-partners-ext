<?
ini_set("max_execution_time", "10800");
require_once("set-doc-root.php");	
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
?><? 

	require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/init_ex_site_catalog_update.php"); 
	
?><?
	
	
    CSiteExCatalogUpdate::DeleteAllDuplicateItems(); //Anti-error


?>
