<?php
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
//$dbinput = new DBinput();
require_once('DBaggregate.php');
require_once('DBstatement.php');
$dbstatement = new DBstatement();
require_once('aggregate.php');
$aggregate = new Aggregate();

$id;

$todayid=202105;

if (isset($_POST['next']) OR isset($_POST['return'])) {
    $id =  $_POST['thisid'];
} else {
    $id = $todayid;
}

//年月の取得
$year = $aggregate->AggregateIDtoYear($id);
$month = $aggregate->AggregateIDtoMonth($id);

//当月のインスタンスを作成
$dbaggregatetoday = new DBaggregate($id,$year,$month);

$statementsheet="";
$bankname="";
$bankbalance="";
$bankid=100;
if (isset($_POST['Bank'])) {
    $bankid=$_POST['BankID'];
    if ($_POST['range']=="all") {
        $statementsheet=$dbstatement->SelectBankStatementAll($_POST['BankID'],isset($_POST['in']),isset($_POST['out']));
        $bankname=$dbstatement->SelectBankData($bankid)[0];
        $bankbalance="残高:".$dbstatement->SelectBankData($bankid)[1];
    } elseif($_POST['start']=="" OR $_POST['end']=="") {
        $statementsheet="範囲を選択してください";
    } else {
        $statementsheet=$dbstatement->SelectBankStatement($_POST['BankID'],$_POST['start'],$_POST['end'],isset($_POST['in']),isset($_POST['out']));
        $bankname=$dbstatement->SelectBankData($bankid)[0];
        $bankbalance="残高:".$dbstatement->SelectBankData($bankid)[1];
    }
    
}

/*if (isset($_POST['next'])) {
    $id = $_POST['thisid'];
} else {
    $id = $todayid;
}

if (isset($_POST['return'])) {
    $id = $_POST['lastid'];
}*/

$maxid =  $dbaggregatetoday->QueryAggregateMaxID();

if ($id>$maxid) {
    $dbaggregatetoday->InsertNewMonth();
}

/*if ($id>$maxid) {
    $dbaggregate->InsertNewMonth();
}*/

//年月の取得
$year = $dbaggregatetoday->AggregateIDtoYear($id);
$month = $dbaggregatetoday->AggregateIDtoMonth($id);

//テーブルデータの一覧表示
/*$dateexpenditure = $dbviewer->SelectDateExpenditure($year,$month);
$dateexpendituregraph = $dbviewer->CreateDateExpenditureGraph($year,$month);
$aggregate = $dbaggregate->SelectMonthAggregate($id);
$lastaggregate;
if ($dbaggregate->QueryAggregateMinID()!=$id) {
    $lastaggregate = $dbaggregate->SelectMonthAggregate($dbaggregate->QueryLastAggregateID($id));
}
$monthexpenditure = $dbviewer->SelectMonthExpenditure($year,$month);*/

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
            <!--<p>test</p>
            <p id="test"></p>
            <p>test2</p>
            <p id="test2"></p>
            <button onclick="selectShop_Contents(1)">test-button</button>
            <p>test3</p>
            <p id="test3"></p>
            <p>test4</p>
            <p id="test4"></p>
            <p>test5</p>
            <p id="test5"></p>
            <p>test6</p>
            <p id="test6"></p>
            <p>test7</p>
            <p id="test7"></p>-->
            
        </div>
        <div>
            <form action="" method="post">
                <?php echo $dbstatement->SelectTag_Bank()?>
                <input type="radio" name="range" value="all" checked>全て
                <input type="radio" name="range" value="part">範囲指定
                <input type="date" name="start"> ~ <input type="date" name="end">
                <input type="checkbox" name="in" value="in" checked>入金
                <input type="checkbox" name="out" value="out" checked>出金
                <input type="submit" value="表示" name="Bank">
            </form>
        </div>
        <?php echo "<h1>{$bankname}</h1>\n
                    <p>{$bankbalance}</p>\n
                    {$statementsheet}"; ?>
        <div> 
            <?php 
                if ($id != $dbaggregatetoday->QueryAggregateMinID()) {
                    echo "<form action='' method='post'><input type='hidden' value='{$dbaggregatetoday->QueryLastAggregateID($id)}' name='thisid'><input type='submit' value='前月' name='return'></form>";
                }
            ?>
        </div>
            
        <script language="JavaScript" type="text/javascript" src="aggregate.js"></script>
    </body>
</html>