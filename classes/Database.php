<?php
class Database {
    private $host = "HOMEOFFICE\HOMESQLSERVER";
    private $db_name = "analyser";
    private $username = "analyst";
    private $password = "SevKam1299!";
    private $type = "mssqlnative";
    private $charset = "UTF-8";

    // Статическое свойство для хранения единственного соединения
    private static $connection = null;

    public function __construct() {
        sqlsrv_configure('CharacterSet', 'UTF-8');
    }

    public function dbConnection($uid = null, $pwd = null) {
        // Обновляем учетные данные, если они предоставлены
        $this->updateCredentials($uid, $pwd);

        // Проверяем, существует ли уже соединение
        if (self::$connection === null) {
            self::$connection = $this->sqlsrvConnection();
        }

        return self::$connection;
    }

    public function gridConnection($uid = null, $pwd = null) {
        $this->updateCredentials($uid, $pwd);

        return [
            "type" => $this->type,
            "server" => $this->host,
            "user" => $this->username,
            "password" => $this->password,
            "database" => $this->db_name,
            "charset" => $this->charset
        ];
    }

    private function updateCredentials($uid, $pwd) {
        if (!empty($uid)) {
            $this->username = $uid;
        }
        if (!empty($pwd)) {
            $this->password = $pwd;
        }
    }

    private function sqlsrvConnection() {
        $connInfo = [
            "Database" => $this->db_name,
            "UID" => $this->username,
            "PWD" => $this->password,
            "TrustServerCertificate" => "yes",
            "CharacterSet" => $this->charset
        ];

        $conn = sqlsrv_connect($this->host, $connInfo);

        if ($conn === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        return $conn;
    }
}
?>
