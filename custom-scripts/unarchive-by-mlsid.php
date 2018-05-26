<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$tourarchive = new tourarchive();
	$mls = new mls();
	$mlsIDs = array(
		"9997737 ACT 2100 Frostwood Unit#6118 The Canyons Waldorf Astoria 1 2 $650,000 $741",
		"11400577 ACT 9755 N Ridgeway Jordanelle Tuhaye 1.980 $449,000",
		"11400578 ACT 9470 N Uinta Jordanelle Tuhaye 0.910 $425,000",
		"11400973 ACT 244 White Pine Canyon Road The Canyons Colony At White Pine Canyon 6.370 $2,600,000",
		"11400965 ACT 248 White Pine Canyon Road The Canyons Colony At White Pine Canyon 5.000 $2,900,000",
		"11400966 ACT 249 White Pine Canyon Road The Canyons Colony At White Pine Canyon 3.290 $2,900,000",
		"11400967 ACT 250 White Pine Canyon Road The Canyons Colony At White Pine Canyon 2.900 $2,600,000",
		"11402418 ACT 7101 Stein CIR Unit#413 Upper Deer Valley Resort Stein Eriksen Residences 4 5 $5,000,000 $1,074",
		"11402415 ACT 7101 Stein CIR Unit#422 Upper Deer Valley Resort Stein Eriksen Residences 5 7 $5,740,000 $1,225",
		"11402414 ACT 7101 Stein CIR Unit#521 Upper Deer Valley Resort Stein Eriksen Residences 5 6 $6,200,000 $1,330",
		"11402422 ACT 7101 Stein CIR Unit#613 Upper Deer Valley Resort Stein Eriksen Residences 5 6 $6,900,000 $1,447",
		"11403380 ACT 10288 N Summit View Deer Crest Deer Crest Area 1.020 $4,199,000",
		"11405025 ACT 2315 W Harmony DR Unit#4A Deer Crest Deer Pointe 5 6 $3,950,000 $604",
		"11405220 ACT 3000 Canyons Resort Unit#4807 The Canyons Lodge at Westgate 1 1 $542,500 $700",
		"11405219 ACT 3000 Canyons Resort Unit#4510 The Canyons Lodge at Westgate 1 1 $564,900 $700",
		"11405217 ACT 3000 Canyons Resort Unit#4507 The Canyons Lodge at Westgate 2 2 $788,200 $700",
		"11405218 ACT 3000 Canyons Resort Unit#4502 The Canyons Lodge at Westgate 2 2 $794,500 $700",
		"11500334 ACT 3551 N Escala CT Unit#209 The Canyons Hyatt Centric Park City 2 2 $899,000 $593",
		"11500336 ACT 3551 N Escala CT Unit#508 The Canyons Hyatt Centric Park City 4 5 $2,125,000 $784",
		"11403282 ACT 10010 N Uinta Jordanelle Tuhaye 1.290 $750,000",
		"11500734 ACT 9330 N Uinta CIR Jordanelle Tuhaye 1.140 $300,000",
		"11501143 ACT 7101 Stein Circle Unit#414 Upper Deer Valley Resort Stein Eriksen Residences 5 6 $5,600,000 $1,203",
		"11501118 ACT 2100 Frostwood BLVD Unit#4152 The Canyons Waldorf Astoria 2 3 $865,000 $681",
		"11501141 ACT 7101 Stein CIR Unit#311 Upper Deer Valley Resort Stein Eriksen Residences 3 4 $3,250,000 $1,220",
		"11501406 ACT 6822 Stein Circle Unit#H12 Upper Deer Valley Resort Stein Eriksen Residences 5 9 0.000 $7,800,000 $1,325",
		"11501216 ACT 17 Nakoma Terrace Unit##7 Empire Pass Nakoma 6 8 0.000 $6,875,000 $1,094",
		"11501152 ACT 11704 E Forest Creek RD Woodland and Francis Wolf Creek Ranch 160.000 $1,100,000",
		"11502025 ACT 4338 Willow Draw Unit#1106 The Canyons Juniper Landing 2 3 $925,000 $611",
		"11502263 ACT 71 White Pine Canyon RD The Canyons Colony At White Pine Canyon 6 9 5.920 $8,250,000 $877",
		"11502712 ACT 6931 Stein Circle Unit#H6A Upper Deer Valley Resort Stein Eriksen Residences 5 7 0.000 $6,750,000 $1,227",
		"11502717 ACT 6921 Stein CIR Unit#H6B Upper Deer Valley Resort Stein Eriksen Residences 5 7 0.000 $6,850,000 $1,245",
		"11503347 ACT 7815 Falcon CT Upper Deer Valley Resort Bald Eagle Club 6 8 0.920 $10,900,000 $1,235",
		"11503476 ACT 3558 N Escala CT Unit#254 The Canyons Hyatt Centric Park City 3 3 $1,350,000 $665",
		"11503192 ACT 2300 Deer Valley DR Unit#628 Deer Crest St. Regis 4 5 $2,750,000 $1,115",
		"11504040 ACT 3525 Oakwood Drive Lower Deer Valley Resort The Oaks At Deer Valley 5 5 0.330 $2,699,000 $397",
		"11504522 ACT 9006 N Uinta DR Jordanelle Tuhaye 2.100 $179,000",
		"11504521 ACT 2350 E Slingshot Jordanelle Tuhaye 1.700 $659,000",
		"11504520 ACT 9444 N Uinta DR Jordanelle Tuhaye 0.970 $399,000",
		"11504546 ACT 9020 N Uinta DR Jordanelle Tuhaye 1.910 $179,000",
		"11504654 ACT 202 White Pine Canyon RD The Canyons Colony At White Pine Canyon 9.530 $1,900,000",
		"11504645 ACT 193 White Pine Canyon RD The Canyons Colony At White Pine Canyon 6.070 $2,400,000",
		"11504648 ACT 196 White Pine Canyon RD The Canyons Colony At White Pine Canyon 5.520 $2,400,000",
		"11504651 ACT 199 White Pine Canyon RD The Canyons Colony At White Pine Canyon 4.260 $1,900,000",
		"11504643 ACT 192 White Pine Canyon RD The Canyons Colony At White Pine Canyon 4.040 $1,700,000",
		"11504640 ACT 190 White Pine Canyon RD The Canyons Colony At White Pine Canyon 4.020 $1,800,000",
		"11504647 ACT 195 White Pine Canyon RD The Canyons Colony At White Pine Canyon 4.000 $1,800,000",
		"11504652 ACT 200 White Pine Canyon RD The Canyons Colony At White Pine Canyon 3.900 $1,800,000",
		"11504642 ACT 191 White Pine Canyon RD The Canyons Colony At White Pine Canyon 4.720 $1,800,000",
		"11504842 ACT 7951 E Aspen Ridge RD Woodland and Francis Wolf Creek Ranch 160.000 $1,250,000",
		"11504599 ACT 1944 N Callaway DR Heber and Daniels Heber and Daniels Area 0.500 $115,000",
		"11504602 ACT 1820 N Cove Springs Heber and Daniels Heber and Daniels Area 0.460 $127,000",
		"11504936 ACT 179 White Pine Canyon RD The Canyons Colony At White Pine Canyon 7.060 $2,150,000",
		"11504101 ACT 8789 Marsac AVE Unit#19 Empire Pass Ironwood 4 6 $3,895,000 $1,002",
		"11600236 ACT 7887 Aster CT Upper Deer Valley Resort Knoll Estates/Deer Valley Club Estates 6 7 0.540 $6,950,000 $808",
		"11600437 ACT 1333 Snow Berry ST Glenwild / Silver Creek Glenwild 5 7 2.690 $3,695,000 $455",
		"11600467 ACT 10786 N Hideout TRL Unit#T3 Jordanelle Hideout Canyon 3 4 $725,000 $259",
		"11600469 ACT 10784 N Hideout TRL Unit#T4 Jordanelle Hideout Canyon 3 4 $749,000 $268",
		"11600517 ACT 230 White Pine Canyon Rd. The Canyons Colony At White Pine Canyon 3.180 $2,100,000",
		"11600515 ACT 227 White Pine Canyon RD The Canyons Colony At White Pine Canyon 3.040 $1,900,000",
		"11600611 ACT 2391 W Harmony DR Deer Crest Deer Pointe 4 6 $3,990,000 $611",
		"11601761 ACT 2669 E Shadow Ridge Jordanelle Tuhaye 1.260 $475,000",
		"11601743 ACT 000 Mayflower Mountain @ Deer Valley and Jordanelle DR Other Wasatch County Wasatch County Area 160.000 $1,650,000",
		"11601987 ACT 257 White Pine Canyon RD The Canyons Colony At White Pine Canyon 4.440 $2,400,000",
		"11602098 ACT 1024 S 5700 E Heber and Daniels Heber and Daniels Area 1.080 $195,000",
		"11602105 ACT 1025 S 5700 E Heber and Daniels Heber City 1.080 $195,000",
		"11602396 ACT 311 Red Ledges BLVD Heber and Daniels Red Ledges 0.460 $259,000",
		"11600687 ACT 7715 Village WAY Unit#204 Empire Pass Shooting Star 2 3 $1,345,000 $996",
		"11602759 ACT 4283 Greener Hills CT Heber and Daniels Greenerhills 4 4 6.760 $1,275,000 $296",
		"11601768 ACT 2100 W Frostwood BLVD Unit#3160 The Canyons Waldorf Astoria 2 3 $754,000 $545",
		"11603108 ACT 8886 Empire Club Dr. Unit#201 Empire Pass Arrowleaf 2 3 $1,129,000 $919",
		"11603124 ACT 7476 Purple Sage Glenwild / Silver Creek Glenwild 1.250 $799,000",
		"11603603 ACT 70 Silver Strike TRL Unit#17 Empire Pass The Belles at Empire Pass 5 6 0.000 $6,875,000 $1,186",
		"11603824 ACT 8777 Marsac AVE Unit#405 Empire Pass Grand Lodge 6 6 $3,225,000 $937",
		"11603963 ACT 2933 E Ridgeway Jordanelle Tuhaye 2.550 $625,000",
		"11604015 ACT 4523 S Gilead WAY Other Utah Other(Areas 23-41) 5 7 5.870 $5,900,000 $430",
		"11604041 ACT 1383 Snow Berry St. Glenwild / Silver Creek Glenwild 5 4 3.980 $3,590,000 $692",
		"11604164 ACT 2189 White Pine Canyon The Canyons The Canyons Area 4 4 106.940 $17,500,000 $3,062",
		"11603949 ACT 5956 N Triple Crown TRL Oakley and Weber Canyon Maple Ridge Ranches 4 5 11.210 $2,350,000 $377",
		"11604193 ACT 2251 E Lone Peak Jordanelle Tuhaye 5 7 4.800 $5,000,000 $725",
		"11604370 ACT 2700 High Mountain RD Unit#902 The Canyons Apex Residences 3 4 $1,750,000 $839",
		"11604365 ACT 2700 High Mountain RD Unit#202 The Canyons Apex Residences 4 5 $2,495,000 $906",
		"11604357 ACT 3551 N Escala CT Unit#401 The Canyons Hyatt Centric Park City 3 3 $1,600,000 $790",
		"11604295 ACT 2359 W Red Pine RD The Canyons Timberwolf Estates 4 7 0.750 $4,650,000 $734",
		"11604290 ACT 449 Echo SPUR Old Town Echo Spur 0.065 $945,000",
		"11604387 ACT 2700 High Mountain RD Unit#901 The Canyons Apex Residences 3 4 $1,850,000 $822",
		"11604381 ACT 2700 High Mountain RD Unit#603 The Canyons Apex Residences 4 5 $2,295,000 $780",
		"11604378 ACT 2700 High Mountain RD Unit#801 The Canyons Apex Residences 4 5 $2,375,000 $807",
		"11604170 ACT 2700 High Mountain RD Unit#1501 The Canyons Apex Residences 4 5 $2,725,000 $854",
		"11604393 ACT 2700 High Mountain RD Unit#1502 The Canyons Apex Residences 4 5 $2,725,000 $854",
		"11604307 ACT 441 Echo SPUR Old Town Echo Spur 0.065 $975,000",
		"11604337 ACT 1085 N Warm Springs RD Midway Charleston Cottages on the Green 0.050 $153,500"
	);
	$tourIDs = array();
	foreach($mlsIDs as $mlsIDInd => $mlsID){
		$extractedMLSID = explode(" ",$mlsID);
		$extractedMLSID = $extractedMLSID[0];
		$tourID = $mls->getTourByID(trim($extractedMLSID), "UT", 4);
		if(empty($tourID)){
			echo "Tour not found for: ".$mlsID."<br/>";
		}else{
			$tourIDs[] = $tourID;
		}
	}
	foreach($tourIDs as $tourIDInd => $tourID){
		$tourarchive->unArchiveTour($tourID);
		echo "Unarchived tourID: ".$tourID."<br/>";
	}
	
?>