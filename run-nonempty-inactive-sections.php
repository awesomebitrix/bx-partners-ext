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
    "CNT_ACTIVE" => true,
);

$arSelect = Array("ID", "NAME", 
                  "IBLOCK_SECTION_ID",
                  "DEPTH_LEVEL", 
                  "ACTIVE", "GLOBAL_ACTIVE", "XML_ID" );

$bs = new CIBlockSection;
$rsItems = $bs->GetList($arSort, $arFilter, true, $arSelect);

while($arFields = $rsItems->GetNext())
{
    // print_r($arFields);
    $arPath = "";
    $nav = CIBlockSection::GetNavChain($ib_id, $arFields["ID"], array("ID", "NAME", "ACTIVE"));
    while($arNav = $nav->GetNext()){
       $arPath = $arPath .$arNav["NAME"] ;
       if ( $arFields["ID"] != $arNav["ID"]) {
          $arPath = $arPath . "->";
          // $arPath = $arPath . "[".  "]->";
       } else {
         $arSrc[$arFields["NAME"]][$arFields["DEPTH_LEVEL"]] = array("ID" => $arFields["ID"],
								     "ACTIVE" => $arFields["ACTIVE"],
                                                                     "PATH" => $arPath, 
                                                                     "CNT" => $arFields["ELEMENT_CNT"] );
       }
    }
    if ($arFields["ACTIVE"] == "Y") {
        print $arPath. ", ";
        print "CNT=". $arFields["ELEMENT_CNT"]. "\n";
    }
}

/*******************************************************************/

# get a list of catalogs
$arFilterCat = Array(
    "TYPE"=>"ex_catalog",
    "ACTIVE" => "Y",
);

$ib = new CIBlock;
$rsIBs = $ib->GetList(Array("SORT"=>"ASC"), $arFilterCat);

$bs = new CIBlockSection;

/* list */
while($arFieldsIB = $rsIBs->Fetch())
{
        $ib_id =  $arFieldsIB["ID"];

        // skip automatix
	// if ($ib_id == 107) continue;
        // skip ark5
	// if ($ib_id == 472) continue;
        // skip ark7
	if ($ib_id == 510) continue;

/*
        // DEBUG skip everything but 33
	if ($ib_id != 33) continue;
*/

	print "Got catalog ID=".$ib_id. "/". $arFieldsIB["NAME"] ."\n";

	$arFilter = array(
		"IBLOCK_ID" => $ib_id,
	);

	$rsItems = $bs->GetList($arSort, $arFilter, false, $arSelect);

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
		 // if ( $arFields["NAME"] == "Распродажа" ) continue;
		 // if ( $arFields["NAME"] == "Программное обеспечение" ) continue;
		 $srcElem = $arSrc[$arFields["NAME"]][$arFields["DEPTH_LEVEL"]];
		 if ($srcElem["ACTIVE"] == 'Y' && $arFields["ACTIVE"] == 'N' )
		 {
                     if ( $srcElem["CNT"] > 0 ) {
			     print "   Try update=". $arPath. "/".  $arFields["ID"] . " CNT=" .$srcElem["CNT"] . "\n";
			     /**/
			     $arUpd["ACTIVE"] = 'Y' ;
			     $res = $bs->Update($arFields["ID"], $arUpd);
			     if ($res) {
				print "UPDATED!\n";
			     } else {
				print "ERROR:".$bs->LAST_ERROR. "\n";
			     }
			     /**/
/*
                     } else {
                             print "Skip empty group". $arPath ."\n";
*/
                     } 
		 }
	       }
	   }
	}
}
