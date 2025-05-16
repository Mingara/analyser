<?php

// include db config
// include and create object
include("../lib/inc/jqgrid_dist.php");
include("classes/ajax.php");

// master grid
// Database config file to be passed in phpgrid constructor
define("PHPGRID_DBTYPE","mssqlnative"); 
define("PHPGRID_DBHOST","10.1.10.44");
define("PHPGRID_DBUSER","analyst");
define("PHPGRID_DBPASS","SevKam1299!");
define("PHPGRID_DBNAME","analyser");
$db_conf = array(
    "type" 		=> PHPGRID_DBTYPE,
    "server" 	=> PHPGRID_DBHOST,
    "user" 		=> PHPGRID_DBUSER,
    "password" 	=> PHPGRID_DBPASS,
    "database" 	=> PHPGRID_DBNAME
);

$grid = new jqgrid($db_conf);

$opt["caption"] = "Clients Data";
$opt["height"] = "150";
$opt["multiselect"] = true;

// keep multiselect only by checkbox, otherwise single selection
$opt["multiboxonly"] = true;

// disable detail grid import if client_id 5 is selected
$opt["onSelectRow"] = "function(rid){
    var rowdata = $('#list1').getRowData(rid);
    //$('#detail_div').load('?grid_id=list2&oper=ajaxload&CountryID='+rowdata['CountryID']);
    $('#detail_div').load('?grid_id=list2&oper=ajaxload&ident=202402190855365');
}";

$grid->set_options($opt);
$grid->select_command = "select * FROM analyser..Country";
$grid->table = "analyser..Country";

$out_master = $grid->render("list1");

// detail grid

if (!empty($_GET["ident"]))
{
    getReport();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<link rel="stylesheet" type="text/css" media="screen" href="../lib/js/themes/redmond/jquery-ui.custom.css"></link>
    <link rel="stylesheet" type="text/css" media="screen" href="../lib/js/jqgrid/css/ui.jqgrid.css"></link>
    <script src="../lib/js/jquery.min.js" type="text/javascript"></script>
	<script src="../lib/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
    <script src="../lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script src="../lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
	</head>
<body>
	
    <div style="margin:10px">
        <div>   
            <?php echo $out_master ?>
        </div>   
        <br>
    	<div id="detail_div">
            <?php echo $out_detail; ?>
        </div>
    </div>

</body>
</html>
