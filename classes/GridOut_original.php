<?php
	include("../lib/inc/jqgrid_dist.php");
	global $conn;
	
	$keysCurrQuery = array('CatalogID','Record','ConsRecord','Tag');
	$arrCurrQuery = array_fill_keys($keysCurrQuery, null);
	
	$keysSettingsVariable = array('m_Index','m_Monthly','m_PriceDev','m_BudComp','m_Receivables','m_Budget','m_Inventory','m_Production',
		'm_Logistic','m_Winding','m_WorkSteps','m_OrdersEA','m_Shipping','m_Workflow','m_OwnProd','m_Merchandise','m_Prognose',
		'm_PP','m_DateFrom','m_DateTo','m_WIA','m_FrameTag','m_SettingsYearPP','m_SettingsYear','m_SettingsMonth');
	$arrSettingsVariable = array_fill_keys($keysSettingsVariable, null);
	//print_r($keysSettingsVariable);

	if (isset($keysTransferValues['m_Index']) && $keysTransferValues['m_Index'] !== null) {
		// код, который выполнится, если значение не равно null и элемент массива существует
	} else {
		// код, который выполнится, если значение равно null или элемент массива не существует
	}

	//error_log("level: " . $_GET["level"] . PHP_EOL, 3, "GridOut.log.log");
   	switch ($_GET["level"]) {
    	case 0:
    	    getCat();
      		break;
      	case 1:
        	getCatPM();
        	break;
   	}

	//global $reportSettings;

	$grid = new jqgrid($db_conf);

	//$opt = array();
	$opt["caption"] = "Invoice Data"; // caption of grid
	$opt["loadtext"] = "Loading...";
	$opt["toolbar"] = "top";

	$opt["actionicon"] = false;
	$opt["tooltip"] = true;
	$opt["altRows"] = false;
	$opt["multiSort"] = false;
	$opt["height"] = ""; // autofit height of subgrid
	$opt["multiselect"] = false; // allow you to multi-select through checkboxes
	$opt["readonly"] = true;
	$opt["resizable"] = false;
	$opt["autoresize"] = false;
	//$opt["loadComplete"] = "function(){ getPopup(); }";

	//$opt["rownumbers"] = true;
	//$opt["rownumWidth"] = 90;

	$grid->set_options($opt);


	$reportSettings = $_SESSION['reportSettings'];
	$topicProperties = $_SESSION['topicProperties'];

	$butTopic = $topicProperties[$reportSettings['queryID']];
