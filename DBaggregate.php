<?php
require_once('db.php');
class DBaggregate extends DB{

    public $id;
    public $year;
    public $month;

    public function __construct($id,$year,$month) {
        $this->id = $id;
        $this->year = $year;
        $this->month = $month;
    }

    /*public function test() {
        return $this->year .'-'.sprintf("%02d",$this->month) .'-01';
    }*/

    /*public function month() {
        return (string)$this->AggregateIDtoMonth();
    }*/

    //当月の最初の日
    public function FirstDay() {
        $month = $this->year .'-' .$this->month;
        $firstday = date('Y-m-d', strtotime('first day of ' . $month));
        return $firstday;
    }

    //当月の最終日
    public function LastDay() {
        //$lastday='2021-08-31';
        $month = $this->year .'-' .$this->month;
        $lastday = date('Y-m-d', strtotime('last day of ' . $month));
        return $lastday;
    }

    //当月の残金
    public function Balance() {
        $firstday = $this->QueryAggregateMinDate();

        $nextid = $this->QueryNextAggregateID();
        $nextmonth = $this->AggregateIDtoYear($nextid) .'-' .$this->AggregateIDtoMonth($nextid);
        $nextfirstday = date('Y-m-d', strtotime('first day of ' . $nextmonth));

        $sql1 = "SELECT SUM(forward) FROM bank";
        $res1 = parent::executeSQL($sql1,null);
        foreach($rows = $res1->fetchAll(PDO::FETCH_NUM) as $row){ 
            $bankforward = $row[0];
        }

        $income = $this->SumPrice('income_record',$firstday,$nextfirstday);
        $payment = $this->SumPrice('payment_record',$firstday,$nextfirstday);
        $credit = $this->SumPriceCredit($firstday,$nextfirstday);
        $chokin = $this->SumPriceChokin($firstday,$nextfirstday);
        
        $balance = $bankforward+$income-$payment-$credit-$chokin;
        return $balance;
    }

