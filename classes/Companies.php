<?php
// https://demo.koolphp.net/Examples/KoolTreeView/Advances/TreeView_With_CheckBox/index.php

class Companies {
	private $companies = null;
	private $arrCompanies = array();

	public function getCompanies() {
		global $conn;

		$KoolControlsFolder = "../KoolControls";
		require $KoolControlsFolder."/KoolTreeView/kooltreeview.php";
        $this->companies = new KoolTreeView("ktv");
        //$this->companies->scriptFolder = $KoolControlsFolder."/KoolTreeView";	
		$this->companies->imageFolder="img/"; //$KoolControlsFolder."/KoolTreeView/icons";

		$sql = "SELECT DISTINCT 'KUFNER' AS Companies, TreeArea.Area, TreeArea.TreeAreaID " .
			"FROM analyser..Company Company INNER JOIN " .
			"analyser..Area TreeArea ON Company.TreeAreaID = TreeArea.TreeAreaID " .
			"WHERE Company.CCCONO = '100' AND Company.CompanyID IN (" . $_SESSION['user_rights']['CompList'] . ") AND Company.bisYear = '' " .
			"ORDER BY TreeArea.TreeAreaID";
		$stmt = sqlsrv_query( $conn, $sql);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}

		// $this->arrCompanies[] = array(parentID, nodeID, labelText, className, true, gif);
		while($storename = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$currArea = $storename['Area'];
			$nodeID = strtolower(str_replace(" ","_",$currArea));
			$this->arrCompanies[] = array("root", $nodeID, " ".$currArea, "area", true, $nodeID.".gif");
			$this->addCompany($nodeID, $currArea);
		}

		$_node_template = "<input type='checkbox' id='{id}' class='{class}' name='{name}' {check} onclick='toogle(\"{id}\")'><label for='{id}'>{text}</label>";
	
		//$this->companies->scriptFolder = $KoolControlsFolder."/KoolTreeView";
		//$this->companies->imageFolder="/img/pics"; //$KoolControlsFolder."/KoolTreeView/icons";
		$this->companies->styleFolder = "hay";
		$root = $this->companies->getRootNode();
	
		$root->text = str_replace("{id}","treeview.root",$_node_template);
		$root->text = str_replace("{class}","classroot",$root->text);
		$root->text = str_replace("{name}","treeview_root",$root->text);
		$root->text = str_replace("{text}"," KUFNER",$root->text);
		$root->text = str_replace("{check}","checked",$root->text);
		$root->expand=true;
		$root->image="kufner.gif";	

		for ( $i = 0 ; $i < sizeof($this->arrCompanies) ; $i++)
		{

			// $this->arrCompanies[] = array(parentID, nodeID, labelText, className, true, gif);
			// <input type="checkbox" id="area_production_europe" name="production_europe" class="area" checked="" onclick="toogle("nodearea_production_europe")">
			// <input type="checkbox" id="company_kpl_20" name="kpl_20" class="company" checked="" onclick="toogle("nodecompany_kpl_20")">
			// id = class + name
			$_text = str_replace("{id}",$this->arrCompanies[$i][3] . "_" . $this->arrCompanies[$i][1],$_node_template);
			$_text = str_replace("{class}",$this->arrCompanies[$i][3],$_text);
			$_text = str_replace("{name}","area_".$this->arrCompanies[$i][0],$_text);
			$_text = str_replace("{text}",$this->arrCompanies[$i][2],$_text);
			$_text = str_replace("{check}","checked",$_text);

			$this->companies->Add($this->arrCompanies[$i][0], $this->arrCompanies[$i][1], $_text, $this->arrCompanies[$i][4], $this->arrCompanies[$i][5]);

		// arr | parentID, nodeID, labelText, checked, gif
		//$this->arrCompanies[] = array($parent, $nodeID, $companyLabel, "company", true, $storename['Country'] . ".gif");
		// add | parentID, nodeID, $_node_template, "checked", gif

		/*
		[0]=> string(17) "production_europe" 
		[1]=> string(5) "sv_49" 
		[2]=> string(25) " SV - Schwaben-Vlies GmbH" 
		[3]=> string(7) "company" 
		[4]=> bool(true) 
		[5]=> string(11) "Germany.gif" } 
		*/

		}

		$this->companies->showLines = true;
		$this->companies->selectEnable = false;
		//$this->companies->keepState = "onpage";	

		//return $this->companies;
		return array($this->companies, $this->arrCompanies);
	}

	function addCompany($parent, $Area) {
		global $conn;
		$sql = "SELECT 'KUFNER' AS Companies, Company.CCCONO, LTRIM(RTRIM(STR(Company.CompanyID))) AS CompanyID, " .
			"Company.CompanyCode, Company.Company, Company.CCDIVI, Country.Country, Company.abYear, Company.bisYear " .
			"FROM analyser..Company Company INNER JOIN " .
			"analyser..Country Country ON Company.LocationID = Country.CountryID " .
			"INNER JOIN analyser..Area Area ON Country.AreaID = Area.AreaID " .
			"INNER JOIN analyser..Area TreeArea ON Company.TreeAreaID = TreeArea.TreeAreaID " .
			"WHERE Company.CCCONO = '100' AND Company.CompanyID IN (" . $_SESSION['user_rights']['CompList'] . ") AND Company.bisYear = '' " .
			"AND TreeArea.Area = '" . $Area . "' " .
			"ORDER BY Company.CCDIVI";
		$stmt = sqlsrv_query( $conn, $sql);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}

		// $this->arrCompanies[] = array("root", $nodeID, " ".$currArea, "area", true, $nodeID.".gif");
		// $this->arrCompanies[] = array(parentID, nodeID, labelText, className, true, gif);

		while($storename = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$nodeID = strtolower($storename['CompanyCode'] . "_" . $storename['CompanyID']);
			$companyLabel = " " . $storename['CompanyCode'] . " - " . $storename['Company'];
			$this->arrCompanies[] = array($parent, $nodeID, $companyLabel, "company", true, $storename['Country'] . ".gif");
		}
	}
}
?>
 
