<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

	if (empty($_SESSION['email']) || empty($_SESSION['password'])) {
		$em = "";
		if (!empty($_GET['email'])) {
			$em = "?email=" . urlencode($_GET['email']);
		}
		header("Location: login.php" . $em);
		exit;
	}
	$ident = $_SESSION['ident'];
	$password = $_SESSION['password'];
	

	$KoolControlsFolder = "../KoolControls";
	$arrTabs = array();
	//$reportSettings = array();
	$startSAP = "2018";
	$currMonth = date('F');
	$currYear = date('Y');
	$startDate = date('Y') . "-01-01";
	$endDate = date('Y') . "-" . date('m') . "-" . date('d') ;

	$keysTabSet = array('m_Index','m_Monthly','m_PriceDev','m_BudComp','m_Receivables','m_Budget','m_Inventory','m_Production',
		'm_Logistic','m_Winding','m_WorkSteps','m_OrdersEA','m_Shipping','m_Workflow','m_OwnProd','m_Merchandise','m_Prognose',
		'm_PP','m_DateFrom','m_DateTo','m_WIA','m_FrameTag','m_SettingsYearPP','m_SettingsYear','m_SettingsMonth','selCompanies',
		'itemIDlist','Company','CompanyID','Year','Month','Area','AreaID','Country','CountryID','CountryCode','ident','queryID',
		'CustomerTypeID','CustomerType','Customer','CustomerName','CustomerID','AgentID','Agent','ProdGrp_Bez','ProdGrpID','ProdGrp',
		'ArticleID','Article','StatCode_Bez','StatCodeID','StatCode','WhseID','Whse','WhseTyp','WhseSTAid','ISOcodeID','ISOcode',
		'InvNo','ArtTyp','ArtTypID','ArtTypName','ProdType','ProdTypeID','ProdTypeName','ArtFam','ArtFamID','SupplierTypeID',
		'SupplierType','Supplier','SupplierName','SupplierID','SalMan','SalManID','SalManName','RegMan','RegManID','RegManNo',
		'AreaMan','AreaManID','AreaManNo','TradeRelationID','Relation','Title','articleOwnMerch','Quantity','OptionIC','OptionTP',
		'ConsCompanies','ConsWarehouses','ThisFormIsCumulative','FormYear','FormMonth','FromYearPP','FormWIA','ToDate','FromDate',
		'LocPriceDB','ParentTag','Caption','Jump','ItemOrder','ConsAreas','ConsCountries','ConsCustomers','Day','nextYear','prevYear',
		'PurchToday','TillToday','FromToday','Ultimo','prev6Month','next3Month','prev12Month','prev1Month','CumMonths','UnCumMonths',
		'ToDateYear','ToDateLastYear','ToDateMonth','ToDateDay','DIVI','CapComp','MonthSel','MonthM1','MonthP1','MonthP2','MonthP3',
		'YearM1','YearP1','YearP2','YearP3','WhseCons','CompCons','WinCap','QueryCompany','IsEuro','IsSQM','Valuta','CompanyCode',
		'QueryIdent','CurrYear','CurrMonth','CurrDay','Tag','CapForStr','QueryHistory','GraphQuery','TAID','TA','ItemsCount',
		'oldFormat','selMonth','selYear','priceYear','timeMode','zk','ic','tp','besch');


	//error_log("ident: " . $ident . PHP_EOL, 3, "index.log");
	//error_log("password: " . $password . PHP_EOL, 3, "index.log");

	// // Get connection to SQL Server: account of User
	require_once("classes/Database.php");
	$conn = (new Database())->dbConnection($ident, $password);
	$db_conf = (new Database())->gridConnection($ident, $password);
	//$grid_conf2 = (new Database())->gridConnection($ident, $password);

	
	if (!empty($_GET["ident"]))
	{
		include_once("classes/GridOut.php");
		exit;
	}


	// // Get rights of checked User
	require_once("classes/UserRights.php");
	new UserRights($conn, $ident);

	// // Get MainMenu
	require_once("classes/MainMenu.php");
	$mainMenu = (new MainMenu())->getMainMenu();

	// // Get Outlook
	require_once("classes/OutlookBar.php");
	$outlookBar = new OutlookBar($conn);

	// // Get Ajax
	require $KoolControlsFolder."/KoolAjax/koolajax.php";
	$koolajax->scriptFolder = $KoolControlsFolder."/KoolAjax";

	// // Get Tabs
	require_once("classes/Tabs.php");
	$kts = (new Tabs())->getTabs();

	// // Get Companies
	require_once("classes/Companies.php");
	$treeview = (new Companies())->getCompanies();
	$companies = $treeview[0];
	$arrCompanies = $treeview[1];
	//var_dump ($arrCompanies);



	$arrMonths = array(
		"01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", 
		"08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December"
	);

	$arrYears = array();
	$sql = "SELECT the_year FROM analyser..the_year WHERE the_year >= ". $startSAP . " AND the_year <= STR(DATEPART(yyyy,DATEADD(year, +1, GETDATE())),4) ORDER BY the_year";
	$result = sqlsrv_query( $conn, $sql);
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$arrYears[] = $row["the_year"];
	}






	

	function setArrTabSet($tabNumber, $jsonNewSettings) {
		global $arrTabs;
		global $keysTabSet;

		$arrNewSettings = json_decode ($jsonNewSettings, true);
		if (array_key_exists($tabNumber, $arrTabs)) {
	
			foreach ($arrNewSettings as $param => $wert) {
				$arrTabs[$tabNumber][$param] = $wert;
			}
		} else {
			$arrTabSettings = array_fill_keys($keysTabSet, null);
	
			foreach ($arrNewSettings as $param => $wert) {
				$arrTabSettings[$param] = $wert;
			}
			$arrTabs[$tabNumber] = $arrTabSettings;
		}
		print_r($arrTabs);
	}
	$koolajax->enableFunction("setArrTabSet");

	function createReport($jsonEinst) {
		//global $conn;
		//global $reportSettings;




		$reportSettings = json_decode ($jsonEinst, true);
		$_SESSION['reportSettings'] = $reportSettings;

/*
		$sql = "CREATE TABLE [tempdb]..[".$reportSettings['ident']."]([param] [nvarchar](255) NULL,[wert] [nvarchar](255) NULL) ON [PRIMARY]";
		$result = sqlsrv_query( $conn, $sql);
		foreach ($reportSettings as $param => $wert) {
				$sql = "INSERT INTO [tempdb]..[" . $reportSettings['ident'] . "] (param, wert) VALUES (?, ?)";
				$params = array($param, $wert);
				$stmt = sqlsrv_query($conn, $sql, $params);
				if($stmt === false) {
					die(print_r(sqlsrv_errors(), true));
				}
		}
*/
	//	return "";
	}
	$koolajax->enableFunction("createReport");
