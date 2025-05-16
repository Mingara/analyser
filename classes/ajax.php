<?php
   function getReport() {
      $grid = new jqgrid($db_conf);
      
      $opt = array();
      
      $opt["height"] = ""; // autofit height of subgrid
      $opt["caption"] = "Invoice Data"; // caption of grid
      $opt["multiselect"] = true; // allow you to multi-select through checkboxes
      
      $grid->set_options($opt);
      
      $grid->select_command = "SELECT * FROM analyser..Customers WHERE CountryID = 3";
      //$grid->table = "analyst..ResultSet";
      
      // generate grid output, with unique grid name as 'list1'
      $out_detail = $grid->render("list2");
   }
?>
