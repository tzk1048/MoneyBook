<?php
require_once('db.php');
class DBshortcut extends DB{

    public $dbname;
    public $idname;

    public function __construct($dbname,$idname) {
        $this->dbname = $dbname;
        $this->idname = $idname;
    }

    public function QueryColumn($name) {
        $sql = "SHOW COLUMNS FROM $name;";
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

    public function SelectpaymentRecordAll($dbname){
        $sql = "SELECT id,name,date,bank.bank_id,bank,pay_id,payment,price,comment FROM (SELECT sc_id AS id,name,date,bank_id,payment.pay_id AS pay_id,payment,price,comment FROM $dbname LEFT OUTER JOIN payment ON $dbname.pay_id=payment.pay_id) AS $dbname LEFT OUTER JOIN bank ON $dbname.bank_id=bank.bank_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='payment_shortcut'>";
        $data .= "<tr><th>名前</th><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='sc{$row[0]}'><form action='' method='post' id='scform'><td>{$row[1]}</td><td>{$row[2]}</td><td id='{$row[3]}'>{$row[4]}</td><td id='{$row[5]}'>{$row[6]}</td><td>{$row[7]}</td><td>{$row[8]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= "<td class='upbtn-td'><input type='button' class='upbtn' value='追加' onClick='InsertRecordPay({$row[0]})'></td>";
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <input type='button' class='upbtn' value='複数追加' onClick='InsertRecordPay_list({$row[0]})'>
            </td>
            eof;
            $data .= "</form></tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectcreditRecordAll($dbname){
        $sql = "SELECT id,name,date,credit.credit_id,credit,pay_id,payment,price,comment,debit_date FROM (SELECT sc_id AS id,name,date,credit_id,payment.pay_id AS pay_id,payment,price,comment,debit_date FROM $dbname LEFT OUTER JOIN payment ON $dbname.pay_id=payment.pay_id) AS $dbname LEFT OUTER JOIN credit ON $dbname.credit_id=credit.credit_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='credit_shortcut'>";
        $data .= "<tr><th>名前</th><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th><th>引落日</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='sc{$row[0]}'><form action='' method='post' id='scform'><td>{$row[1]}</td><td>{$row[2]}</td><td id='{$row[3]}'>{$row[4]}</td><td id='{$row[5]}'>{$row[6]}</td><td>{$row[7]}</td><td>{$row[8]}</td><td>{$row[9]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= "<td class='upbtn-td'><input type='button' class='upbtn' value='追加' onClick='InsertRecordPay({$row[0]})'></td>";
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <input type='button' class='upbtn' value='複数追加' onClick='InsertRecordPay_list({$row[0]})'>
            </td>
            eof;
            $data .= "</form></tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectincomeRecordAll($dbname){
        $sql = "SELECT id,name,date,bank.bank_id,bank,income_id,income,price,comment FROM (SELECT sc_id AS id,name,date,bank_id,income.income_id AS income_id,income,price,comment FROM $dbname LEFT OUTER JOIN income ON $dbname.income_id=income.income_id) AS $dbname LEFT OUTER JOIN bank ON $dbname.bank_id=bank.bank_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='income_shortcut'>";
        $data .= "<tr><th>名前</th><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='sc{$row[0]}'><form action='' method='post' id='scform'><td>{$row[1]}</td><td>{$row[2]}</td><td id='{$row[3]}'>{$row[4]}</td><td id='{$row[5]}'>{$row[6]}</td><td>{$row[7]}</td><td>{$row[8]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= "<td class='upbtn-td'><input type='button' class='upbtn' value='追加' onClick='InsertRecordIncome({$row[0]})'></td>";
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <input type='button' class='upbtn' value='複数追加' onClick='InsertRecordIncome_list({$row[0]})'>
            </td>
            eof;
            $data .= "</form></tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectmovementRecordAll($dbname){
        $sql = "SELECT movement_shortcut.sc_id,name,date,withdrawal_shortcut.bank_id,bank1.bank,deposit_shortcut.bank_id,bank2.bank,price,comment FROM movement_shortcut LEFT OUTER JOIN withdrawal_shortcut ON movement_shortcut.sc_id=withdrawal_shortcut.sc_id left outer join deposit_shortcut on movement_shortcut.sc_id=deposit_shortcut.sc_id left outer join bank as bank1 on withdrawal_shortcut.bank_id=bank1.bank_id left outer join bank as bank2 on deposit_shortcut.bank_id=bank2.bank_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='movement_shortcut'>";
        $data .= "<tr><th>名前</th><th>日付</th><th>出金元</th><th>入金先</th><th>金額</th><th>備考</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='sc{$row[0]}'><form action='' method='post' id='scform'><td>{$row[1]}</td><td>{$row[2]}</td><td id='{$row[3]}'>{$row[4]}</td><td id='{$row[5]}'>{$row[6]}</td><td>{$row[7]}</td><td>{$row[8]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= "<td class='upbtn-td'><input type='button' class='upbtn' value='追加' onClick='InsertRecordMovement({$row[0]})'></td>";
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <input type='button' class='upbtn' value='複数追加' onClick='InsertRecordMovement_list({$row[0]})'>
            </td>
            eof;
            $data .= "</form></tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function ShortcutTag_Pay() {
        $sql = "SELECT bank_id,bank FROM bank;";
        $res = parent::executeSQL($sql,null);
        $updateTag_Pay = "<option value=0>---</option>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $updateTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
        }
        return $updateTag_Pay;
    }

