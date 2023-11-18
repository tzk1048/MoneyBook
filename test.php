<?php
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput2.php');
$dbinput = new DBinput('credit','credit_id');
require_once('DBaggregate.php');
$dbaggregate = new DBaggregate(202108,2021,8);
require_once('aggregate.php');
$aggregatequery = new Aggregate();

//テーブルデータの一覧表示
$data = $dbviewer->SelectoshichokinRecordAll('chokin_current');
$OshichokinList = $dbinput->input_oshichokin();
$oshichokindatesum = $dbviewer->CokinDateSum('currentDB');
//$bankbalance = $dbviewer->SelectBankBalance();
//$monthexpenditure = $dbviewer->SelectMonthExpenditure(2021,8);
$dateexpenditure = $dbviewer->SelectDatePayment(2021,7);
$dateexpendituregraph = $dbviewer->CreateDatePaymentGraph(2021,7);

//MoneyDBに挿入
if(isset($_POST['insertdb'])){
    $dbinput->InsertDBfromcurrent();
    $dbinput->DeletecurrentDBAll();
    header("location:viewer_expenditure.php");
}

//削除処理
if (isset($_POST['delete'])) {
    $dbviewer->Deletedata($_POST['id'],'currentDB');
    $data = $dbviewer->SelectoshichokinAll('currentDB');
}

$input="";

if(isset($_POST['input_oshichokin'])){
    for ($i=1; $i < $_POST['chokincon_len']; $i++) { 
        $date = $_POST['date'];
        $num = $i + 32000;
        $con = $_POST[$num];
        $price = $dbinput->query_oshichokinPrice($num);
        /*$input .= $num;
        $input .= ":";
        $input .= $price;
        $input .= "*";
        $input .= $con;*/
        for ($n=0; $n < $con; $n++) { 
            $dbinput->InsertcurrentDB($date,5,108,2,$num,$price,0,null,null);
        }
    }
    $data = $dbviewer->SelectoshichokinAll('currentDB');
}

$test = $dbviewer->InsertBankBalance();

if(isset($_POST['test'])){
    $dbaggregate->InsertNewMonth();
}

$testary[0][0]="a";
$testary[0][1]="b";
$testary[1][0]="c";
$testary[1][1]="d";

?>
<!DOCTYPE html>
<html>
    <head>
        <meta  charset="utf-8" />
        <title>支出リスト</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script type="text/javascript">
            function CheckDelete(){
                return confirm("削除してもよろしいですか？");
            }
        </script>
    </head>
    <body>
        <div class="main">
        <div>
            <p>test</p>
            <p id="test"></p>
            <p>test2</p>
            <p id="test2"></p>
            <button onclick="selectShop_Contents(1)">test-button</button>
            <p>test3</p>
            <p id="test3"><?php echo $dbinput->QueryCreditDebit(201,'2021-08-18');?></p>
            <p>test4</p>
            <p id="test4"><?php echo var_dump($testary[0]) .":" .count($testary);?></p>
            <p>test5</p>
            <p id="test5"></p>
            <p>test6</p>
            <p id="test6"></p>
            <p>test7</p>
            <p id="test7"></p>

            <?php
                echo $dbinput->SelectTag_Pay();
                echo $dbinput->SelectTag_Credit();
                echo $dbviewer->SelectpaymentAll();
                echo $dbviewer->SelectoshichokinRecordAll('chokin_current');
                echo "年：" .$aggregatequery->AggregateIDtoYear(202105);
                echo "月：" .$aggregatequery->AggregateIDtoMonth(202105);
                echo $dateexpenditure;
                echo $dateexpendituregraph;
                echo "MIN:" .$dbaggregate->QueryAggregateMinID();
                echo "202106:" .$dbaggregate->Balance();
                echo var_dump($dbaggregate->SelectMonthAggregate());
                echo $dbviewer->SelectMonthPayment(2021,8);
                echo $dbaggregate->Firstday();
                echo "todayid:" .$dbaggregate->QueryTodayID();
                echo "count:" .$dbaggregate->QueryDiffAggregateID(202105,202202);
                echo "count:" .$dbaggregate->QueryDiffAggregateID($dbaggregate->QueryAggregateMinID(),$dbaggregate->QueryTodayID());
                echo var_dump($dbaggregate->AggregateAverage());
            ?>
            <form action="" method="post">
                <input type="submit" name="test"  value="test">
            </form>
        </div>
            
        <script language="JavaScript" type="text/javascript" src="aggregate.js"></script>
    </body>
</html>