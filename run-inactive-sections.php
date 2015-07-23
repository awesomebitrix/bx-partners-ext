<?

require("www/adm/set-doc-root.php");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arSort = array(
    // "DEPTH_LEVEL" => "ASC",
    // "SECTION" => "ASC",
    "ID" => "ASC",
);

$ib_id = "15" ;

$arFilter = array(
    "IBLOCK_ID" => $ib_id,
//    "ACTIVE" => "Y",
    "DEPTH_LEVEL" => "4",
);

$arSelect = Array("ID", "NAME", 
                  "IBLOCK_SECTION_ID",
                  "DEPTH_LEVEL", 
                  "ACTIVE", "GLOBAL_ACTIVE", "XML_ID" );

$el = new CIBlockSection;
$rsItems = $el->GetList($arSort, $arFilter, false, $arSelect);

// $fmt = "%s{%d}(%d)[%s]";
$fmt = "%s[%s]";
$fin = array(
   true => "\n",
   false => "->",
);

while($arFields = $rsItems->Fetch())
{
    // print_r($arFields);
    $nav = CIBlockSection::GetNavChain($ib_id, $arFields["ID"], array("ID", "NAME", "ACTIVE"));
    while($arNav = $nav->GetNext()){
/*
        if ( $arFields["ID"] != $arNav["ID"] ) {
           // printf( "%s{%d}(%d)[%s]->", $arNav["NAME"] ,$arNav["ID"], $arNav["DEPTH_LEVEL"], $arNav["ACTIVE"] );
           printf( $fmt."->", $arNav["NAME"] ,$arNav["ACTIVE"] );
        } else {
           printf( $fmt."\n", $arNav["NAME"] ,$arNav["ACTIVE"] );
        }
    } 
    echo "\n" ;
*/

       printf( $fmt.$fin[ $arFields["ID"] == $arNav["ID"] ], $arNav["NAME"] ,$arNav["ACTIVE"] );
   }
}

