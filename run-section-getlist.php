<?

require("www/adm/set-doc-root.php");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arSort = array(
    // "DEPTH_LEVEL" => "ASC",
    // "SECTION" => "ASC",
    // "ID" => "ASC",
    "left_margin"=>"asc",
);

$ib_id = "15" ;

$arFilter = array(
    "IBLOCK_ID" => $ib_id,
//    "ACTIVE" => "Y",
//    "DEPTH_LEVEL" => "4",
);

$arSelect = Array("ID", "NAME", 
                  "IBLOCK_SECTION_ID",
                  "DEPTH_LEVEL", 
                  "ACTIVE", "GLOBAL_ACTIVE", "XML_ID" );

$el = new CIBlockSection;
$rsItems = $el->GetList($arSort, $arFilter, false, $arSelect);

// $fmt = "%s{%d}(%d)[%s]";
$fmt = "%s[%s]\n";
$fin = array(
   true => "\n",
   false => "->",
);

while($arFields = $rsItems->GetNext())
{
    // print_r($arFields);
    printf( $fmt, $arFields["NAME"] ,$arFields["ACTIVE"] );
}

