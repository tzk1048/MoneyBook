<?php
require_once('db.php');
class DBupdate extends DB{

    public $dbname;
    public $idname;

    public function __construct($dbname,$idname) {
        $this->dbname = $dbname;
        $this->idname = $idname;
    }

    public function QueryColumn($name) {
        $sql = "SHOW COLUMNS FROM {$name};";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data[] = $row[0]; 
        }
            return $data;        
    }

    public function Array($ary) {
        $data=$ary[0];
        for ($i=1; $i <  count($ary) ; $i++) { 
            $data .= "," .$ary[$i];
        }
        return $data;
    }

    //bankテーブルのセレクトボックス作成
    //input用とはform内の値が違う。初期値はjsで挿入するため設定なし
    //変更済
    public function UpdateTag_Pay(){
        $sql = "SELECT bank_id,bank FROM bank;";
        $res = parent::executeSQL($sql,null);
        $updateTag_Pay = "<select form='updt' name='FromBID' id='updtbankpay'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $updateTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
        }
        $updateTag_Pay .="</select>";
        return $updateTag_Pay;
    }

    public function UpdateTag_Pay2(){
        $sql = "SELECT bank_id,bank FROM bank;";
        $res = parent::executeSQL($sql,null);
        $updateTag_Pay = "<select form='updt' name='ToBID' id='updtbankpay2'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $updateTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
        }
        $updateTag_Pay .="</select>";
        return $updateTag_Pay;
    }

    //creditテーブルのセレクトボックス作成
    //input用とはform内の値が違う。初期値はjsで挿入するため設定なし←実装済か確認要
    //変更済
    public function UpdateTag_Credit(){
        $sql = "SELECT credit_id,credit FROM credit;";
        $res = parent::executeSQL($sql,null);
        $updateTag_Credit = "<select form='updt' name='FromBID' id='updtbankpay'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $updateTag_Credit .= "<option value=" .$row['credit_id'] .">"
                            .$row['credit'] ."</option>\n";
        }
        $updateTag_Credit .="</select>";
        return $updateTag_Credit;
    }

    //paymentテーブルのセレクトボックスを表示
    //変更済
    //関数名変更要
    public function UpdateTag_ShopCon(){
        $sql = "SELECT pay_id,payment FROM payment;";
        $res = parent::executeSQL($sql,null);
        $updateTag_ShopCon = "<select form='updt' name='ConID' id='updtshopcon'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $updateTag_ShopCon .= "<option value=" .$row['pay_id'] .">"
                            .$row['payment'] ."</option>\n";
        }
        $updateTag_ShopCon .="</select>";
        return $updateTag_ShopCon;
    }

    public function UpdateTag_Chokin(){
        $sql = "SELECT chokin_id,chokin FROM chokin;";
        $res = parent::executeSQL($sql,null);
        $updateTag_ShopCon = "<select form='updt' name='chokin_id' id='updtchokin'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $updateTag_ShopCon .= "<option value=" .$row['chokin_id'] .">"
                            .$row['chokin'] ."</option>\n";
        }
        $updateTag_ShopCon .="</select>";
        return $updateTag_ShopCon;
    }

    public function UpdateTag_Income(){
        $sql = "SELECT income_id,income FROM income;";
        $res = parent::executeSQL($sql,null);
        $updateTag_ShopCon = "<select form='updt' name='ConID' id='updtincome'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $updateTag_ShopCon .= "<option value=" .$row['income_id'] .">"
                            .$row['income'] ."</option>\n";
        }
        $updateTag_ShopCon .="</select>";
        return $updateTag_ShopCon;
    }

    //payment record&currentを更新
    //変更済
    public function UpdateMainDB($value,$db){
        $name=$this->dbname ."_" .$db;
        $column=$this->QueryColumn($name);
        for ($i=1; $i < count($column); $i++) { 
            $data[]=$column[$i] ."=" .$value[$i];
        }
        $str=$this->Array($data);
        $sql = "UPDATE $name SET $str WHERE id = {$value[0]};";
        parent::executeSQL($sql,null);
    }

    public function UpdateMainDBMovement($value,$db){
        $name=$this->dbname ."_" .$db;
        $column=$this->QueryColumn($name);
        for ($i=1; $i < count($column); $i++) { 
            $data[]=$column[$i] ."=" .$value[$i];
        }
        $str=$this->Array($data);
        $sql = "UPDATE $name SET $str WHERE move_id = {$value[0]};";
        parent::executeSQL($sql,null);
    }

    //変更後消す
    public function UpdatePaymentDB($dbname,$date,$price,$pay_id,$bank_id,$comment,$id){
        $sql = "UPDATE $dbname SET date=?,price=?,pay_id=?,bank_id=?,comment=? WHERE id =?";
        //$array = array($dbname);
        $array = array($date,$price,$pay_id,$bank_id,$comment,$id);
        parent::executeSQL($sql,$array);
    }

}
?>