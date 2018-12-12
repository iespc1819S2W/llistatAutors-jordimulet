<?php
require_once ('configuracioBiblioteca.inc');

class ConnexioMySQL{
    private $username;
    private $password;
    private $host;
    private $db;
    protected $msqli;
    public function __construct() {
        $this->username = BIB_USER;
        $this->password = BIB_PASS;
        $this->db = BIB_BBDD;
        $this->host=BIB_HOST;
        $this->connexio();
    }

    public function connexio() {
        $this->mysqli = new mysqli($this->host,$this->username, $this->password, $this->db);
        if ($this->mysqli->connect_error) {
            die('Connect Error (' . $this->mysqli->connect_errno . ') '. $this->mysqli->connect_error);
        }
    }
    public function consulta($sql) {
        $result= $this->mysqli->query($sql) or die("<h4>Operació Incorrecta. Consulta:$sql</h4>");
        return $result;
    }

}
?>