?>
 

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Kufner Web Analyser</title>
	<link rel="icon" type="image/gif" href="img/kufner.gif">
        <link rel="stylesheet" type="text/css" href="css/index.css">
        <link rel="stylesheet" type="text/css" href="css/tabsColl.css">
	<script src="js/jquery-3.7.1.min.js"></script>


	
	<link rel="stylesheet" type="text/css" media="screen" href="../../lib/js/themes/redmond/jquery-ui.custom.css"></link>	
	<!--<link rel="stylesheet" type="text/css" media="screen" href="../../lib/js/themes/redmond/jquery-ui.custom.css"></link>-->
	<link rel="stylesheet" type="text/css" media="screen" href="../../lib/js/jqgrid/css/ui.jqgrid.css"></link>	
	<script src="../../lib/js/jquery.min.js" type="text/javascript"></script>
	<script src="../../lib/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script src="../../lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>	
	<script src="../../lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>


</head>
<body>
    <div id="wrapper" class="wrapper">
		<?php echo $koolajax->Render();?>

		<!-- MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU MENU -->
		<div id="menu">
			<form id="formMainMenu" method="post">
				<?php echo $mainMenu->Render();?>
			</form>
		</div>

		<!-- ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT ABOUT -->
		<div id="about">
			<div class="tabsColl-container">
				<button class="scroll-btn left" id="scroll-left" style="display: none;">&#9664;</button>
				<div id="tabsColl-wrapper">
					<div id="tabsColl">
						<ul>
							<!-- Tabs will be dynamically добавлены здесь -->
						</ul>
					</div>
				</div>
				<button class="scroll-btn right" id="scroll-right" style="display: none;">&#9654;</button>
				<div class="title">Kufner Web Analyser, v.3</div>
			</div>
			<label id="lun" style="display:none"><?php echo $ident;?></label>
		</div>

		<!-- OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK OUTLOOK -->
		<div id="outlook">
			<form id="formOutlook" method="post">
				<?php echo $outlookBar->Render();?>
			</form>
		</div>

		<div id="content">
			<!-- <h2>Content</h2> -->
			
			<!-- TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS -->
			<!--
			<form id="formTabs" method="post">
				<div id="tabs">
					<?php //echo $kts->Render();?> 
				</div>
			</form>
			-->

			<!-- REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT REPORT -->
			<form id="formGrid" method="post">
				<div id="reports">
					<div id="tabs">
						<ul>
						</ul>
	            </div>
				</div>
			</form>
		</div>

		<div id="footer">&copy; 2024, Kufner Holding GmbH. All rights reversed.</div>

		<!-- SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS SETTINGS -->
		<div id="setts">
			<div id="formSettings" class="modal">
				<form class="modal-content animate" method="post">
					<div class="container">

						<div id="company">
							<fieldset>
							<legend>Companies</legend>
								<?php echo $companies->Render();?>
							</fieldset>
						</div>

						<div id="opt">
							<fieldset>
								<legend>Options</legend>
								<div id="datum">
										<div style="display:grid; grid-template-columns: 1fr 1fr;">
											<fieldset>
												<legend>Date</legend>
												<div style="display:grid;grid-template-columns:1fr;grid-gap:1em;">
													<fieldset>
														<legend>Selected Date</legend>
														<div style="display: grid; grid-template-columns:2fr 1fr;grid-gap:5px;">

															<div style="justify-self:end;">
																<select id="selMonth">
																	<?php
																		foreach($arrMonths as $key => $value){
																			echo "<option " . ($value == $currMonth ? "selected='selected'" : '') . " value='" . $key . "'>$value</option>";
																		}
																	?>
																</select>
															</div>

															<div style="justify-self:start;">
																<select id="selYear">
																	<?php
																		foreach($arrYears as $item){
																			echo "<option " . ($item == $currYear ? "selected='selected'" : '') . " value='" . strtolower($item) . "'>$item</option>";
																		}
																	?>
																</select>
															</div>

														</div>
													</fieldset>	
													<fieldset>
														<legend>Period of Time</legend>
														<div style="display: grid; grid-template-columns:1fr 1fr; grid-gap:2px;">
															<div style="justify-self:end;">
																<p>From:</p>
															</div>

															<div style="justify-self:start;">
																<input type="date" value="<?php echo $startDate;?>" />
															</div>

															<div style="justify-self:end;">
																<p>To:</p>
															</div>

															<div style="justify-self:start;">
																<input type="date" value="<?php echo $endDate;?>" />
															</div>
															</div>
													</fieldset>	
												</div>
											</fieldset>	

											<fieldset>
												<legend>Time Mode</legend>
												<div style="display:grid;">
													<div class="radopt1">
														<input id="tmYTD" type="radio" margin-left="5px" name="timeMode" checked /><label for="radio1"> Year To Date</label><br fff/>        
													</div>

													<div class="radopt1">
														<input id="tmMTD" type="radio" name="timeMode" /><label for="radio2"> Month To Date</label><br />
													</div>

													<fieldset class="radopt1" style="padding-left: 10px;">
														<legend style="padding-top: 5px;">Monthly Breakdown</legend>
														<div class="radopt2">
															<input id="tmMBV" type="radio" name="timeMode" /><label for="radio3"> Value</label><br />
														</div>

														<div class="radopt2">
															<input id="tmMBQ" type="radio" name="timeMode" /><label for="radio4"> Quantity</label><br />
														</div>
													</fieldset>
												</div>
											</fieldset>

										</div>

									</div>


									<div id="options">
										<fieldset>
											<legend>others...</legend>

											<div id="opt1" style="display:grid;grid-template-columns:10fr 10fr 10fr 10fr 10frr 1fr;">
												<fieldset>
													<legend>Margin</legend>
													<div id="zk">
														<input id="zk01" type="radio" name="zk" checked/><label for="zk01"> ZK01</label><br />
														<input id="zk02" type="radio" name="zk"/><label for="zk02"> ZK02</label><br />
													</div>
												</fieldset>
												<fieldset>
													<legend>IC</legend>
													<div id="ic">
														<input id="allic" type="radio" name="ic" checked/><label for="allic"> All</label><br />
														<input id="exclic" type="radio" name="ic"/><label for="exclic"> Excl IC</label><br />
														<input id="onlyic" type="radio" name="ic"/><label for="onlyic"> Only IC</label><br />
													</div>
												</fieldset>
												<fieldset>
													<legend>TP</legend>
													<div id="tp">
														<input id="alltp" type="radio" name="tp" checked/><label for="alltp"> All</label><br />
														<input id="excltp" type="radio" name="tp"/><label for="excltp"> Excl TP</label><br />
														<input id="onlytp" type="radio" name="tp"/><label for="onlytp"> Only TP</label><br />
													</div>
												</fieldset>
												<fieldset style="grid-column: 4/5">
													<legend>Procurement</legend>
													<div id="besch">
														<input id="allbesch" type="radio" name="besch" checked/><label for="allbesch"> All</label><br />
														<input id="ownprod" type="radio" name="besch"/><label for="ownprod"> Own Production</label><br />
														<input id="merch" type="radio" name="besch"/><label for="merch"> Merchandise</label><br />
													</div>
												</fieldset>


												<fieldset style="grid-column: 1/3">
													<legend>Use prices from year</legend>
													<div style="text-align: right;">
														<select id="priceYear">
															<?php
																foreach($arrYears as $item){
																	echo "<option " . ($item == $currYear ? "selected='selected'" : '') . " value='" . strtolower($item) . "'>$item</option>";
																}
															?>
														</select>
													</div>
												</fieldset>

											</div>
										</fieldset>		
									</div>
							</fieldset>		
						</div>

						<div id="cancel" style="justify-self:end;">
							<button type="button" onclick="document.getElementById('formSettings').style.display='none'" class="cancelbtn">Cancel</button>
						</div>
						<div id="apply" style="justify-self:start;">
							<button type="button" onclick="getSettings()" class="applybtn">Apply</button>
							<img id="loading" src="img/loading.gif" alt="Loading..." />
						</div>

						<!--<span class="psw">Forgot <a href="#">password?</a></span>-->
					</div>
				</form>
			</div>
		</div>
   </div>
