<?php

class OutlookBar {

    public $ksm = null;
    private $conn = null;
    public $topicProperties = array();

    public function __construct($conn) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = $conn;
        $this->getOutlookBar();
    }

    public function getOutlookBar() {

        $KoolControlsFolder="../KoolControls";
        require $KoolControlsFolder."/KoolSlideMenu/koolslidemenu.php";
        $this->ksm = new KoolSlideMenu("ksm");
        $this->ksm->scriptFolder = $KoolControlsFolder."/KoolSlideMenu";	

        $sql = "SELECT DISTINCT aHelp.CatName, aCat.ButtonOrder " . 
            "FROM analyser..aCat aCat INNER JOIN analyser..aHelp aHelp ON aCat.ButtonOrder = aHelp.ButtonOrder " .
            "WHERE aCat.SubItemOrder = 1 AND aCat.ButtonOrder IN (" . $_SESSION['user_rights']['CatalogList'] . ") " .
            "ORDER BY aCat.ButtonOrder";
        $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false ) {
            die( print_r( sqlsrv_errors(), true));
        }
        $collapse = true;
        while($storename = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $buttonOrder = "P".$storename['ButtonOrder'];
            $this->ksm->addParent("root", $buttonOrder, $storename['CatName'],null, $collapse);
            $this->addTab($storename['CatName'], $buttonOrder);
            $collapse = false;
        }

        $_SESSION['topicProperties'] = $this->topicProperties;

        $this->ksm->singleExpand = true;
        $this->ksm->width="195px";
    
        $this->ksm->styleFolder = $KoolControlsFolder."KoolSlideMenu/styles/office2007"; // violet hay office2007
        //$this->ksm.registerEvent("OnSelect",OpenSettings);
        // void registerEvent(string eventName, function handleEvent)
        //$this->ksm->registerEvent("OnSelect",true);
        return $this->ksm;
    }

    function addTab($catName, $catID) {

        $sql = "SELECT aCat.ButtonOrder, aHelp.CatName, aCat.ItemOrder, aCat.SubItemOrder, aCat.Caption, aCat.Sundry, " .
            "REPLACE(STR(aCat.ButtonOrder, 3) + STR(aCat.ItemOrder, 3) + STR(aCat.SubItemOrder, 2), ' ', '0') AS myKey, mMask.Mask AS myMask " .
            "FROM analyser..aCat aCat INNER JOIN analyser..aHelp aHelp ON aCat.ButtonOrder = aHelp.ButtonOrder INNER JOIN " .
            "analyser..SetsFrameCat cMask ON cMask.CatalogID = aCat.CatalogID INNER JOIN analyser..SetsFrameMask mMask ON cMask.MaskID = mMask.id " .
            "WHERE aCat.SubItemOrder = 1 AND aCat.CatalogId NOT IN " .
            "(SELECT CatalogId FROM analyser..BlockedQueries WHERE UserID = " .
            $_SESSION['user_rights']['UserID'] . " AND CatTab = 1) AND aHelp.CatName = '" . $catName . "' " .
            "ORDER BY aCat.ButtonOrder, aCat.ItemOrder, aCat.Caption DESC";
        $stmt = sqlsrv_query( $this->conn, $sql);

        if( $stmt === false ) {
            die( print_r( sqlsrv_errors(), true));
        }

        $arrProperties = array();
        
        $ind = 1;
        while($storename = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $arrProperties["key"] = $storename["myKey"];
            $arrProperties["index"] = $ind;
            $arrProperties["buttonOrder"] = $storename["ButtonOrder"];
            $arrProperties["catName"] = $catName;
            $arrProperties["itemOrder"] = $storename["ItemOrder"];
            $arrProperties["subItemOrder"] = $storename["SubItemOrder"];
            $arrProperties["caption"] = "<html><center>" . str_replace(" ", "<br>", $storename["Caption"]) . "</center></html>";
            $arrProperties["tag"] = $storename["Sundry"];
            $arrProperties["mask"] = $storename["myMask"];

            $this->topicProperties[$storename["myKey"]] = $arrProperties;
            $this->ksm->addChild($catID, $storename["myKey"], $storename["Caption"]);
            $ind++;
        }

    }
    public function Render() {
        return $this->ksm->Render();
    }
}
?>