/*
	$sql = "SELECT * FROM analyser..aCat WHERE ButtonOrder = " . $butTopic["buttonOrder"] . 
		" AND ItemOrder = " . $butTopic["itemOrder"] . " AND SubItemOrder = " . $butTopic["subItemOrder"];
*/
	$myfile = fopen("sql_query.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $sql);
	fclose($myfile);

	$stmt = sqlsrv_query( $conn, $sql);
	if( $stmt === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$arrCurrQuery['CatalogID'] = $row['CatalogID'];
		$arrCurrQuery['Record'] = $row['Record'];
		$arrCurrQuery['ConsRecord'] = $row['ConsRecord'];
		$arrCurrQuery['Tag'] = '03002010';
	}

	$myfile = fopen("sql_query.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $arrCurrQuery['Record']);
	fclose($myfile);


	$sql = "SELECT * FROM analyser..aCat WHERE ".$_GET["ident"];
	$result = sqlsrv_query( $conn, $sql);


	$grid->select_command = "SELECT * FROM analyst..ResultSet".$_GET["ident"];
	//$grid->table = "analyst..ResultSet";
	
	// generate grid output, with unique grid name as 'list1'
	$out = $grid->render("list".$_GET["ident"]);



	function getCat() {
		$opt = array();
	}

	function getCatPM() {
		$opt = array();
	}

   class TransferValues {
		private $m_Index = 0; // int
		private $itemIDlist = ""; // String
		private $Company = ""; // String
		private $CompanyID = ""; // String
		private $Year = ""; // String
		private $Month = ""; // String
		private $Area = ""; // String
		private $AreaID = ""; // String
		private $Country = ""; // String
		private $CountryID = ""; // String
		private $CountryCode = ""; // String
		private $CustomerTypeID = ""; // String
		private $CustomerType = ""; // String
		private $Customer = ""; // String
		private $CustomerName = ""; // String
		private $CustomerID = ""; // String
		private $AgentID = ""; // String
		private $Agent = ""; // String
		private $ProdGrp_Bez = ""; // String
		private $ProdGrpID = ""; // String
		private $ProdGrp = ""; // String
		private $ArticleID = ""; // String
		private $Article = ""; // String
		private $StatCode_Bez = ""; // String
		private $StatCodeID = ""; // String
		private $StatCode = ""; // String
		private $WhseID = ""; // String
		private $Whse = ""; // String
		private $WhseTyp = ""; // String
		private $WhseSTAid = ""; // String
		private $ISOcodeID = ""; // String
		private $ISOcode = ""; // String
		private $InvNo = ""; // String
		private $ArtTyp = ""; // String
		private $ArtTypID = ""; // String
		private $ArtTypName = ""; // String
		private $ProdType = ""; // String
		private $ProdTypeID = ""; // String
		private $ProdTypeName = ""; // String
		private $ArtFam = ""; // String
		private $ArtFamID = ""; // String
		private $SupplierTypeID = ""; // String
		private $SupplierType = ""; // String
		private $Supplier = ""; // String
		private $SupplierName = ""; // String
		private $SupplierID = ""; // String
		private $SalMan = ""; // String
		private $SalManID = ""; // String
		private $SalManName = ""; // String
		private $RegMan = ""; // String
		private $RegManID = ""; // String
		private $RegManNo = ""; // String
		private $AreaMan = ""; // String
		private $AreaManID = ""; // String
		private $AreaManNo = ""; // String

		private $TradeRelationID = ""; // String
		private $Relation = ""; // String
		private $Title = ""; // String
		
		private $articleOwnMerch;

		private $Quantity; // boolean
		private $OptionIC; // int
		private $OptionTP; // int
		private $ConsCompanies;
		private $ConsWarehouses;
		private $ThisFormIsCumulative; // boolean
		private $FormYear;
		private $FormMonth;
		private $FromYearPP;
		private $FormWIA;
		private $ToDate;
		private $FromDate;
		private $LocPriceDB;
		private $ParentTag; // -
		// private $Caption; //-
		private $Jump; // boolean
		private $ItemOrder; // int

		private $ConsAreas;
		private $ConsCountries;
		private $ConsCustomers;
		private $Day = ""; // String
		private $nextYear = ""; // String
		private $prevYear = ""; // String

		private $PurchToday = ""; // String
		private $TillToday = ""; // String
		private $FromToday = ""; // String
		private $Ultimo = ""; // String
		private $prev6Month = ""; // String
		private $next3Month = ""; // String
		private $prev12Month = ""; // String
		private $prev1Month = ""; // String
		private $CumMonths = ""; // String
		private $UnCumMonths = ""; // String
		private $ToDateYear = ""; // String
		private $ToDateLastYear = ""; // String
		private $ToDateMonth = ""; // String
		private $ToDateDay = ""; // String
		private $DIVI = ""; // String
		// private $CapComp = ""; // String
		private $MonthSel = ""; // String
		private $MonthM1 = ""; // String
		private $MonthP1 = ""; // String
		private $MonthP2 = ""; // String
		private $MonthP3 = ""; // String
		private $YearM1 = ""; // String
		private $YearP1 = ""; // String
		private $YearP2 = ""; // String
		private $YearP3 = ""; // String
		private $WhseCons; // boolean
		private $CompCons; // boolean
		private $WinCap = ""; // String
		private $QueryCompany;
		private $IsEuro; // boolean
		private $IsSQM; // boolean
		private $Valuta;

		private $CompanyCode = ""; // String
		// private $QueryIdent;
		private $CurrYear;
		private $CurrMonth;
		private $CurrDay;
		private $Tag;
		// private $CapForStr;
		private $QueryHistory;
		private $GraphQuery;
		private $TAID;
		private $TA;
		private $ItemsCount; // int
		private $oldFormat; // String
	}

	class SettingsVariable {
		
      private $m_Index; // int
		private $m_Monthly; // boolean
		private $m_PriceDev; // boolean
		private $m_BudComp; // boolean
		private $m_Receivables; // boolean
		private $m_Budget; // boolean
		private $m_Inventory; // boolean
		private $m_Production; // boolean
		private $m_Logistic; // boolean
		private $m_Winding; // boolean
		private $m_WorkSteps; // boolean
		private $m_OrdersEA; // boolean
		private $m_Shipping; // boolean
		private $m_Workflow; // boolean
		private $m_OwnProd; // boolean
		private $m_Merchandise; // boolean
		private $m_Prognose; // boolean
		private $m_PP; // boolean
		private $m_DateFrom; // String
		private $m_DateTo; // String
		private $m_WIA; // String
		private $m_FrameTag; // String
		private $m_SettingsYearPP; // String
		private $m_SettingsYear; // String
		private $m_SettingsMonth; // String
	}

?>
