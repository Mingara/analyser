	<div id="gridtabs">
		<ul>
			<li><a href="#tabs-1">Grid1</a></li>
			<li><a href="#tabs-2">Grid2</a></li>
			<li><a href="#tabs-3">Grid3</a></li>
			<li><a href="#tabs-4">Grid4</a></li>
			<li><a href="#tabs-5">Grid5</a></li>
			<li><a href="#tabs-6">Grid6</a></li>
			<li><a href="#tabs-7">Grid7</a></li>
			<li><a href="#tabs-8">Grid8</a></li>
			<li><a href="#tabs-9">Grid9</a></li>
			<li><a href="#tabs-10">Grid10</a></li>
			<li><a href="#tabs-11">Grid11</a></li>
			<li><a href="#tabs-12">Grid12</a></li>
			<li><a href="#tabs-13">Grid13</a></li>
			<li><a href="#tabs-14">Grid14</a></li>
			<li><a href="#tabs-15">Grid15</a></li>
			<li><a href="#tabs-16">Grid16</a></li>
			<li><a href="#tabs-17">Grid17</a></li>
			<li><a href="#tabs-18">Grid18</a></li>
			<li><a href="#tabs-19">Grid19</a></li>
			<li><a href="#tabs-20">Grid20</a></li>
			<!--
			<li><a href="#tabs-2" onclick="setTimeout('phpgrid_list2.fx_grid_resize();',20); jQuery('#gview_list2 .ui-jqgrid-titlebar-close span.ui-icon-circle-triangle-s').click();">Grid Secondary</a></li>
			<li><a href="#tabs-3" onclick="setTimeout('phpgrid_list3.fx_grid_resize(); phpgrid_list4.fx_grid_resize()',20);">Another Grid</a></li>
			-->
		</ul>

		<div id="tabs-1">
			<?php echo $report_1 ?>
		</div>

		<div id="tabs-2">
			<?php echo $report_2 ?>
		</div>

		<div id="tabs-3">
			<?php echo $report_3 ?>
		</div>

		<div id="tabs-4">
			<?php echo $report_4 ?>
		</div>

		<div id="tabs-5">
			<?php echo $report_5 ?>
		</div>

		<div id="tabs-6">
			<?php echo $report_6 ?>
		</div>

		<div id="tabs-7">
			<?php echo $report_7 ?>
		</div>

		<div id="tabs-8">
			<?php echo $report_8 ?>
		</div>

		<div id="tabs-9">
			<?php echo $report_9 ?>
		</div>

		<div id="tabs-10">
			<?php echo $report_10 ?>
		</div>

		<div id="tabs-11">
			<?php echo $report_11 ?>
		</div>

		<div id="tabs-12">
			<?php echo $report_12 ?>
		</div>

		<div id="tabs-13">
			<?php echo $report_13 ?>
		</div>

		<div id="tabs-14">
			<?php echo $report_14 ?>
		</div>

		<div id="tabs-15">
			<?php echo $report_15 ?>
		</div>

		<div id="tabs-16">
			<?php echo $report_16 ?>
		</div>

		<div id="tabs-17">
			<?php echo $report_17 ?>
		</div>

		<div id="tabs-18">
			<?php echo $report_18 ?>
		</div>

		<div id="tabs-19">
			<?php echo $report_19 ?>
		</div>

		<div id="tabs-20">
			<?php echo $report_20 ?>
		</div>
	</div>

	<style type="text/css">
	.grid_groups ol {
		padding: 7px 0;
		margin: 10px 0 5px 0;
		box-shadow: 0px 0px 2px #ccc;
	}
	.grid_groups .ui-sortable li {
		cursor: hand;
		cursor: pointer;
	}		
	
	.grid_groups li, .dragable { 
		background-color: #f4f4f4;
		display: inline-block;
		padding:5px 15px;
		margin-left:10px;
		font-family: tahoma,verdana,"sans serif";
		font-size: 13px;		
	}
	.grid_groups .ui-icon.ui-icon-close{
		display: inline-block;
		top: 1px;
		position: relative;
		left: -3px;
		cursor: pointer;
		font-size: 12px;		
	}
	</style>	

		<script>
			jQuery(function() {
				jQuery("#gridtabs").tabs();
			});
		/*
			let element = document.getElementById("gridtabs");
			let hidden = element.getAttribute("hidden");
			element.setAttribute("hidden", "hidden");
		*/

		jQuery(window).load(function($){
			var customFormatDisplayField = function (displayValue, value, colModel, index, grp) {

				// show label instead of ids for select formatter
				if (colModel.formatter == 'select')
					displayValue = jQuery.fn.fmatter.select(displayValue,{'colModel':colModel});

				return displayValue;
			},
			generateGroupingOptions = function (groupingCount) {
				var i, arr = [];
				for (i = 0; i < groupingCount; i++) {
					arr.push(customFormatDisplayField);
				}
				return {
					formatDisplayField: arr
				}
			},
			getArrayOfNamesOfGroupingColumns = function (grpname) {
				return jQuery("."+grpname+" ol li:not(.placeholder)")
					.map(function() {
						return jQuery(this).attr("data-column");
					}).get()
			};

			initGroupDragDrop = function(gridid,groupid) {
				var $grid = jQuery("#"+gridid);

				jQuery("#gbox_"+gridid+" tr.ui-jqgrid-labels th div").draggable({
					appendTo: "body",
					helper: function( event ) {
						return jQuery( "<div class='dragable'>"+jQuery(this).html()+"</div>" );
					}
				});

				jQuery("."+groupid+" ol").droppable({
					activeClass: "ui-state-default",
					hoverClass: "ui-state-hover",
					accept: ":not(.ui-sortable-helper)",
					drop: function(event, ui) {
						var $this = jQuery(this), groupingNames;
						$this.find(".placeholder").remove();
						var groupingColumn = jQuery("<li></li>").attr("data-column", ui.draggable.attr("id").replace("jqgh_" + $grid[0].id + "_", ""));
						jQuery("<span class='ui-icon ui-icon-close'></span>").click(function() {
							var namesOfGroupingColumns;
							jQuery(this).parent().remove();
							$grid.jqGrid("groupingRemove");
							namesOfGroupingColumns = getArrayOfNamesOfGroupingColumns(groupid);
							$grid.jqGrid("groupingGroupBy", namesOfGroupingColumns);
							if (namesOfGroupingColumns.length === 0) {
								jQuery("<li class='placeholder'>Drop column headers here to group by that column</li>").appendTo($this);
							}
						}).appendTo(groupingColumn);
						groupingColumn.append(ui.draggable.text());
						groupingColumn.appendTo($this);
						$grid.jqGrid("groupingRemove");
						groupingNames = getArrayOfNamesOfGroupingColumns(groupid);
						$grid.jqGrid("groupingGroupBy", groupingNames, generateGroupingOptions(groupingNames.length));
						jQuery(".chngroup").val("clear");
					}
				}).sortable({
						items: "li:not(.placeholder)",
						sort: function() {
							jQuery( this ).removeClass("ui-state-default");
						},
						stop: function() {
							var groupingNames = getArrayOfNamesOfGroupingColumns(groupid);
							$grid.jqGrid("groupingRemove");
							$grid.jqGrid("groupingGroupBy", groupingNames, generateGroupingOptions(groupingNames.length));
						}
				});

			}

			setTimeout(()=>{
				initGroupDragDrop('list1','group1');
				initGroupDragDrop('list2','group2');
				initGroupDragDrop('list3','group3');
			},200);

		});
	</script>
