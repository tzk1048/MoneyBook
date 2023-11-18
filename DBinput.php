<?php
require_once('db.php');
class DBinput extends DB{

    public $dbname;
    public $idname;

    public function __construct($dbname,$idname) {
        $this->dbname = $dbname;
        $this->idname = $idname;
    }

    public function QueryColumn() {
        $sql = "SHOW COLUMNS FROM $this->dbname;";
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
    //変更済
    //$_POST['FromBID']の名前変更要
    public function SelectTag_Pay(){
        $sql = "SELECT bank_id,bank FROM bank;";
        $res = parent::executeSQL($sql,null);
        $selectTag_Pay = "<select name='FromBID' id='selecttag_pay'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            if(isset($_POST['FromBID'])){
                if($_POST['FromBID'] == $row['bank_id']){
                    $selectTag_Pay .= "<option value=" .$row['bank_id'] ." selected>"
                            .$row['bank'] ."</option>\n";
                }else {
                    $selectTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
                }
            }else {
                $selectTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
            }
        }
        $selectTag_Pay .="</select>";
        return $selectTag_Pay;
    }

    public function SelectTag_Pay2(){
        $sql = "SELECT bank_id,bank FROM bank;";
        $res = parent::executeSQL($sql,null);
        $selectTag_Pay = "<select name='ToBID' id='selecttag_pay2'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            if(isset($_POST['ToBID'])){
                if($_POST['ToBID'] == $row['bank_id']){
                    $selectTag_Pay .= "<option value=" .$row['bank_id'] ." selected>"
                            .$row['bank'] ."</option>\n";
                }else {
                    $selectTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
                }
            }else {
                $selectTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
            }
        }
        $selectTag_Pay .="</select>";
        return $selectTag_Pay;
    }

    //creditテーブルのセレクトボックス作成
    //変更済
    //$_POST['FromBID']の名前変更要
    public function SelectTag_Credit(){
        $sql = "SELECT credit_id,credit FROM credit;";
        $res = parent::executeSQL($sql,null);
        $selectTag_Credit = "<select name='FromBID' id='selecttag_credit'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            if(isset($_POST['FromBID'])){
                if($_POST['FromBID'] == $row['credit_id']){
                    $selectTag_Credit .= "<option value=" .$row['credit_id'] ." selected>"
                            .$row['credit'] ."</option>\n";
                }else {
                    $selectTag_Credit .= "<option value=" .$row['credit_id'] .">"
                            .$row['credit'] ."</option>\n";
                }
            }else {
                $selectTag_Credit .= "<option value=" .$row['credit_id'] .">"
                            .$row['credit'] ."</option>\n";
            }
        }
        $selectTag_Credit .="</select>";
        return $selectTag_Credit;
    }

    public function SelectTag_CreditDebit(){
        $sql = "SELECT credit_id,credit FROM credit;";
        $res = parent::executeSQL($sql,null);
        $selectTag_Credit = "<select name='credit_id' id='selecttag_credit_debit'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $selectTag_Credit .= "<option value=" .$row['credit_id'] .">"
                            .$row['credit'] ."</option>\n";
        }
        $selectTag_Credit .="</select>";
        return $selectTag_Credit;
    }

    public function QueryCreditDebit($id,$date) {
        $sql = "SELECT dead,debit FROM credit WHERE credit_id={$id}";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $dead = $row[0];
            $debit = $row[1];
        }
        //$debit_date=date("Y-m-d",strtotime(date("Y",strtotime($date)) ."-" .date("m",strtotime("+1 month",strtotime($date))) ."-" .$debit));
        if(date("d",strtotime($date)) <= $dead) {
            $debit_date=date("Y-m-d",strtotime(date("Y",strtotime($date)) ."-" .date("m",strtotime("+1 month",strtotime($date))) ."-" .$debit));
        } else {
            $debit_date=date("Y-m-d",strtotime(date("Y",strtotime($date)) ."-" .date("m",strtotime("+2 month",strtotime($date))) ."-" .$debit));
        }
        return $debit_date;
    }

    //payment_currntテーブルに新レコードを挿入
    //変更済
    public function InsertRecordToCurrent($data){
        $sql = "INSERT INTO {$this->dbname}_current VALUES({$data})";
        parent::executeSQL($sql,null);
    }

    public function InsertRecordToRecord($data){
        $sql = "INSERT INTO {$this->dbname}_record VALUES({$data})";
        parent::executeSQL($sql,null);
    }

    public function InsertRecord($data){
        $sql = "INSERT INTO {$this->dbname} VALUES({$data})";
        parent::executeSQL($sql,null);
    }

    public function InsertCreditDebit($data){
        $sql = "INSERT INTO credit_debit VALUES({$data})";
        parent::executeSQL($sql,null);
    }

    //chokin_currntテーブルに新レコードを挿入
    //変更済
    public function InsertChokinCurrent($date,$chokin_id,$bank_id){
        $sql = "INSERT INTO chokin_current VALUES(NULL,?,?,?,NULL)";
        $array = array($date,$chokin_id,$bank_id);
        parent::executeSQL($sql,$array);
    }

    /*public function InsertcurrentDB($date,$CateID,$FromBID,$ToBID,$ConID,$price,$com){
        $sql = "INSERT INTO currentDB Values(NULL,?,?,?,?,?,?,?)";
        $array = array($date,$CateID,$FromBID,$ToBID,$ConID,$price,$com);
        parent::executeSQL($sql,$array);
    }*/

    //payment_recordにpayment_currentを挿入
    //変更済
    public function CurrentToRecord(){
        $sql = "SHOW COLUMNS FROM {$this->dbname}_record;";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $ary[] = $row[0]; 
        }
        $column=$ary[1];
        for ($i=2; $i <  count($ary) ; $i++) { 
            $column .= "," .$ary[$i];
        }
        $sql = "INSERT INTO {$this->dbname}_record({$column}) SELECT {$column} FROM {$this->dbname}_current;";
        parent::executeSQL($sql,null);
        return $sql;
    }

    //chokin_recordにchokin_currentを挿入
    //変更済
    public function InsertChokinRecord($fromDB,$toDB){
        $sql = "INSERT INTO $toDB(date,chokin_id,bank_id,comment) SELECT date,chokin_id,bank_id,comment FROM $fromDB;";
        parent::executeSQL($sql,null);
    }

    //currentテーブルの全レコードを削除
    //変更済
    public function DeletecurrentDBAll(){
        $sql = "DELETE FROM {$this->dbname}_current;";
        parent::executeSQL($sql,null);
    }

    //推し貯金入力フォーム
    //変更済
    public function input_oshichokin(){
        $sql = "SELECT chokin_id-32000 AS chokin_id,chokin,price FROM chokin WHERE 32000 < chokin_id;";
        $res = parent::executeSQL($sql,null);
        $ContentsList = "<table class='recordlist' id='input_oshichokin'>";
        $ContentsList .= "<tr><th>ID</th><th>項目</th><th>個数</th><th>単価</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $id = $row[0] + 32000;
            $ContentsList .= "<tr><td>{$row[0]}</td><td>{$row[1]}</td>";
            $ContentsList .= "<td><input type='number' min='0' name='{$id}' value='0'></td><td>{$row[2]}</td></tr>\n";
        }
        $ContentsList .= "</table>
                <input type='hidden' name='chokincon_len' value='{$row[0]}'><br>\n";
        return $ContentsList;
    }

    public function query_oshichokinPrice($num){
        $sql = "SELECT price FROM chokin_contents WHERE ConID = {$num} ";
        $res = parent::executeSQL($sql,null);
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $price = $row['price'];
            return $price;
        }
    }

    public function QueryMaxID(){
        $sql = "SELECT MAX($this->idname) FROM $this->dbname";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $maxid = $row[0];
        }
        return $maxid;
    }

    public function CurrentPreview() {
        $data=<<<EOF
        <h3 id='current_preview_h'>プレビューデータ</h3><button id='current_preview_btn' onclick="Show('current_preview','current_preview_btn','▼')">▲</button><div class='current_preview' id='current_preview'>
        EOF;
        $sql = "select count(*),sum(price) from {$this->dbname}_current;";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data .= "<p>総レコード数：{$row[0]}</p><p>合計金額：{$row[1]}</p>";
        }

        $data.="<h4>支払い別</h4>";

        $sql1 = "select bank,count(*),sum(price) from {$this->dbname}_current inner join bank on {$this->dbname}_current.bank_id=bank.bank_id group by bank.bank_id;";
        $res1 = parent::executeSQL($sql1,null);
        foreach($rows = $res1->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data .= "<p>【{$row[0]}】</p><p>レコード数：{$row[1]} 合計金額：{$row[2]}</p>";
        }

        $data.="</div>";

        return $data;
    }

    public function QuerySettingOption($id) {
        $sql = "SELECT option FROM setting WHERE id={$id};";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $value = $row[0];
        }
        return $value;
    }

    public function QueryCreditValue($id,$column) {
        $sql = "SELECT {$column} FROM credit WHERE credit_id={$id};";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $value = $row[0];
        }
        return $value;
    }

    public function CreditDebitTable(){
        $ContentsList ="";
        for ($i=1; $i < 10; $i++) { 
            $id=$i+200;
            $ContentsList .= "<table class='credit_debit_table' id='creditdebit{$id}' style='display:none;'>\n";
            $sql = <<<SQL
                select
                    credit_record.debit_date,
                    price,
                    debit.date,
                    debit.move_id,
                    debit_price,
                    credit_record.debit
                from
                    (
                        select
                            debit_date as debit,
                            date_format(debit_date, '%Y-%m') as debit_date,
                            sum(price) as price
                        from
                            credit_record
                        where
                            credit_id = {$id}
                        group by
                            debit_date
                    ) as credit_record
                    left outer join
                        (
                            select
                                date_format(credit_debit.date, '%Y-%m') as debit_date,
                                credit_debit.date as date,
                                movement_record.move_id,
                                price as debit_price
                            from
                                credit_debit
                                inner join
                                    movement_record
                                on  credit_debit.move_id = movement_record.move_id
                            where
                                credit_id = {$id}
                        ) as debit
                    on  credit_record.debit_date = debit.debit_date
                order by
                    credit_record.debit_date desc
                ;
            SQL;
            
            $res = parent::executeSQL($sql,null);
            $rows = $res->fetchAll(PDO::FETCH_NUM);
            if(isset($rows[0][0])){
                $ContentsList .= "<tr><th>ID</th><th>合計額</th><th>引落日</th><th>引落額</th><th></th></tr>\n";
                foreach($rows as $row){
                    $ContentsList .= "<tr id='{$row[0]}'><td>{$row[0]}</td><td>{$row[1]}</td>";
                    if($row[3]=="") {
                        $ContentsList .= <<<eof
                        <td>{$row[5]}</td>
                        <td>
                            <form action='' method='post' id='form{$row[0]}'>
                                <input type='hidden' form='form{$row[0]}' name='debit_price' value='{$row[1]}'>
                                <input type='hidden' form='form{$row[0]}' name='debit_date' value='{$row[5]}'>
                                <input type='hidden' form='form{$row[0]}' name='credit' value='{$id}'>
                                <input type='submit' form='form{$row[0]}' name='insert_debit' value='引落'>
                            </from>
                        </td>
                        eof;
                    } else {
                        $ContentsList .= "<td>{$row[2]}</td><td id='{$row[3]}'>{$row[4]}</td>";
                    }
                    /*$ContentsList .= <<<eof
                    <td class='upbtn-td' ><input type='button' class='upbtn' value='更新' onClick='update{$this->table}({$id})'></td>
                    eof;*/
                    //削除ボタンのコード
                    $ContentsList .= <<<eof
                    <td class='upbtn-td'>
                    <form method='post' action=''>
                    <input type='hidden' name='id' id='Daleteid' value='{$row[0]}'>
                    <input type='submit' class='upbtn' name='statement' value='詳細'>
                    </form>
                    </td>
                    eof;
                    $ContentsList .= "</tr>\n";
                }
            }
        $ContentsList .= "</table>\n";
        }
        return $ContentsList;
    }

    public function CreditStatement($month) {
        $sql = "select date,payment,price,comment from credit_record inner join payment on credit_record.pay_id=payment.pay_id WHERE date_format(debit_date,'%Y-%m')='{$month}';";
        $res = parent::executeSQL($sql,null);
        $data="<table class='credit_statement'><tr><th>使用日</th><th>項目</th><th>金額</th><th>備考</th></tr>";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data .= "<tr>";
            for ($i=0; $i < count($row); $i++) { 
                $data .="<td>{$row[$i]}</td>";
            }
            $data .="<tr>";
        }

        $data.="</table>";
        return $data;
    }
}
?>