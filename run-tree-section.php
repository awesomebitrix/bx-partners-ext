<?

require("www/adm/set-doc-root.php");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arSort = array(
    "DEPTH_LEVEL" => "ASC",
    "SECTION" => "ASC",
);


$arFilter = array(
    "IBLOCK_ID" => "6",
);

$arSelect = Array("ID", "NAME", 
                  "IBLOCK_SECTION_ID",
                  "DEPTH_LEVEL", 
                  "ACTIVE", "GLOBAL_ACTIVE" );

$el = new CIBlockSection;
$rsItems = $el->GetTreeList($arFilter, $arSelect);

while($arFields = $rsItems->Fetch())
{
    $CatTree[$arFields["NAME"]][$arFields["DEPTH_LEVEL"]] = $arFields["ACTIVE"]  ;
}

$ib_id = "15" ;

$arFilter = array(
    "IBLOCK_ID" => $ib_id,
);

$arSelect = Array("ID", "NAME", 
                  "IBLOCK_SECTION_ID",
                  "DEPTH_LEVEL", 
                  "ACTIVE", "GLOBAL_ACTIVE", "XML_ID" );

$el = new CIBlockSection;
// $rsItems = $el->GetList($arSort, $arFilter, false, $arSelect);
$rsItems = $el->GetTreeList($arFilter, $arSelect);

while($arFields = $rsItems->Fetch())
{
   /**/
    $pref = sprintf("\n%'. " . 2*($arFields["DEPTH_LEVEL"]-1) . "s", ""); 
    printf( $pref."%s(%d)[%s]", $arFields["NAME"] , $arFields["DEPTH_LEVEL"], $arFields["ACTIVE"]  );
   /**/
   /*
   if ($CatTree[$arFields["NAME"]][$arFields["DEPTH_LEVEL"]]  != $arFields["ACTIVE"] ) {
      printf("Source[%s][%d]=%s / ",  $arFields["NAME"] , $arFields["DEPTH_LEVEL"], $CatTree[$arFields["NAME"]][$arFields["DEPTH_LEVEL"]]  );
      printf("IB_%d[%s][%d]=%s\n",  $ib_id, $arFields["NAME"] , $arFields["DEPTH_LEVEL"], $arFields["ACTIVE"]  );
   }
   */
}

