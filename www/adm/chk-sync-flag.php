#!/usr/bin/env php

<? 
require_once("set-doc-root.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/init_ex_site_catalog_update.php");

$arFilter = array(
    "IBLOCK_ID" => "6",
//    "ACTIVE" => "Y",
);

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "XML_ID" , "PROPERTY_EX_SYNC_FLAG", "ACTIVE" );

$el = new CIBlockElement;
$rsItems = $el->GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
ob_start();
echo "items=". $rsItems->SelectedRowsCount() ."\n";

while($ob = $rsItems->GetNextElement())
{
    $arFields = $ob->GetFields();
    $arSrc[$arFields["XML_ID"]] = $arFields ;
}

print "Got arSrc\n";
// print_r($arSrc);
ob_flush();
/***********************************************/

# get a list of catalogs
$arFilterCat = Array(
    "TYPE"=>"ex_catalog",
//    "ACTIVE" => "Y",
);

$ib = new CIBlock;
$rsIBs = $ib->GetList(Array("SORT"=>"ASC"), $arFilterCat);

/* list */
while($arFields = $rsIBs->Fetch())
{
    // skip automatix
    if ($ib_id == 107) continue;
    // skip ark5
    if ($ib_id == 472) continue;
    // skip ark7
    if ($ib_id == 510) continue;


    // if ($arFields["ID"] !== "243" ) continue; // site 01 with this ID only
    if ($arFields["ID"] != 5 ) continue; // site s1 with this ID only
    print "Got catalog ID=".$arFields["ID"]."\n";
    ob_flush();
    // print_r($arFields);

    $arFilter = array(
        "IBLOCK_ID" => $arFields["ID"],
    //    "ACTIVE" => "Y",
    );
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "XML_ID" , "PROPERTY_EX_SYNC_FLAG", "ACTIVE" );
    $rsItems = $el->GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);

    /***/
    $cnt_good=0;
    $cnt_bad=0;
    while($ob = $rsItems->GetNextElement())
    {
        $arItemFields = $ob->GetFields();
        $loc_XML_ID = $arItemFields["XML_ID"] ;
        $both_Y = ($arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y') && ($arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y');
        $src_Not_sync = ($arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'N') || ($arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] == '');
        $dst_Not_sync = ($arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"]       == 'N') || ($arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"]       == '');
        $both_N = ($src_Not_sync && $dst_Not_sync) ;
        // if ( ($arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y') && ($arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y') ) {
        if ( $both_Y || $both_N  ) {
            $cnt_good++;
            // print "catalog_ID=".$arFields["ID"]." cnt=".$cnt_good. " ". $arItemFields["NAME"]. " - OK\n";
        } else {
            // if ($arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y') {
                $cnt_bad++;
                // print "catalog_ID=".$arFields["ID"]." Item #".$cnt_bad." with wrong EX_SYNC_FLAG={".$arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"]."}, Src_Flag={".$arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"]."} XML_ID=". $arItemFields["XML_ID"].", NAME=".$arItemFields["NAME"]."\n";
                printf( "catalog_ID=%d Item#=%d Src_Flag/Active={%s/%s} EX_SYNC_FLAG/Active={%s/%s} , NAME=%s\n"
,$arFields["ID"]
,$cnt_bad
,$arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] 
,$arSrc[$loc_XML_ID]["ACTIVE"]
,$arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"] 
,$arItemFields["ACTIVE"] 
,$arItemFields["NAME"]);

                /*** Update ***
                $el->SetPropertyValues($arItemFields["ID"], $arFields["ID"], $arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"], "EX_SYNC_FLAG");
                ***/
            // }                
        }    
        ob_flush();
    }
    /***/


}
/**/


/***********************************************/
echo "Done\n";
ob_end_flush();
?>
