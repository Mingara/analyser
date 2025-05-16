<?php
	require $KoolControlsFolder."/KoolComboBox/koolcombobox.php";
    $startSAP = "2018";
    
	$selected_month_id = date("m");
	$kcb_month = new KoolComboBox("kcb_month");
	$kcb_month->scriptFolder = $KoolControlsFolder."/KoolComboBox";
	$kcb_month->styleFolder="outlook";
	$kcb_month->width = "100px";

    $myMonths = array(
        "01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", 
        "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December"
    );
    foreach ($myMonths as $key => $value) {
        $kcb_month->addItem(" ".$value,$key,null,($selected_month_id==$key));
    }

 	$selected_year_id = date("Y");
	$kcb_year = new KoolComboBox("kcb_year");
	$kcb_year->scriptFolder = $KoolControlsFolder."/KoolComboBox";
	$kcb_year->styleFolder="outlook";
	$kcb_year->width = "60px";

    $sql = "SELECT the_year FROM analyser..the_year WHERE the_year >= ". $startSAP . " AND the_year <= STR(DATEPART(yyyy,DATEADD(year, +1, GETDATE())),4) ORDER BY the_year";
    $result = sqlsrv_query( $conn, $sql);
	while($row= sqlsrv_fetch_array($result))
	{
        $kcb_year->addItem(" ".$row["the_year"],$row["the_year"],null,($selected_year_id==$row["the_year"]));
	}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<form id="form1" method="post">
		<fieldset style="width:500px;">
			<legend>Date/Time</legend>
			<div style="display:grid;grid-template-columns:1fr 1fr;grid-gap:1em;">
				<fieldset style="width:200px;">
					<legend>Date</legend>
					<div style="display:grid;grid-template-columns:1fr;grid-gap:1em;">
						<fieldset style="width:200px;">
							<legend>Selected Date</legend>
							<div style="display: grid; grid-template-columns:1fr 1fr;grid-gap:5px;">
								<div style="justify-self:end;">
									<?php echo $kcb_year->Render();?>
								</div>

								<div style="justify-self:start;">
									<?php echo $kcb_month->Render();?>
								</div>
							</div>
						</fieldset>	
						<fieldset style="width:200px;">
							<legend>Period of Time</legend>
							<div style="display: grid; grid-template-columns:1fr 1fr;grid-gap:5px;">
								<div style="justify-self:end;">
									From:
								</div>

								<div style="justify-self:start;">
									<?php echo $kcb_month->Render();?>
								</div>


								<div style="justify-self:end;">
									To:
								</div>

								<div style="justify-self:start;">
									<?php echo $kcb_month->Render();?>
								</div>
								</div>
						</fieldset>	
					</div>
				</fieldset>	
				<fieldset style="width:200px;">
					<legend>Time Mode</legend>
					<div style="display:grid;grid-row:1/4;grid-gap:1em;">

						<div>
							<input id="radio1" type="radio" name="age" checked /><label for="radio1">Year To Date</label><br />        
						</div>

						<div>
							<input id="radio2" type="radio" name="age" /><label for="radio2">Month To Date</label><br />
						</div>

						<fieldset style="width:200px;">
							<legend>Monthly Breakdown</legend>

							<div>
								<input id="radio3" type="radio" name="age" /><label for="radio3">Quantity</label><br />
							</div>

							<div>
								<input id="radio4" type="radio" name="age" /><label for="radio4">Value</label><br />
							</div>
						</fieldset>

					</div>
				</fieldset>
			</div>
		</fieldset>	
	</form>
</body>
</html>
