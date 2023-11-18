<?php
require_once('db.php');
class DBstatement extends DB{

    public function SelectTag_Bank(){
        $sql = "SELECT bank_id,bank FROM bank;";
        $res = parent::executeSQL($sql,null);
        $selectTag_Pay = "<select name='BankID'>\n";
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $selectTag_Pay .= "<option value=" .$row['bank_id'] .">"
                            .$row['bank'] ."</option>\n";
        }
        $selectTag_Pay .="</select>";
        return $selectTag_Pay;
    }

    public function SelectBankStatementALl($bankid,$in,$out){
        $sql = "SELECT date,category,detail,price FROM (SELECT date,'支出' AS category,payment AS detail,price*-1 AS price,bank_id FROM payment_record INNER JOIN payment ON payment_record.pay_id=payment.pay_id UNION (SELECT date,'収入' AS category,income AS detail,price AS price,bank_id FROM income_record INNER JOIN income ON income_record.income_id=income.income_id) UNION (SELECT date,'預入' AS category,bank AS detail,price,movement.bank_id AS bank_id FROM (SELECT date,price,frombank_id as bank_id,bank_id as tobank_id FROM (SELECT movement_record.move_id AS move_id,date,price,bank_id AS frombank_id FROM movement_record LEFT OUTER JOIN deposit_record ON movement_record.move_id=deposit_record.move_id) AS move_deposit LEFT OUTER JOIN withdrawal_record ON move_deposit.move_id=withdrawal_record.move_id) AS movement INNER JOIN bank ON movement.tobank_id=bank.bank_id) UNION(SELECT date,'引出' AS category,bank AS detail,price*-1 AS price,movement.bank_id AS bank_id FROM (SELECT date,price,frombank_id,bank_id FROM (SELECT movement_record.move_id AS move_id,date,price,bank_id AS frombank_id FROM movement_record LEFT OUTER JOIN deposit_record ON movement_record.move_id=deposit_record.move_id) AS move_deposit RIGHT OUTER JOIN withdrawal_record ON move_deposit.move_id=withdrawal_record.move_id) AS movement LEFT OUTER JOIN bank ON movement.frombank_id=bank.bank_id) UNION (SELECT create_day AS date,'繰越' AS category,'' AS detail,forward AS price,bank_id FROM bank)) AS bankbook WHERE bankbook.bank_id={$bankid} ORDER BY bankbook.date;";
        $res = parent::executeSQL($sql,null);
        $sum = 0;
        $data = "<table class='recordlist'>";
        $data .= "<tr><th>日付</th><th>出入先</th><th>項目</th><th>金額</th><th>残高</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            if($in==false AND $row[3]>0) {
                continue;
            }
            if ($out==false AND $row[3]<0) {
                continue;
            }
            $data .= "<tr>";
            for ($i=0; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }
            $sum += $row[3];
            $data .= "<td>{$sum}</td></tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectBankStatement($bankid,$start,$end,$in,$out){
        $sql = "SELECT date,category,detail,price FROM (SELECT date,'支出' AS category,payment AS detail,price*-1 AS price,bank_id FROM payment_record INNER JOIN payment ON payment_record.pay_id=payment.pay_id UNION (SELECT date,'収入' AS category,income AS detail,price AS price,bank_id FROM income_record INNER JOIN income ON income_record.income_id=income.income_id) UNION (SELECT date,'預入' AS category,bank AS detail,price,movement.bank_id AS bank_id FROM (SELECT date,price,frombank_id as bank_id,bank_id as tobank_id FROM (SELECT movement_record.move_id AS move_id,date,price,bank_id AS frombank_id FROM movement_record LEFT OUTER JOIN deposit_record ON movement_record.move_id=deposit_record.move_id) AS move_deposit LEFT OUTER JOIN withdrawal_record ON move_deposit.move_id=withdrawal_record.move_id) AS movement INNER JOIN bank ON movement.tobank_id=bank.bank_id) UNION(SELECT date,'引出' AS category,bank AS detail,price*-1 AS price,movement.bank_id AS bank_id FROM (SELECT date,price,frombank_id,bank_id FROM (SELECT movement_record.move_id AS move_id,date,price,bank_id AS frombank_id FROM movement_record LEFT OUTER JOIN deposit_record ON movement_record.move_id=deposit_record.move_id) AS move_deposit RIGHT OUTER JOIN withdrawal_record ON move_deposit.move_id=withdrawal_record.move_id) AS movement LEFT OUTER JOIN bank ON movement.frombank_id=bank.bank_id) UNION (SELECT create_day AS date,'繰越' AS category,'' AS detail,forward AS price,bank_id FROM bank)) AS bankbook WHERE bankbook.bank_id={$bankid} AND date >= '$start' AND date <= '$end' ORDER BY bankbook.date;";
        $res = parent::executeSQL($sql,null);
        $sum = 0;
        $data = "<table class='recordlist'>";
        $data .= "<tr><th>日付</th><th>出入先</th><th>項目</th><th>金額</th><th>残高</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            if($in==false AND $row[3]>0) {
                continue;
            }
            if ($out==false AND $row[3]<0) {
                continue;
            }
            $data .= "<tr>";
            for ($i=0; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }
            $sum += $row[3];
            $data .= "<td>{$sum}</td></tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    //変更必要
    public function SelectBankData($bankid) {
        $sql = "SELECT bank FROM bank WHERE bank_id = $bankid;";
        $res = parent::executeSQL($sql,null);
        $sql1 = "SELECT SUM(price) FROM (SELECT date,'支出' AS category,payment AS detail,price*-1 AS price,bank_id FROM payment_record INNER JOIN payment ON payment_record.pay_id=payment.pay_id UNION (SELECT date,'収入' AS category,income AS detail,price AS price,bank_id FROM income_record INNER JOIN income ON income_record.income_id=income.income_id) UNION (SELECT date,'預入' AS category,bank AS detail,price,movement.bank_id AS bank_id FROM (SELECT date,price,frombank_id as bank_id,bank_id as tobank_id FROM (SELECT movement_record.move_id AS move_id,date,price,bank_id AS frombank_id FROM movement_record LEFT OUTER JOIN deposit_record ON movement_record.move_id=deposit_record.move_id) AS move_deposit LEFT OUTER JOIN withdrawal_record ON move_deposit.move_id=withdrawal_record.move_id) AS movement INNER JOIN bank ON movement.tobank_id=bank.bank_id) UNION(SELECT date,'引出' AS category,bank AS detail,price*-1 AS price,movement.bank_id AS bank_id FROM (SELECT date,price,frombank_id,bank_id FROM (SELECT movement_record.move_id AS move_id,date,price,bank_id AS frombank_id FROM movement_record LEFT OUTER JOIN deposit_record ON movement_record.move_id=deposit_record.move_id) AS move_deposit LEFT OUTER JOIN withdrawal_record ON move_deposit.move_id=withdrawal_record.move_id) AS movement INNER JOIN bank ON movement.frombank_id=bank.bank_id) UNION (SELECT create_day AS date,'繰越' AS category,'' AS detail,forward AS price,bank_id FROM bank)) AS bankbook WHERE bankbook.bank_id=$bankid;";
        $res1 = parent::executeSQL($sql1,null);
        $banks = $res->fetchAll(PDO::FETCH_NUM);
        $sums = $res1->fetchAll(PDO::FETCH_NUM);
        foreach(array_map(NULL,$banks,$sums) as [$bank,$sum]){
            $bankdata[0]=$bank[0];
            $bankdata[1]=$sum[0];
        }
        return $bankdata;
    }
}
?>