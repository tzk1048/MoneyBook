<?php
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
//$dbinput = new DBinput();
require_once('DBaggregate_day.php');
require_once('aggregate.php');
$aggregate = new Aggregate();

//当ページで表示される月
$id;

//今日の日付を取得（変更要）
$todayid='2021-05-30';

//前月or次月から来た場合は、表示する月を取得
//その他は今日の日付を挿入
if (isset($_POST['next']) OR isset($_POST['return'])) {
    $id =  $_POST['thisid'];
} else {
    $id = $todayid;
}

//年月の取得
//$year = $aggregate->AggregateIDtoYear($id);
//$month = $aggregate->AggregateIDtoMonth($id);

//当月のインスタンスを作成
$dbaggregatetoday = new DBaggregate_day($id);

//いらない？↓↓
//$maxid =  $dbaggregate->QueryAggregateMaxID();
//仮
/*$maxid = 202110;

if ($id>$maxid) {
    $dbaggregate->InsertNewMonth();
}

if ($id>$maxid) {
    $dbaggregate->InsertNewMonth();
}*/

//テーブルデータの一覧表示
//変更済
//$dateexpenditure = $dbviewer->SelectDatePayment($year,$month);
//$dateexpendituregraph = $dbviewer->CreateDatePaymentGraph($year,$month);
//$thisaggregate = $dbaggregatetoday->SelectMonthAggregate();
//$monthexpenditure = $dbviewer->SelectMonthPayment($year,$month);

//前月のインスタンスを作成
//変更済
/*$lastaggregate =[];
$dbaggregatelast;
if ($dbaggregatetoday->QueryAggregateMinID()!=$id) {
    $lastid = $dbaggregatetoday->QueryLastAggregateID();
    $lastid_year = $aggregate->AggregateIDtoYear($lastid);
    $lastid_month = $aggregate->AggregateIDtoMonth($lastid);
    $dbaggregatelast = new DBaggregate($lastid,$lastid_year,$lastid_month);
    $lastaggregate = $dbaggregatelast->SelectMonthAggregate();
    $thisaggregate['forward'] = $lastaggregate['balance'];
    if($dbaggregatelast->QueryAggregateMinID()!=$lastid) {
    $lastlastid = $dbaggregatelast->QueryLastAggregateID();
    $lastaggregate['forward'] =(new DBaggregate($lastlastid,$aggregate->AggregateIDtoYear($lastlastid),$aggregate->AggregateIDtoMonth($lastlastid)))->Balance();
    }
}

$name = ["支出","収入","収支","貯金","繰越","残高"];
                    $index = ['expenditure','income','profit','chokin','forward','balance'];*/

/*if(isset($_POST['next'])){
    $dbaggregate->InsertNewMonth();
}*/

?>
<!DOCTYPE html>
<html>
    <head>
        <meta  charset="utf-8" />
        <title><?php echo $year ."年" .$month ."月" ."支出集計";?></title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script type="text/javascript">
            function CheckDelete(){
                return confirm("削除してもよろしいですか？");
            }
        </script>
    </head>
    <body>
        <?php include ('nav.php');?>
        <div>
            <p>test</p>
            <p id="test"><?php echo $todayid;?></p>
            <p>test2</p>
            <p id="test2"></p>
            <button onclick="selectShop_Contents(1)">test-button</button>
            <p>test3</p>
            <p id="test3"><?php echo $dbaggregatetoday->SumPrice('payment_record');?></p>
            <p>test4</p>
            <p id="test4"></p>
            <p>test5</p>
            <p id="test5"></p>
            <p>test6</p>
            <p id="test6"></p>
            <p>test7</p>
            <p id="test7"></p>
            <h1></h1>
            <form action='' method='post'><input type='hidden' name='thisid' value='<?php echo date('Y-m-d',strtotime('-1 day',strtotime($id)));?>'><input type='submit' name='return' value='前日'></form>
            <form action='' method='post'><input type='hidden' name='thisid' value='<?php echo date('Y-m-d',strtotime('+1 day',strtotime($id)));?>'><input type='submit' name='next' value='翌日'></form>
            <h1><?php echo date('Y',strtotime($id)) ."年" .date('m',strtotime($id)) ."月" .date('d',strtotime($id)) ."日" ?></h1>
            <h2>集計</h2>
            <?php echo $dbaggregatetoday->DayAggregateTable();?>
            <h2>支出</h2>
            <?php echo $dbaggregatetoday->SelectDayPayment();?>
            <h2>収入</h2>
            <?php echo $dbaggregatetoday->SelectDayIncome();?>
            <h2>貯金</h2>
            <?php echo $dbaggregatetoday->SelectDayChokin();?>
        </div>
        <form action='' method='post'>
            <textarea name='memo'></textarea>
            <input type='submit' name='memobtn'>
        </form>
        <div> 
            <?php 
                /*if ($id != $dbaggregatetoday->QueryAggregateMinID()) {
                    echo "<form action='' method='post'><input type='hidden' value='{$dbaggregatetoday->QueryLastAggregateID()}' name='thisid'><input type='submit' value='前月' name='return'></form>";
                }*/
            ?>
        </div>
            
        <script language="JavaScript" type="text/javascript" src="aggregate.js"></script>
    </body>
</html>