    public function ShortcutTag_Shop() {
        $sql = "SELECT pay_id,payment FROM payment;";
        $res = parent::executeSQL($sql,null);
        $updateTag_ShopCon = "<option value=0>---</option>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $updateTag_ShopCon .= "<option value=" .$row['pay_id'] .">"
                            .$row['payment'] ."</option>\n";
        }
        return $updateTag_ShopCon;
    }

    public function ShortcutTag_Credit() {
        $sql = "SELECT credit_id,credit FROM credit;";
        $res = parent::executeSQL($sql,null);
        $updateTag_Pay = "<option value=0>---</option>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $updateTag_Pay .= "<option value=" .$row['credit_id'] .">"
                            .$row['credit'] ."</option>\n";
        }
        return $updateTag_Pay;
    }

    public function ShortcutTag_income() {
        $sql = "SELECT income_id,income FROM income;";
        $res = parent::executeSQL($sql,null);
        $updateTag_ShopCon = "<option value=0>---</option>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $updateTag_ShopCon .= "<option value=" .$row['income_id'] .">"
                            .$row['income'] ."</option>\n";
        }
        return $updateTag_ShopCon;
    }

    public function SelectpaymentRecordSet(){
        $sql = "SELECT markup_payment.id,payment_shortcut.sc_id,name,date,bank.bank_id,bank,pay_id,payment,price,comment FROM (SELECT sc_id,name,date,bank_id,payment.pay_id AS pay_id,payment,price,comment FROM payment_shortcut LEFT OUTER JOIN payment ON payment_shortcut.pay_id=payment.pay_id) AS payment_shortcut LEFT OUTER JOIN bank ON payment_shortcut.bank_id=bank.bank_id LEFT OUTER JOIN markup_payment ON payment_shortcut.sc_id=markup_payment.sc_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='payment_shortcut_set'>";
        $data .= "<tr><th>お気に入り</th><th>名前</th><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            if ($row[0]!=null) {
                $markup ="お気に入り" .$row[0];
            }  else  {
                $markup="";
            }
            $data .= "<tr id='scset{$row[1]}'><td>{$markup}</td><td>{$row[2]}</td><td>{$row[3]}</td><td id='{$row[4]}'>{$row[5]}</td><td id='{$row[6]}'>{$row[7]}</td><td>{$row[8]}</td><td>{$row[9]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='updatesc_pay({$row[1]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[1]}'>
            <input type='submit' class='upbtn' name='deletesc' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectcreditRecordSet(){
        $sql = "SELECT markup_credit.id,credit_shortcut.sc_id,name,date,credit.credit_id,credit,pay_id,payment,price,comment,debit_date FROM (SELECT sc_id,name,date,credit_id,payment.pay_id AS pay_id,payment,price,comment,debit_date FROM credit_shortcut LEFT OUTER JOIN payment ON credit_shortcut.pay_id=payment.pay_id) AS credit_shortcut LEFT OUTER JOIN credit ON credit_shortcut.credit_id=credit.credit_id LEFT OUTER JOIN markup_credit ON credit_shortcut.sc_id=markup_credit.sc_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='credit_shortcut_set'>";
        $data .= "<tr><th>お気に入り</th><th>名前</th><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th><th>引落日</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            if ($row[0]!=null) {
                $markup ="お気に入り" .$row[0];
            }  else  {
                $markup="";
            }
            $data .= "<tr id='scset{$row[1]}'><td>{$markup}</td><td>{$row[2]}</td><td>{$row[3]}</td><td id='{$row[4]}'>{$row[5]}</td><td id='{$row[6]}'>{$row[7]}</td><td>{$row[8]}</td><td>{$row[9]}</td><td>{$row[10]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='updatesc_pay({$row[1]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[1]}'>
            <input type='submit' class='upbtn' name='deletesc' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectincomeRecordSet(){
        $sql = "SELECT markup_income.id,income_shortcut.sc_id,name,date,bank.bank_id,bank,income_id,income,price,comment FROM (SELECT sc_id,name,date,bank_id,income.income_id AS income_id,income,price,comment FROM income_shortcut LEFT OUTER JOIN income ON income_shortcut.income_id=income.income_id) AS income_shortcut LEFT OUTER JOIN bank ON income_shortcut.bank_id=bank.bank_id LEFT OUTER JOIN markup_income ON income_shortcut.sc_id=markup_income.sc_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='income_shortcut_set'>";
        $data .= "<tr><th>お気に入り</th><th>名前</th><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            if ($row[0]!=null) {
                $markup ="お気に入り" .$row[0];
            }  else  {
                $markup="";
            }
            $data .= "<tr id='scset{$row[1]}'><td>{$markup}</td><td>{$row[2]}</td><td>{$row[3]}</td><td id='{$row[4]}'>{$row[5]}</td><td id='{$row[6]}'>{$row[7]}</td><td>{$row[8]}</td><td>{$row[9]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='updatesc_income({$row[1]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[1]}'>
            <input type='submit' class='upbtn' name='deletesc' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectMovementRecordSet(){
        $sql = "select markup_movement.id,movement_shortcut.sc_id,name,date,withdrawal_shortcut.bank_id,bank1.bank,deposit_shortcut.bank_id,bank2.bank,price,comment from movement_shortcut left outer join withdrawal_shortcut on movement_shortcut.sc_id=withdrawal_shortcut.sc_id left outer join deposit_shortcut on movement_shortcut.sc_id=deposit_shortcut.sc_id left outer join bank as bank1 on withdrawal_shortcut.bank_id=bank1.bank_id left outer join bank as bank2 on deposit_shortcut.bank_id=bank2.bank_id left outer join markup_movement on movement_shortcut.sc_id=markup_movement.sc_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='movement_shortcut_set'>";
        $data .= "<tr><th>お気に入り</th><th>名前</th><th>日付</th><th>出金元</th><th>入金先</th><th>金額</th><th>備考</th><th colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            if ($row[0]!=null) {
                $markup ="お気に入り" .$row[0];
            }  else  {
                $markup="";
            }
            $data .= "<tr id='scset{$row[1]}'><td>{$markup}</td><td>{$row[2]}</td><td>{$row[3]}</td><td id='{$row[4]}'>{$row[5]}</td><td id='{$row[6]}'>{$row[7]}</td><td>{$row[8]}</td><td>{$row[9]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='updatesc_movement({$row[1]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[1]}'>
            <input type='submit' class='upbtn' name='deletesc' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function UpdateShortcut($value){
        $name=$this->dbname ."_shortcut";
        $column=$this->QueryColumn($name);
        for ($i=1; $i < count($column); $i++) { 
            $data[]=$column[$i] ."=" .$value[$i];
        }
        $str=$this->Array($data);
        $sql = "UPDATE $name SET $str WHERE sc_id = {$value[0]};";
        parent::executeSQL($sql,null);
        return $sql;
    }

    public function InsertShortcut($value) {
        $str=$this->Array($value);
        $sql = "INSERT INTO {$this->dbname}_shortcut VALUES({$str});";
        $res = parent::executeSQL($sql,null);
    }

    public function DeleteRecord($id){
        $sql = "DELETE FROM {$this->dbname}_shortcut WHERE sc_id=?";
        $array = array($id);
        parent::executeSQL($sql, $array);
    }

    public function SelectMarkupTable() {
        $sql = "SELECT * FROM Markup_{$this->dbname};";
        $res = parent::executeSQL($sql,null);
        return $res;
    }

    public function CreateMarkupTable() {
        $sql = "SELECT id,{$this->dbname}_shortcut.sc_id,name FROM markup_{$this->dbname} LEFT OUTER JOIN {$this->dbname}_shortcut ON markup_{$this->dbname}.sc_id={$this->dbname}_shortcut.sc_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='markup_{$this->dbname}'>";
        $data .= "<tr><th></th><th>名前</th><th></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='{$row[0]}'><td>お気に入り{$row[0]}</td><td id='{$row[1]}'>{$row[2]}</td><td><form action='' method='post'><input type='hidden' name='id' value={$row[0]}><input type='submit' name='markupdlt' value='お気に入り解除'></form></td></tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function UpdateMarkup($data) {
        for ($i=1; $i < 7; $i++) { 
            if($data[$i-1]!=null) {
                $sql = "UPDATE markup_{$this->dbname} SET sc_id={$data[$i-1]} WHERE id=$i;";
            parent::executeSQL($sql,null);
            }  
        }
    }

    public function DeleteMarkup($id) {
        $sql = "UPDATE markup_{$this->dbname} SET sc_id=NULL WHERE id=$id;";
        parent::executeSQL($sql,null);
    }

    public function MarkupCard() {
        $sql = "SELECT id,sc_id,name,date,bank,payment,price,comment FROM (SELECT id,markup_payment.sc_id as sc_id,name,date,bank_id,pay_id,price,comment from markup_payment inner join payment_shortcut on markup_payment.sc_id=payment_shortcut.sc_id) as markup LEFT OUTER JOIN bank on markup.bank_id=bank.bank_id LEFT OUTER JOIN payment ON markup.pay_id=payment.pay_id;";
        $res = parent::executeSQL($sql,null);
        //$data = "<table class='recordlist' id='markupsheet_payment'>";
        //$data .= "<tr><th></th><th>名前</th><th></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $value="<div id='markupcard{$row[0]}' class='markupcard' onclick='InsertFromCard({$row[1]})'><h5>{$row[2]}</h5><ul>";
            if ($row[3]!=null) {
                $value .="<li>日付：{$row[3]}</li>";
            }
            if ($row[4]!=null) {
                $value .="<li>支払い：{$row[4]}</li>";
            }
            if ($row[5]!=null) {
                $value .="<li>項目：{$row[5]}</li>";
            }
            if ($row[6]!=null) {
                $value .="<li>金額：{$row[6]}</li>";
            }
            if ($row[7]!=null) {
                $value .="<li>メモ：{$row[7]}</li>";
            }
            $value .="</ul></div>";
            $data[]=$value;
        }
        //$data .= "</table>\n";
        return $data;
    }

    public function MarkupCardCredit() {
        $sql = "SELECT id,sc_id,name,date,credit,payment,price,comment FROM (SELECT id,markup_credit.sc_id AS sc_id,name,date,price,pay_id,credit_id,comment FROM markup_credit INNER JOIN credit_shortcut ON markup_credit.sc_id=credit_shortcut.sc_id) AS markup LEFT OUTER JOIN credit ON markup.credit_id=credit.credit_id LEFT OUTER JOIN payment ON markup.pay_id=payment.pay_id;";
        $res = parent::executeSQL($sql,null);
        //$data = "<table class='recordlist' id='markupsheet_payment'>";
        //$data .= "<tr><th></th><th>名前</th><th></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $value="<div id='markupcard{$row[0]}' class='markupcard' onclick='InsertFromCard({$row[1]})'><h5>{$row[2]}</h5><ul>";
            if ($row[3]!=null) {
                $value .="<li>日付：{$row[3]}</li>";
            }
            if ($row[4]!=null) {
                $value .="<li>支払い：{$row[4]}</li>";
            }
            if ($row[5]!=null) {
                $value .="<li>項目：{$row[5]}</li>";
            }
            if ($row[6]!=null) {
                $value .="<li>金額：{$row[6]}</li>";
            }
            if ($row[7]!=null) {
                $value .="<li>メモ：{$row[7]}</li>";
            }
            $value .="</ul></div>";
            $data[]=$value;
        }
        //$data .= "</table>\n";
        return $data;
    }

    public function MarkupCardMovement() {
        $sql = "select markup_movement.id,movement_shortcut.sc_id,name,date,withdrawal_shortcut.bank_id,bank1.bank,deposit_shortcut.bank_id,bank2.bank,price,comment from movement_shortcut left outer join withdrawal_shortcut on movement_shortcut.sc_id=withdrawal_shortcut.sc_id left outer join deposit_shortcut on movement_shortcut.sc_id=deposit_shortcut.sc_id left outer join bank as bank1 on withdrawal_shortcut.bank_id=bank1.bank_id left outer join bank as bank2 on deposit_shortcut.bank_id=bank2.bank_id left outer join markup_movement on movement_shortcut.sc_id=markup_movement.sc_id;";
        $res = parent::executeSQL($sql,null);
        //$data = "<table class='recordlist' id='markupsheet_payment'>";
        //$data .= "<tr><th></th><th>名前</th><th></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $value="<div id='markupcard{$row[0]}' class='markupcard' onclick='InsertFromCard({$row[1]})'><h5>{$row[2]}</h5><ul>";
            if ($row[3]!=null) {
                $value .="<li>日付：{$row[3]}</li>";
            }
            if ($row[4]!=null) {
                $value .="<li>出金元：{$row[5]}</li>";
            }
            if ($row[5]!=null) {
                $value .="<li>入金先：{$row[7]}</li>";
            }
            if ($row[6]!=null) {
                $value .="<li>金額：{$row[8]}</li>";
            }
            if ($row[7]!=null) {
                $value .="<li>メモ：{$row[9]}</li>";
            }
            $value .="</ul></div>";
            $data[]=$value;
        }
        //$data .= "</table>\n";
        return $data;
    }

    public function MarkupSheet($data) {
        $table="<table id='markupsheet'>";
        for ($i=0; $i < count($data); $i++) { 
            if ($i%2==0) {
                $table.="<tr><td id='m_card{$i}'>{$data[$i]}</td>";
            } else {
                $table.="<td id='m_card{$i}'>{$data[$i]}</td></tr>";
            }
        }
        if (count($data)%2!=0) {
            $table.="</tr>";
        }
        $table.="</table>";
        return $table;
    }

    public function QueryMovementShortcutMaxID(){
        $sql = "SELECT MAX(sc_id) FROM movement_shortcut";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $maxid = $row[0];
        }
        return $maxid;
    }
}
?>