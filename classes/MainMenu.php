<?php

class MainMenu {

    public $km = null;

    public function getMainMenu() {
    
        $KoolControlsFolder = "../KoolControls";
        require $KoolControlsFolder."/KoolMenu/koolmenu.php";
        $this->km = new KoolMenu("km");
        $this->km->scriptFolder = $KoolControlsFolder."/KoolMenu";
        $this->km->styleFolder="default";
    
        //$this->km->Add(string $parent_id,string $id,string $text,[[string $href,[string $image_src]]]);
    
        $this->km->Add("root","file","File");
        $this->km->Add("file","export","Export To Excel","javascript:alert(\"Export\")","img/excel.png");
        $this->km->Add("file","print","Print","javascript:alert(\"Print\")","img/printer.png");
        $this->km->Add("file","search","Search","javascript:alert(\"Search\")","img/search.png");
        $this->km->AddSeparator("file");
        $this->km->Add("file","logoff","Logoff","javascript:alert(\"Logoff\")","img/logoff.png");

        $this->km->Add("root","collapse","Collapse");
    
        $this->km->Add("root","reports","Reports");
    
        return $this->km;
    }
}
?>