</body>
</html>

<!--<script src="js/index.js"></script>-->

<script>
	const einst = new Map();
	var level = 1;
	einst.set('level', level);

	function ReadQuery(sender,arg)
	{
		// Einstellungen: Selected Query //////////////////////////////////////////////////////////////////////////////////////////////
		level = 0;
		einst.set('queryID', arg.ItemId);
		//alert(einst.get("queryID"));

		alert("sender:" + sender);
		alert("arg.ItemId : " + arg.ItemId);

		var modal = document.getElementById("formSettings");
		modal.style.display = "block";
//		ksm.getItem(arg.ItemId).unselect();
	}
	// Register for OnSelect event
	ksm.registerEvent("OnSelect",ReadQuery);

	function getSettings() { // startReport_ORIGINAL
		document.getElementById("loading").style.visibility = "visible";

		var un = document.getElementById("lun");
		var d = new Date();
		var ident = un.innerHTML + "_" + d.getFullYear() + ("0" + (d.getMonth() + 1)).slice(-2) + ("0" + d.getDate()).slice(-2) + ("0" + d.getHours() ).slice(-2) + ("0" + d.getMinutes()).slice(-2) + ("0" + d.getSeconds()).slice(-2);

		einst.set('ident', ident);
		
		// Einstellungen: Selected Companies //////////////////////////////////////////////////////////////////////////////////////////////
		var selCompanies = "";
		var classCompany = document.getElementsByClassName("company");
		for(var index=0; index < classCompany.length; index++) {
			var companyID = classCompany[index].getAttribute("id");
			if (document.getElementById(companyID).checked) {
				companyID = companyID.substring(companyID.lastIndexOf("_") + 1);
				selCompanies = selCompanies + "," + companyID;
			}
		}
		selCompanies = selCompanies.substring(1);
		einst.set('selCompanies', selCompanies);
		//alert(einst.get("selCompanies"));

		// Einstellungen: Select Options //////////////////////////////////////////////////////////////////////////////////////////////
		const arrSelect = ["selMonth", "selYear", "priceYear"];
		for (var i = 0; i < arrSelect.length; i++) {
			var mySelect = document.getElementById(arrSelect[i]);
			for (var j = 0; j < mySelect.options.length; j++) {
				if (mySelect.options[j].selected) {
					einst.set(arrSelect[i], mySelect.options[j].value);
					break;
				}
			}
			//alert(einst.get(arrSelect[i]));
		}

		// Einstellungen: Radio Groups //////////////////////////////////////////////////////////////////////////////////////////////
		var arrRadioGroup = ["timeMode", "zk", "ic", "tp", "besch"];
		for (var i = 0; i < arrRadioGroup.length; i++) {
			var myRadioButton = document.getElementsByName(arrRadioGroup[i]);
			for (var j = 0; j < myRadioButton.length; j++) {
				einst.set(myRadioButton[j].id, (myRadioButton[j].checked ? "1" : "0"));
				//alert(myRadioButton[j].id + " = " + einst.get(myRadioButton[j].id))
			}
		}

		let jsonEinst = JSON.stringify(Object.fromEntries(einst));
		//koolajax.callback(createReport(jsonEinst),onDone);

		



		
		
		//koolajax.callback(setArrTabSet(newTabIndex, jsonEinst));


		


//alert("begin");
		addCollTab();
//alert("end");
		createNewTab();
		$('#tabs-' + newTabIndex).load('?grid_id=list' + newTabIndex + '&oper=ajaxload&ident=' + newTabIndex);
		newTabIndex++;

		var modal = document.getElementById("formSettings");
		modal.style.display = "none";
		document.getElementById("loading").style.visibility = "hidden";
	}

	// TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS TABS
	function remove(_id)
	{
		kts.removeTab(_id);
	}
	function m_over(_this)
	{
		_this.src='img/closeover.gif';
	}
	function m_out(_this)
	{
		_this.src='img/closenormal.gif';
	}		

	// COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES COMPANIES
	function toogle(nodeId)
	{
		if (nodeId.startsWith("company")) {
			var parentid = document.getElementById(nodeId).getAttribute("name");
			document.getElementById(parentid).checked = false;
			var classCompany = document.querySelectorAll("[name='" + parentid + "']")
			for(var index=0; index < classCompany.length; index++) {
				var companyID = classCompany[index].getAttribute("id");
				if (document.getElementById(companyID).checked)
					document.getElementById(parentid).checked = true;
			}
			toogle("root");
		} else if (nodeId.startsWith("area")) {
			var stateArea = document.getElementById(nodeId).checked;
			var classCompany = document.querySelectorAll("[name='" + nodeId + "']")
			for(var index=0; index < classCompany.length; index++) {
				var companyID = classCompany[index].getAttribute("id");
				document.getElementById(companyID).checked = false;
				if (stateArea)
					document.getElementById(companyID).checked = true;
				toogle("root");
			}
		} else if (nodeId.startsWith("treeview")) {
			// click on root: on or off
			var stateRoot = document.getElementById(nodeId).checked;
			var classArea = document.getElementsByClassName("area");
			for(var index=0; index < classArea.length; index++) {
				var areaID = classArea[index].getAttribute("id");
				document.getElementById(areaID).checked = false;
				if (stateRoot)
					document.getElementById(areaID).checked = true;
				toogle(areaID);
			}
		} else if (nodeId.startsWith("root")) {
			var classArea = document.getElementsByClassName("area");
			document.getElementById("treeview.root").checked = false;
			for(var index=0; index < classArea.length; index++) {
				var areaID = classArea[index].getAttribute("id");
				if (document.getElementById(areaID).checked)
					document.getElementById("treeview.root").checked = true;
			}
		}
	}

	//$(document).ready(function() {
   	$("#tabs").tabs({
      	activate: TabSelected
    	});

   	// Идентификатор активного таба
		var newTabIndex = 1;
   	var tabActive = "tabs-" + newTabIndex;

		function createNewTab() {
			var newTabId = "tabs-" + newTabIndex;
			var queryCaption = "Custom Caption " + newTabIndex;

			var newDiv = $('<div id="' + newTabId + '" class="tab-content">Content for Tab ' + newTabIndex + '</div>');

			$("body").append(newDiv);

			$("#tabs ul").append('<li><a href="#' + newTabId + '" data-caption="' + queryCaption + '">Tab ' + newTabIndex + '<button class="close-tab" title="Close Tab">-</button></a></li>');

			$("#tabs").append(newDiv);

			$("#tabs").tabs("refresh");

			// Устанавливаем новый таб активным
			$('a[href="#' + newTabId + '"]').click();
		}

		$("#tabs").on("click", ".close-tab", function() {
			var panelId = $(this).closest("li").remove().attr("aria-controls");
			$("#" + panelId).remove();

			if (panelId === tabActive) {
				var nextTab = $('a[href="#' + tabActive + '"]').closest("li").next().find("a");
				var prevTab = $('a[href="#' + tabActive + '"]').closest("li").prev().find("a");

				if (prevTab.length > 0) {
						prevTab.click();
				} else if (nextTab.length > 0) {
						nextTab.click();
				}
			} else {
				$('a[href="#' + tabActive + '"]').click();
			}
			$("#tabs").tabs("refresh");
		});
		
		$("#tabs").on("mouseenter", "a", function() {
			var caption = $(this).data("caption");
			if (caption) {
				$(this).attr("title", caption);
			}
		});

		// Функция вызывается при смене активной вкладки
		function TabSelected(event, ui) {
			if (!$(event.originalEvent.target).hasClass("close-tab")) {
				// Событие не вызвано кликом на кнопке закрытия
				// Обновляем переменную tabActive только если это не кнопка закрытия
				tabActive = ui.newPanel.attr("id");
			}
		}
	//});

	function onDone(s)
	{
	}