    public function SumPrice($table,$firstdate,$nextdate) {
        $sql = "SELECT SUM(price) FROM $table WHERE DATE(date)>=DATE('$firstdate') AND DATE(date)<DATE('$nextdate')";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $sum = $row[0];
        }
        return $sum;
    }

    public function SumPriceCredit($firstdate,$nextdate) {
        $sql = "SELECT SUM(price) FROM credit_record WHERE DATE(debit_date)>=DATE('$firstdate') AND DATE(debit_date)<DATE('$nextdate')";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $credit = $row[0];
        }
        return $credit;
    }

    public function SumPriceChokin($firstdate,$nextdate) {
        $sql = "SELECT SUM(price) FROM chokin_record INNER JOIN chokin ON chokin_record.chokin_id=chokin.chokin_id WHERE DATE(date)>=DATE('$firstdate') AND DATE(date)<DATE('$nextdate')";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $chokin = $row[0];
        }
        return $chokin;
    }

        //$forward = $this->SelectMonthForward($lastid);
        /*$cash = $this->SelectMonthColumn(1,'date',$id);
        $credit = $this->SelectMonthColumn(4,'putdate',$id);
        $income = $this->SelectMonthColumn(2,'date',$id);
        $chokin = $this->SelectMonthColumn(5,'date',$id);
        $expenditure = $cash + $credit;
        $in_ex = $income - $expenditure - $chokin;
        $balance = $forward + $in_ex;
        $data = [$id,$firstday,$lastday,$forward,$cash,$credit,$expenditure,$income,$chokin,$in_ex,$balance];*/

    //当月の集計を配列にする
    //変更済
    public function SelectMonthAggregate(){
        $data['id']=$this->id;
        $data['firstday']=$this->FirstDay();
        $data['lastday']=$this->LastDay();
        $nextfirstday = (new DateTime($data['lastday'] .'+1 day'))->format('Y-m-d');
        $data['payment']=$this->SumPrice('payment_record',$data['firstday'],$nextfirstday);
        $data['income'] = $this->SumPrice('income_record',$data['firstday'],$nextfirstday);
        $data['credit'] = $this->SumPriceCredit($data['firstday'],$nextfirstday);
        $data['chokin'] =$this->SumPriceChokin($data['firstday'],$nextfirstday);
        $data['expenditure'] = $data['payment'] + $data['credit'];
        $data['profit'] = $data['income']-$data['expenditure']-$data['chokin'];
        $data['balance'] = $this->Balance();
        
        return $data;
    }

    //編集済
    public function AggregateAverage(){
        //$firstday = $this->QueryAggregateMinID();
        //$firstday = $this->FirstDay();
        //$nextid = $this->QueryNextAggregateID();
        //$nextid_year = $this->AggregateIDtoYear($nextid);
        //$nextid_month = $this->AggregateIDtoMonth($nextid);
        //$nextfirstday = (new DateTime($nextid_year .'-' .$nextid_month .'-01'))->format('Y-m-d');
        $todayid= $this->QueryTodayID();
        $count = $this->QueryDiffAggregateID($this->QueryAggregateMinID(),$todayid);
        $payment=floor($this->AveragePrice('payment_record',$count));
        $data['income'] = floor($this->AveragePrice('income_record',$count));
        $credit = floor($this->AveragePriceCredit($count));
        $data['chokin'] =floor($this->AveragePriceChokin($count));
        $data['expenditure'] = $payment + $credit;
        $data['profit'] = $data['income']-$data['expenditure']-$data['chokin'];
        /*$sql = "SELECT AVG(expenditure),AVG(income),AVG(in_ex),AVG(chokin) FROM aggregate;";
        $res = parent::executeSQL($sql,null);
        $array;
        foreach ($rows = $res->fetchAll(PDO::FETCH_NUM) as $row) {
            for ($i=0; $i < count($row); $i++) { 
                $array[$i]=round($row[$i]);
            }
        }*/
        return $data;
    }

    public function QueryTodayID() {
        $todayid = date('Ym');
        return $todayid;
    }

    public function AveragePrice($table,$count) {
        $sql = "SELECT SUM(price) FROM $table;";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $sum = $row[0];
        }
        return $sum/$count;
    }

    public function AveragePriceCredit($count) {
        $sql = "SELECT SUM(price) FROM credit_record;";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $credit = $row[0];
        }
        return $credit/$count;
    }

    public function AveragePriceChokin($count) {
        $sql = "SELECT SUM(price) FROM chokin_record INNER JOIN chokin ON chokin_record.chokin_id=chokin.chokin_id;";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $chokin = $row[0];
        }
        return $chokin/$count;
    }

    /*
    //指定したidの月の項目別支出
    //編集済
    public function MonthExpenditure($year,$month){
        //$year =  $this->AggregateIdtoYear($id);
        //$month = $this->AggregateIdtoMonth($id);
        $sql = "SELECT payment,IFNULL(SUM(price),0) FROM payment LEFT OUTER JOIN (SELECT * FROM payment_record WHERE year(date) = $year AND month(date) = $month) AS month_record ON payment.pay_id=month_record.pay_id GROUP BY payment.pay_id ORDER BY payment.pay_id;";
        $res = parent::executeSQL($sql,null);
        return $res;
    }

    //編集済
    public function MonthPaymentSheet(){
        $thisres = $this->MonthExpenditure($this->year,$this->month);
        $lastid = $this->QueryLastAggregateID();;
        $lastres = $this->MonthExpenditure($this->AggregateIDtoYear($lastid),$this->AggregateIDtoMonth($lastid));
        $sql = "SELECT IFNULL(AVG(sumprice),0) FROM payment LEFT OUTER JOIN (SELECT MONTH(date),pay_id,SUM(price) AS sumprice FROM payment_record GROUP BY pay_id,MONTH(date)) AS month_record ON payment.pay_id=month_record.pay_id GROUP BY payment.pay_id ORDER BY payment.pay_id;";
        $res = parent::executeSQL($sql,null);
        $data = "";
        $thisrows = $thisres->fetchAll(PDO::FETCH_NUM);
        $lastrows = $lastres->fetchALL(PDO::FETCH_NUM);
        $avgrows = $res->fetchAll(PDO::FETCH_NUM);
        foreach(array_map(NULL,$thisrows,$lastrows,$avgrows) as [$thisrow,$lastrow,$avgrow]){
            $data .= "<tr><td>{$thisrow[0]}</td><td>{$thisrow[1]}</td><td>{$lastrow[1]}</td><td>"
                .sprintf("%+d",$thisrow[1]-$lastrow[1]) ."</td><td>" .round($avgrow[0]) ."</td><td>" 
                .sprintf("%+d",$thisrow[1]-$avgrow[0]) ."</td></tr>\n";
        }
        return $data;
    }

    public function MonthIncome($year,$month){
        $sql = "SELECT income,IFNULL(SUM(price),0) FROM income LEFT OUTER JOIN (SELECT * FROM income_record WHERE year(date) = $year AND month(date) = $month) AS month_record ON income.income_id=month_record.income_id GROUP BY income.income_id ORDER BY income.income_id;";
        $res = parent::executeSQL($sql,null);
        return $res;
    }

    public function MonthIncomeSheet(){
        $thisres = $this->MonthIncome($this->year,$this->month);
        $lastid = $this->QueryLastAggregateID();;
        $lastres = $this->MonthIncome($this->AggregateIDtoYear($lastid),$this->AggregateIDtoMonth($lastid));
        $sql = "SELECT IFNULL(AVG(sumprice),0) FROM income LEFT OUTER JOIN (SELECT MONTH(date),income_id,SUM(price) AS sumprice FROM income_record GROUP BY income_id,MONTH(date)) AS month_record ON income.income_id=month_record.income_id GROUP BY income.income_id ORDER BY income.income_id;";
        $res = parent::executeSQL($sql,null);
        $data = "";
        $thisrows = $thisres->fetchAll(PDO::FETCH_NUM);
        $lastrows = $lastres->fetchALL(PDO::FETCH_NUM);
        $avgrows = $res->fetchAll(PDO::FETCH_NUM);
        foreach(array_map(NULL,$thisrows,$lastrows,$avgrows) as [$thisrow,$lastrow,$avgrow]){
            $data .= "<tr><td>{$thisrow[0]}</td><td>{$thisrow[1]}</td><td>{$lastrow[1]}</td><td>"
                .sprintf("%+d",$thisrow[1]-$lastrow[1]) ."</td><td>" .round($avgrow[0]) ."</td><td>" 
                .sprintf("%+d",$thisrow[1]-$avgrow[0]) ."</td></tr>\n";
        }
        return $data;
    }*/

    public function MonthSumPrice($table,$idname) {
        $sql = "SELECT $table,IFNULL(SUM(price),0) FROM $table LEFT OUTER JOIN (SELECT * FROM {$table}_record WHERE year(date) = $this->year AND month(date) = $this->month) AS month_record ON {$table}.{$idname}=month_record.{$idname} GROUP BY {$table}.{$idname} ORDER BY {$table}.{$idname};";
        $res = parent::executeSQL($sql,null);
        return $res->fetchALL(PDO::FETCH_NUM);
    }

    public function MonthAveragePrice($table,$idname) {
        $sql = "SELECT IFNULL(AVG(sumprice),0) FROM $table LEFT OUTER JOIN (SELECT MONTH(date),{$idname},SUM(price) AS sumprice FROM {$table}_record GROUP BY {$idname},MONTH(date)) AS month_record ON {$table}.{$idname}=month_record.{$idname} GROUP BY {$table}.{$idname} ORDER BY {$table}.{$idname};";
        $res = parent::executeSQL($sql,null);
        return $res->fetchALL(PDO::FETCH_NUM);
    }

    //変更済
    public function QueryLastAggregateID(){
        if ($this->id%100==1) {
            $lastid = $this->id-89;
        }else{
            $lastid = $this->id-1;
        }
        return $lastid;
    }

    //変更済
    public function QueryNextAggregateID(){
        if ((int)$this->id%100==12) {
            $nextid = $this->id+89;
        }else{
            $nextid =(int)$this->id+1;
        }
        return $nextid;
    }

    public function QueryAggregateMaxID(){
        $sql = "SELECT MAX(id) FROM aggregate";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $maxid = $row[0];
        }
        return $maxid;
    }

    //データの集計開始月を検索（一番古いbankのレコード作成年月を表示）
    //変更済
    public function QueryAggregateMinDate(){
        $sql = "SELECT MIN(create_day) FROM bank";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $minday = $row[0];
        }
        $minid = (new DateTime($minday))->format('Y-m-d');
        return $minid;
    }

    public function QueryAggregateMinID(){
        $sql = "SELECT MIN(create_day) FROM bank";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $minday = $row[0];
        }
        $minid = (new DateTime($minday))->format('Ym');
        return $minid;
    }

    public function AggregateIDtoYear($id){
        $year = floor((int)$id / 100);
        return $year;
    }

    public function AggregateIDtoMonth($id){
        $month = (int)$id % 100;
        return sprintf("%02d",$month);
    }

    //↓↓いる？
    public function SelectMonthForward($lastid){
        $sql = "SELECT balance FROM aggregate WHERE id = $lastid";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data = $row[0];
        }
        return $data;
    }

    public function SelectMonthColumn($CateID,$date,$id){
        $sql = "SELECT SUM(price) FROM MoneyDB WHERE CateID=$CateID AND DATE_FORMAT($date,'%Y%m')=$id";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data = $row[0];
        }
        return $data;
    }

    //id2はid1の何ヶ月後か検索
    public function QueryDiffAggregateID($id1,$id2){
        $diff;
        $year1 = $this->AggregateIdtoYear($id1);
        $month1 = $this->AggregateIdtoMonth($id1);
        $year2 = $this->AggregateIdtoYear($id2);
        $month2 = $this->AggregateIdtoMonth($id2);
        if ($year1==$year2) {
            $diff=$month2-$month1;
        }  else {
            $diff=($year2-$year1-1)*12+(12-$month1)+$month2;
        }
        return $diff;
    }

    /*public function SelectMonthCash($id){
        $sql = "SELECT SUM(price) FROM MoneyDB WHERE CateID=1 AND DATE_FORMAT(date,'%Y%m')=$id";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data = $row[0];
        }
        return $data;
    }

    public function SelectMonthCredit($id){
        $sql = "SELECT SUM(price) FROM MoneyDB WHERE CateID=4 AND DATE_FORMAT(putdate,'%Y%m')=$id";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data = $row[0];
        }
        return $data;
    }

    public function SelectMonthIncome($id){
        $sql = "SELECT SUM(price) FROM MoneyDB WHERE CateID=2 AND DATE_FORMAT(date,'%Y%m')=$id";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data = $row[0];
        }
        return $data;
    }

    public function SelectMonthChokin($id){
        $sql = "SELECT SUM(price) FROM MoneyDB WHERE CateID=5 AND DATE_FORMAT(date,'%Y%m')=$id";
        $res = parent::executeSQL($sql,null);
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){ 
            $data = $row[0];
        }
        return $data;
    }*/
}
?>