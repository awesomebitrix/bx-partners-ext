<?

require("www/adm/set-doc-root.php");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arSort = array(
    // "DEPTH_LEVEL" => "ASC",
    // "SECTION" => "ASC",
    // "ID" => "ASC",
    "left_margin"=>"asc",
);

$ib_id = "6" ;
$arFilter = array(
    "IBLOCK_ID" => $ib_id,
);

$arSelect = Array("ID", "NAME", 
                  "IBLOCK_SECTION_ID",
                  "DEPTH_LEVEL", 
                  "ACTIVE", "GLOBAL_ACTIVE", "XML_ID" );

$el = new CIBlockSection;
$rsItems = $el->GetList($arSort, $arFilter, false, $arSelect);

while($arFields = $rsItems->GetNext())
{
    // print_r($arFields);
    $arPath = "";
    $nav = CIBlockSection::GetNavChain($ib_id, $arFields["ID"], array("ID", "NAME", "ACTIVE"));
    while($arNav = $nav->GetNext()){
       $arPath = $arPath .$arNav["NAME"] ;
       if ( $arFields["ID"] != $arNav["ID"]) {
          $arPath = $arPath . "->";
       } else {
         $arSrc[$arFields["NAME"]][$arFields["DEPTH_LEVEL"]] = array("ID"=>$arFields["ID"], "ACTIVE"=>$arFields["ACTIVE"], "PATH"=>$arPath);
       }
   }
}

/*******************************************************************/

$ib_id = "15" ;
$arFilter = array(
    "IBLOCK_ID" => $ib_id,
);

$rsItems = $el->GetList($arSort, $arFilter, false, $arSelect);

while($arFields = $rsItems->GetNext())
{
    // print_r($arFields);
    $arPath = "";
    $nav = CIBlockSection::GetNavChain($ib_id, $arFields["ID"], array("ID", "NAME", "ACTIVE"));
    while($arNav = $nav->GetNext()){
       $arPath = $arPath .$arNav["NAME"] ;
       if ( $arFields["ID"] != $arNav["ID"]) {
          $arPath = $arPath . "->";
       } else {
         //$key = array("NAME"=>$arFields["NAME"], "DEPTH_LEVEL"=>$arFields["DEPTH_LEVEL"]);
         //$arCat[$key] = array("ID"=>$arFields["ID"], "ACTIVE"=>$arFields["ACTIVE"], "PATH"=>$arPath) ;
         $arCat[$arFields["NAME"]][$arFields["DEPTH_LEVEL"]] = array("ID"=>$arFields["ID"], "ACTIVE"=>$arFields["ACTIVE"], "PATH"=>$arPath);
       }
   }
}

// print_r($arCat);


print "---=== Ошибочно деактивирован у дилера ===---\n";
foreach ( array_keys($arCat) as $keyName ) {
    foreach ( array_keys($arCat[$keyName] ) as $keyDepth ) {
        // print $keyName. "/" . $keyDepth . "\n";
        $catElem = $arCat[$keyName][$keyDepth];
        $srcElem = $arSrc[$keyName][$keyDepth];
        if ( $catElem["PATH"] == "Распродажа" ) continue;
        if ( $catElem["PATH"] == "Программное обеспечение" ) continue;
 	// if ( $catElem["ACTIVE"] != $srcElem["ACTIVE"] ) {
 	if ( $srcElem["ACTIVE"] == 'Y' && $catElem["ACTIVE"] == 'N') {
             print $catElem["PATH"]. "/ID=". $catElem["ID"] ."\n"; //. "[" . $catElem["ACTIVE"] ."]" . " Источник[" . $srcElem["ACTIVE"] . "]\n" ;
	}
    }
}

print "---=== Ошибочно активирован у дилера ===---\n";
foreach ( array_keys($arCat) as $keyName ) {
    foreach ( array_keys($arCat[$keyName] ) as $keyDepth ) {
        // print $keyName. "/" . $keyDepth . "\n";
        $catElem = $arCat[$keyName][$keyDepth];
        $srcElem = $arSrc[$keyName][$keyDepth];
 	// if ( $catElem["ACTIVE"] != $srcElem["ACTIVE"] ) {
 	if ( $srcElem["ACTIVE"] == 'N' && $catElem["ACTIVE"] == 'Y') {
             print $catElem["PATH"]. "\n"; //. "[" . $catElem["ACTIVE"] ."]" . " Источник[" . $srcElem["ACTIVE"] . "]\n" ;
	}
    }
}


