<?php
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
//$dbinput = new DBinput();
require_once('DBaggregate.php');
require_once('DBstatement.php');
$dbstatement = new DBstatement();
require_once('DBsetting.php');
$paymentset = new DBsetting("payment","pay_id",10000,"payment","");
$BankSet = new DBsetting("bank","bank_id",100,"bank","");
$creditset = new DBsetting("credit","credit_id",200,"credit","");
$chokinset = new DBsetting("chokin","chokin_id",30000,"chokin","");

$test="";

//削除処理
if (isset($_POST['deletepay_id'])) {
    $paymentset->DeleteRecord($_POST['id']);
    $payment = $paymentset->SelectTable();
}

if (isset($_POST['deletebank_id'])) {
    $BankSet->DeleteRecord($_POST['id']);
    $Bank = $BankSet->SelectTable();
}

if (isset($_POST['deletecredit_id'])) {
    $creditset->DeleteRecord($_POST['id']);
    $credit = $creditset->SelectTable();
}

if (isset($_POST['deletechokin_id'])) {
    $chokinset->DeleteRecord($_POST['id']);
    $chokin = $chokinset->SelectTableChokin();
}

//更新処理
//Shop_Contents
if(isset($_POST['updtdb_sc'])) {
    $test=$_POST['id']."+".$_POST['shopcon'];
    $paycolumns = $paymentset->QueryColumn();
    $payvalues[0] = $_POST['id'];
    $payvalues[1]  =  "'{$_POST['shopcon']}'";
    $test .= var_dump($paycolumns) .var_dump($payvalues);
    $paymentset->UpdateRecord($paycolumns,$payvalues);
    $payment = $paymentset->SelectTable();
}

if(isset($_POST['updtdb_ba'])) {
    $test=$_POST['id']."+".$_POST['bank']."+".$_POST['balance'];
    $bankcolumns = $BankSet->QueryColumn();
    $bankvalues[0] = $_POST['id'];
    $bankvalues[1] = "'{$_POST['bank']}'";
    $bankvalues[2] = $_POST['balance'];
    $bankvalues[3] = "'{$_POST['date']}'";
    $BankSet->UpdateRecord($bankcolumns,$bankvalues);
    $Bank = $BankSet->SelectTable();
}

if(isset($_POST['updtdb_cre'])) {
    $creditcolumns = $creditset->QueryColumn();
    $creditvalues[0] = $_POST['id'];
    $creditvalues[1] = "'{$_POST['credit']}'";
    $creditvalues[2] = $_POST['dead'];
    $creditvalues[3] = $_POST['debit'];
    $creditvalues[4] = $_POST['bankid'];
    $creditset->UpdateRecord($creditcolumns,$creditvalues);
    $credit = $creditset->SelectTable();
}

if(isset($_POST['updtdb_cho'])) {
    $chokincolumns = $chokinset->QueryColumn();
    $chokinvalues[0] = $_POST['id'];
    $chokinvalues[1] = "'{$_POST['chokin']}'";
    $chokinvalues[2] = $_POST['price'];
    $chokinset->UpdateRecord($chokincolumns,$chokinvalues);
    $chokin = $chokinset->SelectTableChokin();
}

//追加処理
//Shop_Contents
if(isset($_POST['shopconset'])) {
    $pay_id=$paymentset->QueryNextID();
    $pay= $_POST['shopcon'];
    $pay_value = $paymentset->Array([$pay_id,"'{$pay}'"]);
    $paymentset->InsertTable($pay_value);
    $payment = $paymentset->SelectTable();
}
//Bank
if(isset($_POST['bankset'])) {
    //test=$_POST['bank']."+".$_POST['balance'];:*/
    $bank_id=$BankSet->QueryNextID();
    $bank1=$_POST['bank'];
    $bank2=$_POST['balance'];
    $create_day=$_POST['createday'];
    $bank_value = $BankSet->Array([$bank_id,"'{$bank1}'",$bank2,"'{$create_day}'"]);
    $BankSet->InsertTable($bank_value);
    $Bank = $BankSet->SelectTable();
}
//credit
if(isset($_POST['creditset'])) {
    //test=$_POST['bank']."+".$_POST['balance'];:*/
    $credit_id=$creditset->QueryNextID();
    $name=$_POST['credit'];
    $dead=$_POST['dead'];
    $debit=$_POST['debit'];
    $bankid=$_POST['bankid'];
    $credit_value = $BankSet->Array([$credit_id,"'{$name}'",$dead,$debit,$bankid]);
    $creditset->InsertTable($credit_value);
    $credit=$creditset->SelectTableCredit();
}

