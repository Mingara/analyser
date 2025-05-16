<?php
class UserRights {

    private $conn = null;
    private $userIdent = null;

    public function __construct($conn, $userIdent) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = $conn;
        $this->userIdent = $userIdent;
        //error_log("getUserRights0" . PHP_EOL, 3, "UserRights.log");
        $this->getUserRights();
    }

    private function getUserRights() {

        //error_log("getUserRights1" . PHP_EOL, 3, "UserRights.log");
        $sql = "SELECT Ident,UserPass,HostName,UserName,Anrede,Nachname,CompList,CatalogList,EditComment,Financial," .
            "Reporting,CheckIn,Controlling,Printing,NoMargin,Tech,aPost,ExAcc,newStarter,newAnalyser,newMobile,UserID " .
            "FROM analyser..Users WHERE Ident = '" . $this->userIdent . "' AND CatalogList <> '' AND CompList <> ''";
        $stmt = sqlsrv_query( $this->conn, $sql);
        if( $stmt === false ) {
            die( print_r( sqlsrv_errors(), true));
        }
        
        // Make the first (and in this case, only) row of the result set available for reading.
        if( sqlsrv_fetch( $stmt ) === false ) {
            die( print_r( sqlsrv_errors(), true));
        }
    
        $_SESSION['user_rights'] = [
            "Author" => "Rzayev Kamran, Master of Engineering",
            "Ident" => sqlsrv_get_field( $stmt, 0),
            "UserPass" => sqlsrv_get_field( $stmt, 1),
            "HostName" => sqlsrv_get_field( $stmt, 2),
            "ComputerName" => sqlsrv_get_field( $stmt, 2),
            "UserName" => sqlsrv_get_field( $stmt, 3),
            "Nachname" => sqlsrv_get_field( $stmt, 4) . sqlsrv_get_field( $stmt, 5),
            "CompList" => str_replace('|','',str_replace('||',',',sqlsrv_get_field( $stmt, 6))),
            "CatalogList" => str_replace('|','\'',str_replace('||','\',\'',sqlsrv_get_field( $stmt, 7))),
            "EditComment" => sqlsrv_get_field( $stmt, 8),
            "Financial" => sqlsrv_get_field( $stmt, 9),
            "Reporting" => sqlsrv_get_field( $stmt, 10),
            "CheckIn" => sqlsrv_get_field( $stmt, 11),
            "Controlling" => sqlsrv_get_field( $stmt, 12),
            "Printing" => sqlsrv_get_field( $stmt, 13),
            "NoMargin" => sqlsrv_get_field( $stmt, 14),
            "Tech" => sqlsrv_get_field( $stmt, 15),
            "Post" => sqlsrv_get_field( $stmt, 16),
            "ExAcc" => sqlsrv_get_field( $stmt, 17),
            "newStarter" => sqlsrv_get_field( $stmt, 18),
            "newAnalyser" => sqlsrv_get_field( $stmt, 19),
            "newMobile" => sqlsrv_get_field( $stmt, 20),
            "UserID" => sqlsrv_get_field( $stmt, 21)
        ];
    }
}
?>