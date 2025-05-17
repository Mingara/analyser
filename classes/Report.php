<?php
class Report
{
    private $conn;
    private $db_conf;
    private $report;
    private $arrCurrQuery;
    private $arrSettingsVariable;

    public function __construct($db_conf, $conn)
    {
        $this->conn = $conn;
        $this->db_conf = $db_conf;
        $this->initializeCurrQuery();
        $this->initializeSettingsVariable();

        //$this->report = new jqgrid($this->db_conf);

        $this->setReportOptions();
    }

    private function initializeCurrQuery()
    {
        $keysCurrQuery = array('CatalogID', 'Record', 'ConsRecord', 'Tag');
        $this->arrCurrQuery = array_fill_keys($keysCurrQuery, null);
    }

    private function initializeSettingsVariable()
    {
        $keysSettingsVariable = array(
            'm_Index', 'm_Monthly', 'm_PriceDev', 'm_BudComp', 'm_Receivables', 'm_Budget', 'm_Inventory',
            'm_Production', 'm_Logistic', 'm_Winding', 'm_WorkSteps', 'm_OrdersEA', 'm_Shipping', 'm_Workflow',
            'm_OwnProd', 'm_Merchandise', 'm_Prognose', 'm_PP', 'm_DateFrom', 'm_DateTo', 'm_WIA',
            'm_FrameTag', 'm_SettingsYearPP', 'm_SettingsYear', 'm_SettingsMonth'
        );
        $this->arrSettingsVariable = array_fill_keys($keysSettingsVariable, null);
    }

    private function setReportOptions()
    {
        $this->report = new jqgrid($this->db_conf);
        $opt = array();
        $opt["caption"] = "Invoice Data"; // caption of Report
        $opt["loadtext"] = "Loading...";
        $opt["toolbar"] = "top";

        $opt["actionicon"] = false;
        $opt["tooltip"] = true;
        $opt["altRows"] = false;
        $opt["multiSort"] = false;
        $opt["height"] = ""; // autofit height of subReport
        $opt["multiselect"] = false; // allow you to multi-select through checkboxes
        $opt["readonly"] = true;
        $opt["resizable"] = false;
        $opt["autoresize"] = false;

        $this->report->set_options($opt);
    }

    public function processRequest($level)
    {
        switch ($level) {
            case 0:
                $this->getCat();
                break;
            case 1:
                $this->getCatPM();
                break;
        }
    }

    private function getCat()
    {
        // Add your implementation here
    }

    private function getCatPM()
    {
        // Add your implementation here
    }

    public function executeQuery($sql)
    {
        $stmt = sqlsrv_query($this->conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $this->arrCurrQuery['CatalogID'] = $row['CatalogID'];
            $this->arrCurrQuery['Record'] = $row['Record'];
            $this->arrCurrQuery['ConsRecord'] = $row['ConsRecord'];
            $this->arrCurrQuery['Tag'] = '03002010';
        }
    }

    public function render($ident)
    {
        // Рендеринг jqreport
        echo $this->report->render("list".$ident);
    }
}