if(isset($_POST['chokinset'])) {
    //test=$_POST['bank']."+".$_POST['balance'];:*/
    $chokin_id=$chokinset->QueryNextIDChokin($_POST['tblid']);
    $test=$chokin_id;
    $name=$_POST['chokin'];
    $price=$_POST['price'];
    $chokin_value = $chokinset->Array([$chokin_id,"'{$name}'",$price]);
    $chokinset->InsertTable($chokin_value);
    $chokin=$chokinset->SelectTableChokin();
}

//設定処理
//貯金
if(isset($_POST['ChokinTblSet'])) {
    $chokinset->UpdateSetting(1,$_POST['chokintbl']);
}

if(isset($_POST['ChokinPay'])) {
    $chokinset->UpdateSetting(2,$_POST['bank_id']);
}

//テーブルデータの一覧表示
$payment = $paymentset->SelectTable();
$Bank = $BankSet->SelectTable();
$credit = $creditset->SelectTableCredit();
$chokin = $chokinset->SelectTableChokin();

/*if(isset($_POST['next'])){
    $dbaggregate->InsertNewMonth();
}*/

?>
<!DOCTYPE html>
<html>
    <head>
        <meta  charset="utf-8" />
        <title>リスト設定</title>
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
            <p id="test"></p>
            <?php echo $paymentset->test();?>
            <?php echo $test;?>
            <p>test2</p>
            <p id="test2"></p>
            <button onclick="selectShop_Contents(1)">test-button</button>
            <p>test3</p>
            <p id="test3"><?php $a='あああ'; $testary=[1,"'{$a}'"]; echo $paymentset->Array($testary);?></p>
            <p>test4</p>
            <p id="test4"><?php echo var_dump($paymentset->QueryColumn());?></p>
            <p>test5</p>
            <p id="test5"><?php echo $BankSet->QueryNextID();?></p>
            <p>test6</p>
            <p id="test6"></p>
            <p>test7</p>
            <p id="test7"></p>

            <div class="area">
                <input type="radio" name="tab_name" id="tab" >
                <label class="tab_class" for="tab">支出項目</label>
                <div class="content_class">
                    <table class='listsetting' id='Shop_ContentsList'>
                        <?php echo $payment;?>
                        <tr id="shoplistform"><td colspan="2"><button id="shoplistset">追加</button></td></tr>
                    </table>
                </div>
                <input type="radio" name="tab_name" id="tab2" >
                <label class="tab_class" for="tab2">収納機關</label>
                <div class="content_class">
                    <table class='listsetting' id='Banklist'>
                        <?php echo $Bank;?>
                        <tr id="banklistform"><td colspan="2"><button id="banklistset">追加</button></td></tr>
                    </table>
                    
                </div>
                <input type="radio" name="tab_name" id="tab3" >
                <label class="tab_class" for="tab3">クレジット</label>
                <div class="content_class">
                    <table class='listsetting' id='creditlist'>
                        <?php echo $credit;?>
                        <tr id="creditlistform"><td colspan="2"><button id="creditlistset">追加</button></td></tr>
                    </table>
                </div>
                <input type="radio" name="tab_name" id="tab4" >
                <label class="tab_class" for="tab4">貯金項目</label>
                <div class="content_class">
                    <?php echo $chokinset->SelectTag_ChokinTable();?>
                        <?php echo $chokin;?>
                        <tr id="chokinlistform"><td colspan="2"><button id="chokinlistset">追加</button></td></tr>
                        <div>
                            <p>使用テーブルの選択</p>
                            <p>選択中テーブル：テーブル<?php echo $chokinset->QuerySettingOption(1);?></p>
                            <form action="" method="post">
                                <?php echo $chokinset->SelectTag_ChokinTable();?>
                                <input type="submit" name="ChokinTblSet" value="変更">
                            </form>
                        </div>
                        <div>
                            <p>デフォルトの支払い設定</p>
                            <p>デフォルト：<?php echo $chokinset->QueryBankName($chokinset->QuerySettingOption(2));?></p>
                            <input type='hidden' id="chokinpay" value="<?php echo $chokinset->QuerySettingOption(2)?>">
                            <form action="" method="post">
                                <select name="bank_id" id="selectchokinpay">
                                <?php echo $chokinset->SelectTag_Pay();?>
                                </select>
                                <input type="submit" name="ChokinPay" value="変更">
                            </form>
                        </div>
                </div>
                <input type="radio" name="tab_name" id="tab5" >
                <label class="tab_class" for="tab5">その他</label>
                <div class="content_class">
                    <p>タブ5のコンテンツを表示します</p>
                </div>
            </div>
            
        </div>
        <div style="display:none" id="selectbank"><?php echo $creditset->SelectTag_Pay();?></div>    
        <script language="JavaScript" type="text/javascript" src="setting.js"></script>
    </body>
</html>