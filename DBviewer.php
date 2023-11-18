<?php
require_once('db.php');
class DBviewer extends DB{

    //支出記録の表示(データベース名)
    //変更済
    public function SelectpaymentRecordAll($dbname){
        $sql = "SELECT id,date,bank.bank_id,bank,pay_id,payment,price,comment FROM (SELECT id,date,bank_id,payment.pay_id AS pay_id,payment,price,comment FROM $dbname INNER JOIN payment ON $dbname.pay_id=payment.pay_id) AS $dbname INNER JOIN bank ON $dbname.bank_id=bank.bank_id;";
        $res = parent::executeSQL($sql,null);
        $data = <<<EOF
        <div id="current_table_btn_area">
        <button class='current_table_btn' id='current_table_btn_small' onclick="Small('current_table','current_table_btn_small','最大化　▼')">最小化　▲</button>
        <button class='current_table_btn' id='current_table_btn_blind' onclick="Show('current_table','current_table_btn_blind','表示　▼')">非表示　▲</button>
        </div>
        <table class='recordlist' id='current_table'>
        EOF;
        $data .= "<tr><th>日付</th><th>支払い方法</th><th>カテゴリー</th><th>金額</th><th>備考</th><th id='current_table_insert_btn' colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= <<<EOF
            <tr id='{$row[0]}'>
            <td>{$row[1]}</td>
            <td id='{$row[2]}'>{$row[3]}</td>
            <td id='{$row[4]}'>{$row[5]}</td>
            <td>{$row[6]}</td><td>{$row[7]}</td>";
            EOF;

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='UPDATE' onClick='update_ex({$row[0]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[0]}'>
            <input type='submit' class='upbtn' name='delete' value='DELETE' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    //貯金記録の表示(データベース名)
    //変更済
    public function SelectoshichokinRecordAll($dbname){
        $sql = "SELECT id,date,chokin.chokin_id,chokin,price,bank.bank_id,bank,comment FROM $dbname INNER JOIN chokin ON $dbname.chokin_id=chokin.chokin_id INNER JOIN bank ON $dbname.bank_id=bank.bank_id;";
        $res = parent::executeSQL($sql,null);
        $data = <<<EOF
        <div id="current_table_btn_area">
        <button class='current_table_btn' id='current_table_btn_small' onclick="Small('current_table','current_table_btn_small','最大化　▼')">最小化　▲</button>
        <button class='current_table_btn' id='current_table_btn_blind' onclick="Show('current_table','current_table_btn_blind','表示　▼')">非表示　▲</button>
        </div>
        <table class='recordlist' id='current_table'>
        EOF;
        $data .= "<tr><th>日付</th><th>カテゴリー</th><th>金額</th><th>支払い方法</th><th>備考</th><th id='current_table_insert_btn' colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='{$row[0]}'><td>{$row[1]}</td><td id='{$row[2]}'>{$row[3]}</td><td>{$row[4]}</td><td id='{$row[5]}'>{$row[6]}</td><td>{$row[7]}</td>";
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='update_chokin({$row[0]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[0]}'>
            <input type='submit' class='upbtn' name='delete' value='削除' onClick='return CheckDelete()'>
            </form></td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectcreditAll($dbname){
        $sql = "SELECT id,date,credit.credit_id,credit,payment.pay_id,payment,price,comment,debit_date FROM {$dbname} INNER JOIN credit ON {$dbname}.credit_id=credit.credit_id INNER JOIN payment ON {$dbname}.pay_id=payment.pay_id;";
        $res = parent::executeSQL($sql,null);
        $data = <<<EOF
        <div id="current_table_btn_area">
        <button class='current_table_btn' id='current_table_btn_small' onclick="Small('current_table','current_table_btn_small','最大化　▼')">最小化　▲</button>
        <button class='current_table_btn' id='current_table_btn_blind' onclick="Show('current_table','current_table_btn_blind','表示　▼')">非表示　▲</button>
        </div>
        <table class='recordlist' id='current_table'>
        EOF;
        $data .= "<tr><th>日付</th><th>支払い方法</th><th>カテゴリー</th><th>金額</th><th>備考</th><th>引落日</th><th id='current_table_insert_btn' colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='{$row[0]}'><td>{$row[1]}</td><td id='{$row[2]}'>{$row[3]}</td><td id='{$row[4]}'>{$row[5]}</td><td>{$row[6]}</td><td>{$row[7]}</td><td>{$row[8]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='update_cre({$row[0]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[0]}'>
            <input type='submit' class='upbtn' name='delete' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectincomeRecordAll($dbname){
        $sql = "SELECT id,date,bank.bank_id,bank,income_id,income,price,comment FROM (SELECT id,date,bank_id,income.income_id AS income_id,income,price,comment FROM $dbname INNER JOIN income ON $dbname.income_id=income.income_id) AS $dbname INNER JOIN bank ON $dbname.bank_id=bank.bank_id;";
        $res = parent::executeSQL($sql,null);
        $data = <<<EOF
        <div id="current_table_btn_area">
        <button class='current_table_btn' id='current_table_btn_small' onclick="Small('current_table','current_table_btn_small','最大化　▼')">最小化　▲</button>
        <button class='current_table_btn' id='current_table_btn_blind' onclick="Show('current_table','current_table_btn_blind','表示　▼')">非表示　▲</button>
        </div>
        <table class='recordlist' id='current_table'>
        EOF;
        $data .= "<tr><th>日付</th><th>入金先</th><th>カテゴリー</th><th>金額</th><th>備考</th><th id='current_table_insert_btn' colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='{$row[0]}'><td>{$row[1]}</td><td id='{$row[2]}'>{$row[3]}</td><td id='{$row[4]}'>{$row[5]}</td><td>{$row[6]}</td><td>{$row[7]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='update_income({$row[0]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[0]}'>
            <input type='submit' class='upbtn' name='delete' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function SelectmovementRecordAll($status){
        $sql = "SELECT movement_{$status}.move_id,date,withdrawal_{$status}.id,withdrawal_{$status}.bank_id,bank1.bank,deposit_{$status}.id,deposit_{$status}.bank_id,bank2.bank,price,comment from movement_{$status} left outer join withdrawal_{$status} on movement_{$status}.move_id=withdrawal_{$status}.move_id left outer join deposit_{$status} on movement_{$status}.move_id=deposit_{$status}.move_id inner join bank as bank1 on withdrawal_{$status}.bank_id=bank1.bank_id inner join bank as bank2 on deposit_{$status}.bank_id=bank2.bank_id;";
        $res = parent::executeSQL($sql,null);
        $data = <<<EOF
        <div id="current_table_btn_area">
        <button class='current_table_btn' id='current_table_btn_small' onclick="Small('current_table','current_table_btn_small','最大化　▼')">最小化　▲</button>
        <button class='current_table_btn' id='current_table_btn_blind' onclick="Show('current_table','current_table_btn_blind','表示　▼')">非表示　▲</button>
        </div>
        <table class='recordlist' id='current_table'>
        EOF;
        $data .= "<tr><th>日付</th><th>出金元</th><th>入金先</th><th>金額</th><th>備考</th><th id='current_table_insert_btn' colspan='2'></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr id='{$row[0]}'><td>{$row[1]}</td><td id='{$row[3]}'>{$row[4]}</td><td id='{$row[6]}'>{$row[7]}</td><td>{$row[8]}</td><td>{$row[9]}</td>";

            /*for ($i=1; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }*/
            //更新ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'><input type='button' class='upbtn' value='更新' onClick='update_move({$row[0]},{$row[2]},{$row[5]})'></td>
            eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td class='upbtn-td'>
            <form method='post' action=''>
            <input type='hidden' name='id' id='Daleteid' value='{$row[0]}'>
            <input type='submit' class='upbtn' name='delete' value='削除' onClick='return CheckDelete()'>
            </form>
            </td>
            eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    //指定した月のカテゴリーごとの支出を表示
    //変更済
    public function SelectMonthPayment($year,$month){
        $sql = "SELECT payment,IFNULL(SUM(price),0) FROM payment LEFT OUTER JOIN (SELECT * FROM payment_record WHERE YEAR(date) = 2021 AND MONTH(date) = 8) AS payment_record ON payment.pay_id=payment_record.pay_id GROUP BY payment.pay_id ORDER BY payment.pay_id;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='current_table'>";
        $data .= "<tr><th>カテゴリー</th><th>金額</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr>";
            for ($i=0; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    public function CokinDateSum($dbname){
        $sql = "SELECT date,SUM($dbname.price) AS price FROM $dbname INNER JOIN Chokin_Contents ON $dbname.ConID=Chokin_Contents.ConID WHERE CateID=5 group by date;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='chokindatesum'>";
        $data .= "<tr><th>日付</th><th>金額</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr>";
            for ($i=0; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    //多分いらない
    public function InsertBankBalance(){
        $sql1 = "SELECT SUM(price) FROM MoneyDB WHERE 100 < FromBID AND GROUP BY FromBID;";
        $res1 = parent::executeSQL($sql1,null);
        $sql2 = "SELECT SUM(price) FROM MoneyDB WHERE 100 < ToBID AND GROUP BY ToBID;";
        $res2 = parent::executeSQL($sql2,null);
        foreach($rows = $res1->fetchAll(PDO::FETCH_NUM) as $row){
            $data1[] = $row[0];
        }
        foreach($rows = $res2->fetchAll(PDO::FETCH_NUM) as $row){
            $data2[] = $row[0];
        }
        $sql = "UPDATE Bank SET Balance = ? WHERE BankID = ?;";
        for ($i=0; $i < count($data2); $i++) { 
            if (empty($data1[$i])) {
                $data1[$i] = 0;
            }
            if (empty($data2[$i])) {
                $data2[$i] = 0;
            }
            $balance = $data2[$i] - $data1[$i];
            $num = 101 + $i;
            $array = array($balance,$num);
            parent::executeSQL($sql,$array);
        }
    }

    public function SelectBankBalance(){
        $this->InsertBankBalance();
        $sql = "SELECT BankName,Balance FROM Bank WHERE 100 < BankID;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='bankbalance'>";
        $data .= "<tr><th>収納機関</th><th>残高</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr>";
            for ($i=0; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    //レコード削除
    //変更済（変更前:Deletedata($id,$dbname))
    public function DeleteRecord($id,$dbname){
        $sql = "DELETE FROM {$dbname} WHERE id=?";
        $array = array($id);
        parent::executeSQL($sql, $array);
    }

    //paymentテーブルの表示
    //入力ページでカテゴリー名を押して入力が可能
    //tableのid名の変更要
    //変更済
    public function SelectPaymentAll(){
        $sql = "SELECT pay_id-10000 AS pay_id,payment FROM payment;";
        $res = parent::executeSQL($sql,null);
        $ContentsList = <<<EOF
        <button id='payment_list_btn' onclick="Show('payment_list','payment_list_btn','▼')">▲</button>
        <table class='recordlist' id='payment_list'>
        EOF;
        $ContentsList .= "<tr><th>ID</th><th>カテゴリー</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $id = 10000+$row[0];
            $ContentsList .= "<tr onclick='selectShop_Contents({$id})'>";
            for ($i=0; $i < count($row); $i++) {
                $ContentsList .="<td>{$row[$i]}</td>";
            }
            $ContentsList .= "</tr>\n";
        }
        $ContentsList .= "</table>\n";
        return $ContentsList;
    }

    public function SelectIncomeAll(){
        $sql = "SELECT income_id-20000 AS income_id,income FROM income;";
        $res = parent::executeSQL($sql,null);
        $ContentsList = "<table class='recordlist' id='IncomeList'>";
        $ContentsList .= "<tr><th>ID</th><th>カテゴリー</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $id = 20000+$row[0];
            $ContentsList .= "<tr onclick='selectIncome({$id})'>";
            for ($i=0; $i < count($row); $i++) {
                $ContentsList .="<td>{$row[$i]}</td>";
            }
            $ContentsList .= "</tr>\n";
        }
        $ContentsList .= "</table>\n";
        return $ContentsList;
    }

    //指定した月の日毎のpayment_recordを表示
    //変更済
    public function SelectDatePayment($year,$month){
        $sql = "SELECT date,sum(price) AS price FROM payment_record WHERE year(date)=$year AND month(date)=$month GROUP BY date;";
        $res = parent::executeSQL($sql,null);
        $data = "<table class='recordlist' id='dateexpenditure'>";
        $data .= "<tr><th>日付</th><th>支出</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            $data .= "<tr>";
            for ($i=0; $i < count($row); $i++) {
                $data .="<td>{$row[$i]}</td>";
            }
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    //指定した月の日毎のpayment_recordをグラフで表示
    //変更済
    public function CreateDatePaymentGraph($year,$month){
        $sql = "SELECT DAY(date),SUM(price) as price FROM payment_record WHERE YEAR(date)=$year AND MONTH(date)=$month GROUP BY date;";
        $res = parent::executeSQL($sql,null);
        $data = "<ul class='dateexpend_graph'>";
        $num = 1;
        $lastday = (new DateTimeImmutable)->modify('last day of ' .$year .'-' .$month)->format('d');
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
            //$data .="<li>{$num},{$row[0]},{$row[1]}</li>";
            while($num!=$row[0]){
                $data .= "<li><div class='dex' id='0'></div><p>$num</p></li>\n";
                $num++;
            }
            $data .= "<li><div class='dex' id='{$row[1]}'></div><p>{$row[0]}</p></li>\n";
            $num++;
        }
        while($num<=$lastday){
            $data .= "<li><div class='dex' id='0'></div><p>$num</p></li>\n";
            $num++;
        }
        $data .= "</ul>\n";
        return $data;
    }
}
?>