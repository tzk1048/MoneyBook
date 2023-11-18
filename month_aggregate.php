<?php
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
//$dbinput = new DBinput();
require_once('DBaggregate.php');
require_once('aggregate.php');
$aggregate = new Aggregate();

//当ページで表示される月
$id;

//今日の日付を取得（変更要）
$todayid=202110;

//前月or次月から来た場合は、表示する月を取得
//その他は今日の日付を挿入
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
$dateexpenditure = $dbviewer->SelectDatePayment($year,$month);
$dateexpendituregraph = $dbviewer->CreateDatePaymentGraph($year,$month);
$thisaggregate = $dbaggregatetoday->SelectMonthAggregate();
$monthexpenditure = $dbviewer->SelectMonthPayment($year,$month);

//前月のインスタンスを作成
//変更済
$lastaggregate =[];
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
                    $index = ['expenditure','income','profit','chokin','forward','balance'];

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
            <p id="test"><?php echo $todayid .':'.$year .':' .$month?></p>
            <p>test2</p>
            <p id="test2"></p>
            <button onclick="selectShop_Contents(1)">test-button</button>
            <p>test3</p>
            <p id="test3"><?php echo $dbaggregatetoday->FirstDay();?></p>
            <p>test4</p>
            <p id="test4"><?php echo $lastaggregate[$index[0]] .":" .$thisaggregate[$index[0]] .":" .($lastaggregate[$index[0]]-$thisaggregate[$index[0]]);?></p>
            <p>test5</p>
            <p id="test5"><?php echo $dbaggregatetoday->QueryDiffAggregateID($todayid,$id);?></p>
            <p>test6</p>
            <p id="test6"></p>
            <p>test7</p>
            <p id="test7"></p>
            <h1><?php echo $year ."年" .$month ."月"; ?></h1>
            <h1><?php 
                $testid = $dbaggregatetoday->QueryNextAggregateID();
                echo $id ."+" .$testid ."+" .$aggregate->AggregateIDtoYear($testid) ."+" .$aggregate->AggregateIDtoMonth($testid); ?></h1>
            <h2>集計</h2>
            <table>
                <tr><th></th><th>今月</th><th>前月</th><th>前月比</th><th>平均</th><th>平均比</th></tr>
                <?php 
                    $name = ["支出","収入","収支","貯金","繰越","残高"];
                    $index = ['expenditure','income','profit','chokin','forward','balance'];
                    $average = $dbaggregatetoday->AggregateAverage();
                    /*$average[4]="-";
                    $average[5]="-";*/
                    $data = "";
                    for ($i=0; $i < 6; $i++) { 
                        if(array_key_exists($index[$i],$thisaggregate)){
                        $thisag=(int)$thisaggregate[$index[$i]];
                        } else {
                            $thisag="-";
                        }
                        $lastag;
                        $difference;
                        if(!(array_key_exists($index[$i], $lastaggregate)) OR empty($lastaggregate[$index[$i]])){
                            $lastag="-";
                            $difference="-";
                        } else {
                            $lastag=(int)$lastaggregate[$index[$i]];
                            $difference =$lastag-$thisag;
                        }
                        $avgDiff;
                        if(empty($average[$index[$i]])){
                            $average[$index[$i]]='-';
                            $avgDiff='-';
                        } else{
                            $avgDiff=sprintf("%+d",$thisag-$average[$index[$i]]);
                        }
                        $data .= "<tr><td>{$name[$i]}</td><td>{$thisag}</td><td>
                            {$lastag}</td><td>{$difference}</td><td>{$average[$index[$i]]}</td><td>{$avgDiff}</td></tr>\n";
                    }
                    //↑編集済
                    echo $data;
                ?>
            </table>
            <h2>支出</h2>
            <?php
                $thisrows = $dbaggregatetoday->MonthSumPrice('payment','pay_id');
                if(empty($dbaggregatelast)){
                    $lastrows=[];
                } else{
                    $lastrows = $dbaggregatelast->MonthSumPrice('payment','pay_id');
                }
                $avgrows = $dbaggregatetoday->MonthAveragePrice('payment','pay_id');
                echo $aggregate->MakeAggregatetable($thisrows,$lastrows,$avgrows);
            ?>
            <h2>収入</h2>
            <?php
                $thisrows = $dbaggregatetoday->MonthSumPrice('income','income_id');
                if(empty($dbaggregatelast)){
                    $lastrows=[];
                } else{
                    $lastrows = $dbaggregatelast->MonthSumPrice('income','income_id');
                }
                $avgrows = $dbaggregatetoday->MonthAveragePrice('income','income_id');
                echo $aggregate->MakeAggregatetable($thisrows,$lastrows,$avgrows);
            ?>
            <?php
                echo $dateexpendituregraph;
                echo $dateexpenditure;
                echo $monthexpenditure;
                $lastday = (new DateTimeImmutable)->modify('last day of' .$year .'-' .$month)->format('d');
                echo $lastday;
            ?>
            <?php
                if ($dbaggregatetoday->QueryDiffAggregateID($todayid,$id)<=1) {
                    echo "<form action='' method='post'><input type='hidden' name='thisid' value='{$dbaggregatetoday->QueryNextAggregateID()}'><input type='submit' name='next' value='次月'></form>";
                }
            ?>
        </div>
        <div> 
            <?php 
                if ($id != $dbaggregatetoday->QueryAggregateMinID()) {
                    echo "<form action='' method='post'><input type='hidden' value='{$dbaggregatetoday->QueryLastAggregateID()}' name='thisid'><input type='submit' value='前月' name='return'></form>";
                }
            ?>
        </div>
            
        <script language="JavaScript" type="text/javascript" src="aggregate.js"></script>
    </body>
</html>