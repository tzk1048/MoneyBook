<?php
require_once('db.php');
class DBsetting extends DB{

    public $table;
    public $idname;
    public $iddif;
    public $column;
    public $column2;

    public function __construct($table,$idname,$iddif,$column,$column2) {
        $this->table = $table;
        $this->idname = $idname;
        $this->differ = $iddif;
        $this->column = $column;
        $this->column2 = $column2;
    }

    public function test(){
        return $this->table ."+" .$this->differ;
    }


    public function SelectTable(){
        /*if($this->column2==""){
            $sql = "SELECT $this->idname-$this->differ,$this->column FROM $this->table;";
        } else {
            $sql = "SELECT $this->idname-$this->differ,$this->column,$this->column2 FROM $this->table;";
        }*/
        //$column = $this->Array($this->QueryColumn());
        //$sql = "SELECT $column FROM $this->table;";
        //$columns = $this->QueryColumn();
        //$columns[0] = "{$columns[0]}-{$this->differ}";
        $sql = "SELECT * FROM $this->table;";
        $res = parent::executeSQL($sql,null);
        $ContentsList = "<tr><th>ID</th><th>項目</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $id = $row[0];
            $row[0] -=$this->differ;
            $ContentsList .= "<tr id='{$id}'>";
            for ($i=0; $i < count($row); $i++) {
                $ContentsList .="<td>{$row[$i]}</td>";
            }
            $ContentsList .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='update{$this->table}({$id})'></td>
            eof;
            //削除ボタンのコード
            $ContentsList .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$id}'>
            <input type='submit' class='upbtn' name='delete{$this->idname}' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $ContentsList .= "</tr>\n";
        }
        return $ContentsList;
    }

    public function SelectTableCredit(){
        $sql = "SELECT credit_id,credit,dead,debit,bank.bank_id,bank FROM credit INNER JOIN bank ON credit.bank_id=bank.bank_id;";
        $res = parent::executeSQL($sql,null);
        $ContentsList = "<tr><th>ID</th><th>カード名</th><th>締め日</th><th>引落日</th><th>引落元</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $id = $row[0];
            $row[0] -=$this->differ;
            $ContentsList .= "<tr id='{$id}'><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td><td id='{$row[4]}'>{$row[5]}</td>";
            $ContentsList .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='update{$this->table}({$id})'></td>
            eof;
            //削除ボタンのコード
            $ContentsList .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$id}'>
            <input type='submit' class='upbtn' name='delete{$this->idname}' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $ContentsList .= "</tr>\n";
        }
        return $ContentsList;
    }

    public function SelectTableChokin(){
        $ContentsList ="";
        for ($i=1; $i < 10; $i++) { 
            $ContentsList .= "<table class='chokin_set table' id='chokinlist{$i}' style='display:none;'>\n";
            $id1=$i*1000+30000;
            $id2=$id1+1000;
            $sql = "SELECT * FROM $this->table WHERE chokin_id>{$id1} AND chokin_id <={$id2};";
            $res = parent::executeSQL($sql,null);
            $rows = $res->fetchAll(PDO::FETCH_NUM);
            if(isset($rows[0][0])){
                $ContentsList .= "<tr><th>ID</th><th>項目</th><th>金額</th></tr>\n";
                $sql = "SELECT * FROM $this->table WHERE chokin_id>{$id1} AND chokin_id <={$id2};";
                $res = parent::executeSQL($sql,null);
                foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
                    $id = $row[0];
                    $row[0] = $row[0]-$this->differ-$i*1000;
                    $ContentsList .= "<tr id='{$id}'>";
                    for ($n=0; $n < count($row); $n++) {
                        $ContentsList .="<td>{$row[$n]}</td>";
                    }
                    $ContentsList .= <<<eof
                    <td class='upbtn-td' ><input type='button' class='upbtn' value='更新' onClick='update{$this->table}({$id})'></td>
                    eof;
                    //削除ボタンのコード
                    $ContentsList .= <<<eof
                    <td class='upbtn-td'>
                    <form method='post' action=''>
                    <input type='hidden' name='id' id='Daleteid' value='{$id}'>
                    <input type='submit' class='upbtn' name='delete{$this->idname}' value='削除' onClick='return CheckDelete()'>
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

    public function SelectTag_ChokinTable() {
        $select=$this->QuerySettingOption(1);
        $data="<select name='chokintbl' id='chokin_table_option'>";
        for ($i=1; $i < 10; $i++) {
            if ($i==$select) {
                $data .= "<option value='{$i}' selected>テーブル{$i}</option>";
            } else {
                $data .= "<option value='{$i}'>テーブル{$i}</option>";
            }
        }
        $data .= "</select>";
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

    public function UpdateSetting($id,$value) {
        $sql = "UPDATE setting SET option={$value} WHERE id={$id};";
        $res = parent::executeSQL($sql,null);
    }

    public function QueryColumn() {
        $sql = "SHOW COLUMNS FROM $this->table;";
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

    public function QueryNextID() {
        $sql = "SELECT MAX($this->idname) FROM $this->table;";
        $res = parent::executeSQL($sql,null);
        $ID=null;
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $ID = $row[0];
        }
        $ID++;
        return $ID;
    }

    public function QueryNextIDChokin($tbl) {
        $id = $this->differ+($tbl*1000)+1000;
        $sql = "SELECT MAX($this->idname) FROM $this->table WHERE chokin_id < {$id} ;";
        $res = parent::executeSQL($sql,null);
        $ID=null;
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $ID = $row[0];
        }
        $ID++;
        return $ID;
    }

    public function InsertTable($values) {
        $sql = "INSERT INTO $this->table Values({$values});";
        $res = parent::executeSQL($sql,null);
    }

    public function InsertTableBank($name,$balance) {
        $sql = "SELECT MAX($this->idname) FROM $this->table;";
        $res = parent::executeSQL($sql,null);
        $ID=null;
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $ID = $row[0];
        }
        $ID++;
        $sql2 = "INSERT INTO $this->table Values($ID,'$name',$balance,$balance);";
        $res2 = parent::executeSQL($sql2,null);
    }

    public function DeleteRecord($id){
        try {
            $sql = "DELETE FROM $this->table WHERE $this->idname=$id";
            parent::executeSQL($sql,null);
        } catch (\Throwable $th) {
            $alert = "<script type='text/javascript'>alert('削除できません');</script>";
            echo $alert;
        }
    }

    public function UpdateRecord($columns,$values){
        for ($i=1; $i < count($columns); $i++) { 
            $sql = "UPDATE $this->table SET $columns[$i]={$values[$i]} WHERE $columns[0]=$values[0]";
            //$array=array($columns[$i],$values[$i],$columns[0],$values[0]);
            parent::executeSQL($sql,null);
        }
    }

    public function SelectTag_Pay(){
        $sql = "SELECT bank_id,bank FROM bank;";
        $res = parent::executeSQL($sql,null);
        $updateTag_Pay = "";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $updateTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
        }
        return $updateTag_Pay;
    }

    public function QueryBankName($id) {
        $sql = "SELECT bank FROM bank WHERE bank_id={$id};";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $name = $row[0];
        }
        return $name;
    }

    /*public function QuerryDBExist($id){
        $sql = "SELECT COUNT($this->idname) FROM MoneyDB WHERE $this->idname=$id;";
        $res = parent::executeSQL($sql,null);
        $data="";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data = $row[0]; 
        }
        return $data;
    }*/
}
?>