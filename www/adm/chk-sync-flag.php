#!/usr/bin/env php

<? 
require_once("set-doc-root.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/init_ex_site_catalog_update.php");

$arFilter = array(
    "IBLOCK_ID" => "6",
//    "ACTIVE" => "Y",
);

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "XML_ID" , "PROPERTY_EX_SYNC_FLAG" );

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
    // if ($arFields["ID"] !== "243" ) continue; // site s1 with this ID only
    print "Got catalog ID=".$arFields["ID"]."\n";
    ob_flush();
    // print_r($arFields);

    $arFilter = array(
        "IBLOCK_ID" => $arFields["ID"],
    //    "ACTIVE" => "Y",
    );
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "XML_ID" , "PROPERTY_EX_SYNC_FLAG" );
    $rsItems = $el->GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);

    /***/
    $cnt_good=0;
    $cnt_bad=0;
    while($ob = $rsItems->GetNextElement())
    {
        $arItemFields = $ob->GetFields();
        $loc_XML_ID = $arItemFields["XML_ID"] ;
        if ( ($arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y') && ($arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y') ) {
            $cnt_good++;
            // print "catalog_ID=".$arFields["ID"]." cnt=".$cnt_good. " ". $arItemFields["NAME"]. " - OK\n";
        } else {
            if ($arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"] == 'Y') {
                $cnt_bad++;
                print "catalog_ID=".$arFields["ID"]." Item #".$cnt_bad." with wrong EX_SYNC_FLAG={".$arItemFields["PROPERTY_EX_SYNC_FLAG_VALUE"]."}, Src_Flag={".$arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"]."} XML_ID=". $arItemFields["XML_ID"].", NAME=".$arItemFields["NAME"]."\n";

                /*** Update ***
                $el->SetPropertyValues($arItemFields["ID"], $arFields["ID"], $arSrc[$loc_XML_ID]["PROPERTY_EX_SYNC_FLAG_VALUE"], "EX_SYNC_FLAG");
                ***/
            }                
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
