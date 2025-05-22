<?php
include("../lib/inc/jqgrid_dist.php");

// Database config file to be passed in phpgrid constructor
$db_conf = array(
					"type" 		=> "mssqlnative",
					"server" 	=> "HOMEOFFICE\HOMESQLSERVER",
					"user" 		=> "analyst",
					"password" 	=> "SevKam1299!",
					"database" 	=> "analyser"
				);



$g = new jqgrid($db_conf);

$grid["caption"] = "Sample Grid";
$grid["loadComplete"] = "function(){ onload(); }";
$g->set_options($grid);
$g->table = "Article";

$out = $g->render("list1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="../../lib/js/themes/redmond/jquery-ui.custom.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="../../lib/js/jqgrid/css/ui.jqgrid.css" />

	<script src="../../lib/js/jquery.min.js" type="text/javascript"></script>
	<script src="../../lib/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script src="../../lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	<script src="../../lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>

</head>
<body>
	<div>
	<?php echo $out?>
	</div>
	<script>
	function onload()
	{
		var grid_id = "list1";
		$.contextMenu({
			selector: '#gbox_' + grid_id + ' .jqgrow',
			appendTo: '.ui-jqgrid',
			callback: function(key, options) {

				var row = jQuery("#"+grid_id).getGridParam("selrow");
				var gender = jQuery('#'+grid_id).jqGrid('getCell', row, 'gender');

				var m = "Row:"+ row +", Gender:"+gender+", Clicked:" + key;
				window.console && console.log(m) || alert(m); 
       		},
			items: {
				"edit": {"name": "Edit", "icon": "edit"},
				"cut": {"name": "Cut", "icon": "cut"},
				"sep1": "---------",
				"quit": {"name": "Quit", "icon": "quit"},
				"sep2": "---------",
				"fold1": {
					"name": "Sub group", 
					"items": {
						"fold1-key1": {"name": "Foo bar"},
						"fold2": {
							"name": "Sub group 2", 
							"items": {
								"fold2-key1": {"name": "alpha"},
								"fold2-key2": {"name": "bravo"},
								"fold2-key3": {"name": "charlie"}
							}
						},
						"fold1-key3": {"name": "delta"}
					}
				},
				"fold1a": {
					"name": "Other group", 
					"items": {
						"fold1a-key1": {"name": "echo"},
						"fold1a-key2": {"name": "foxtrot"},
						"fold1a-key3": {"name": "golf"}
					}
				}
			}
		});
	}
	</script>
</body>
</html>