//$(function () {
  let tabCount = 0;

  $("#tabsColl").tabs();


	function addCollTab() {
		//alert("1");
		tabCount++;
		//alert("2");
		const newTab = `<li><a href="#tab-${tabCount}">Collection ${tabCount}</a><span class="close-tab">&#10754;</span></li>`;
		//alert("3");
		$("#tabsColl ul").append(newTab);
		$("#tabsColl").tabs("refresh"); // Обновление вкладок после добавления
		checkOverflow();
	}

/*
  $("#add-tab").on("click", function () {
    tabCount++;
    const newTab = `<li><a href="#tab-${tabCount}">Collection ${tabCount}</a><span class="close-tab">&#10754;</span></li>`;
    $("#tabsColl ul").append(newTab);
    $("#tabsColl").tabs("refresh"); // Обновление вкладок после добавления
    checkOverflow();
  });
*/

  // Обработчик клика на табы
  $(document).on("click", "#tabsColl ul li a", function (event) {
    const tabId = $(this).attr("href");
    // Устанавливаем активную вкладку
    const index = $(this).parent().index(); // Определяем индекс вкладки
    $("#tabsColl").tabs("option", "active", index); // Устанавливаем активную вкладку
    //alert(`Событие: click на таб, ID: ${tabId}`);
  });

  $(document).on("click", ".close-tab", function (event) {
    event.preventDefault(); // Предотвращает переход по ссылке
    const tabId = $(this).prev("a").attr("href");
    //alert(`Событие: click на кнопку закрытия, ID: ${tabId}`);
    $(this).closest("li").remove(); // Удаление таба
    //$(tabId).remove(); // Удаление содержимого таба // ЗДЕСЬ НАДО УДАЛИТЬ ВСЕ ТАБЫ ОТ KoolPHP.net
    $("#tabsColl").tabs("refresh");
    checkOverflow();
  });

  function checkOverflow() {
    const wrapper = $("#tabsColl-wrapper")[0];
    const tabs = $("#tabsColl")[0];
    if (tabs.scrollWidth > wrapper.clientWidth) {
      $("#scroll-left, #scroll-right").show();
    } else {
      $("#scroll-left, #scroll-right").hide();
    }
  }

  $("#scroll-left").on("click", function () {
    $("#tabsColl").animate({ scrollLeft: "-=100" }, 200);
  });

  $("#scroll-right").on("click", function () {
    $("#tabsColl").animate({ scrollLeft: "+=100" }, 200);
  });

  $(window).on("resize", checkOverflow);
</script>