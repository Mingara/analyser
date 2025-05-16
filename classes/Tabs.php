<?php

class Tabs {
    public $kts = null;

    public function getTabs() {

        $KoolControlsFolder="../KoolControls";
        require $KoolControlsFolder."/KoolTabs/kooltabs.php";

        $this->kts = new KoolTabs("kts");
        $this->kts->scriptFolder = $KoolControlsFolder."/KoolTabs";
        $this->kts->styleFolder = "silver";

        $html = "<img class='btnclose' onmouseover='m_over(this)' onmouseout='m_out(this)' onclick=\"remove('2020')\" src='img/closenormal.gif' />Sales->ArtGrp";
        $this->kts->addTab("root","2020",$html,null,true,true,"120px");
/*
        $this->kts->addTab("2020","level0","Sales->ArtGrp",null,true);	
        $this->kts->addTab("2020","level1","Sales->ArtGrp->ArtFam",null,true);	
        $this->kts->addTab("2020","level2","Sales->ArtGrp->ArtFam->Article",null,true);	
        $this->kts->addTab("2020","level3","Sales->ArtGrp->Invoice",null,true);	
*/

        $html = "<img class='btnclose' onmouseover='m_over(this)' onmouseout='m_out(this)' onclick=\"remove('4020')\" src='img/closenormal.gif' />Inventory->Product Type";
        $this->kts->addTab("root","4020",$html,null,null,true,"180px");
/*
        $this->kts->addTab("4020","level0","Inventory->Product Type",null,true);	
*/
        return $this->kts;
    }

}
?>
