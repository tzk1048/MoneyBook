<?php
class DB{
    private $USER = "root";
    private $PW = "pass";
    private $dns = "mysql:dbname=MoneyBook;host=localhost;charset=utf8";

    private function Connectdb(){
        try {
            $pdo = new PDO($this->dns,$this->USER,$this->PW);
            return $pdo;
        } catch (Exception $e) {
            return false;
        }
    }
    protected function executeSQL($sql,$array){
        try {
            if(!$pdo = $this->Connectdb())return false;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($array);
            return $stmt;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>