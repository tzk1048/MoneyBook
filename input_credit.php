<?php
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
$dbinput = new DBinput('credit','credit_id');
$dbinput_move = new DBinput('movement','move_id');
$dbinput_withdraw = new DBinput('withdrawal','id');
require_once('DBupdate.php');
$dbupdate = new DBupdate('credit','credit_id');
require_once('DBshortcut.php');
$dbshortcut = new DBshortcut('credit','credit_id');

//テーブルデータの一覧表示
//変更済
$data = $dbviewer->SelectcreditAll('credit_current');
$ContentsList = $dbviewer->SelectPaymentAll();

//selectタグを生成
//変更済
$selectTag_Credit = $dbinput->SelectTag_Credit();
$updateTag_Credit = $dbupdate->UpdateTag_Credit();
$updateTag_ShopCon = $dbupdate->UpdateTag_ShopCon();
$shortcutTag_Credit = $dbshortcut->ShortcutTag_Credit();
$shortcutTag_ShopCon = $dbshortcut->ShortcutTag_Shop();
$shortcutset = $dbshortcut->SelectcreditRecordSet();
$markup = $dbshortcut->CreateMarkupTable();
$markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCardCredit()));

//currentDBに入力
//変更済
if(isset($_POST['input_credit'])){
    $input_credit[0]="NULL";
    $input_credit[1]="'{$_POST['date']}'";
    $input_credit[4]=$_POST['FromBID'];
    $input_credit[3]=$_POST['ConID']+10000;
    $input_credit[2]=$_POST['price'];
    $input_credit[5]="'{$_POST['comment']}'";
    $debit=$dbinput->QueryCreditDebit($_POST['FromBID'],$_POST['date']);
    $input_credit[6]="'{$debit}'";
    $te=$dbinput->Array($input_credit);
    $dbinput->InsertRecordToCurrent($dbinput->Array($input_credit));
    $data = $dbviewer->SelectcreditAll('credit_current');
}

if(isset($_POST['inputary'])){
    for ($i=0; $i < count($_POST['date']); $i++) { 
        $input_credit[$i][0]="NULL";
        $input_credit[$i][1]="'{$_POST['date'][$i]}'";
        $input_credit[$i][4]=$_POST['FromBID'][$i];
        $input_credit[$i][3]=$_POST['ConID'][$i];
        $input_credit[$i][2]=$_POST['price'][$i];
        $input_credit[$i][5]="'{$_POST['comment'][$i]}'";
        $debit=$dbinput->QueryCreditDebit($_POST['FromBID'][$i],$_POST['date'][$i]);
        $input_credit[$i][6]="'{$debit}'";
    }

    for ($i=0; $i < count($input_credit); $i++) { 
        $dbinput->InsertRecordToCurrent($dbinput->Array($input_credit[$i]));
    }
    $data = $dbviewer->SelectcreditAll('credit_current');
}

if(isset($_POST['insert_debit'])) {
    $move_id=date("YmdHis");
    $input_movement[0]=(int)$move_id;
    $input_movement[1]="'{$_POST['debit_date']}'";
    $input_movement[2]=$_POST['debit_price'];
    $credit=$dbinput->QueryCreditValue($_POST['credit'],'credit');
    $input_movement[3]="'{$credit}'";
    $dbinput_move->InsertRecordToRecord($dbinput_move->Array($input_movement));
    $input_withdrawal[0]="NULL";
    $input_withdrawal[1]=(int)$move_id;
    $bank=$dbinput->QueryCreditValue($_POST['credit'],'bank_id');
    $input_withdrawal[2]=$bank;
    $dbinput_withdraw->InsertRecordToRecord($dbinput_withdraw->Array($input_withdrawal));
    $data=$dbinput->Array(["NULL",$input_movement[1],$_POST['credit'],(int)$move_id]);
    $dbinput->InsertCreditDebit($data);
}

//MoneyDBに挿入
if(isset($_POST['insertdb'])){
    $dbinput->CurrentToRecord();
    $dbinput->DeletecurrentDBAll();
    header("location:viewer_expenditure.php");
}

//currentDBを更新
$test7 = "";
if (isset($_POST['updtdb'])) {
    $update_credit[0]=$_POST['id'];
    $update_credit[1]="'{$_POST['date']}'";
    $update_credit[4]=$_POST['FromBID'];
    $update_credit[3]=$_POST['ConID'];
    $update_credit[2]=$_POST['price'];
    $update_credit[5]="'{$_POST['comment']}'";
    $debit=$dbinput->QueryCreditDebit($_POST['FromBID'],$_POST['date']);
    $update_credit[6]="'{$debit}'";
    $dbupdate->UpdateMainDB($update_credit,'current');
    $data = $dbviewer->SelectcreditAll('credit_current');
}

if (isset($_POST['updtsc'])) {
    $update_shortcut[0]=$_POST['id'];
    $update_shortcut[1]="'{$_POST['name']}'";
    if($_POST['date']==''){
        $update_shortcut[2]="NULL";
        $update_shortcut[7]="NULl";
    } else {
        $update_shortcut[2]="'{$_POST['date']}'";
        if($_POST['FromBID']!=0){
            $debit=$dbinput->QueryCreditDebit($_POST['FromBID'],$_POST['date']);
            $update_shortcut[7]="'{$_POST['date']}'";
        } else {
            $update_shortcut[7]="NULL";
        }
    }
    if($_POST['FromBID']==0){
        $update_shortcut[5]="NULL";
    } else {
        $update_shortcut[5]=$_POST['FromBID'];
    }
    if($_POST['ConID']==0){
        $update_shortcut[4]="NULL";
    } else {
        $update_shortcut[4]=$_POST['ConID'];
    }
    if($_POST['price']==''){
        $update_shortcut[3]="NULL";
    } else {
        $update_shortcut[3]=$_POST['price'];
    }
    $update_shortcut[6]="'{$_POST['comment']}'";
    //$test7 = "更新：　ID：" .$id ."名前：" .$name ."日付：" .$date ."支払い方法：" .$bank_id ."項目：" .$pay_id ."金額：" .$price .$comment;
    $test7=$dbshortcut->UpdateShortcut($update_shortcut);
    $shortcutset = $dbshortcut->SelectcreditRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCardCredit()));
}

if (isset($_POST['insertsc'])) {
    $insert_shortcut[0]="NULL";
    $insert_shortcut[1]="'{$_POST['name']}'";
    if($_POST['date']==''){
        $insert_shortcut[2]="NULL";
        $insert_shortcut[7]="NULL";
    } else {
        $insert_shortcut[2]="'{$_POST['date']}'";
        if($_POST['FromBID']!=0){
            $debit=$dbinput->QueryCreditDebit($_POST['FromBID'],$_POST['date']);
            $insert_shortcut[7]="'{$_POST['date']}'";
        } else {
            $insert_shortcut[7]="NULL";
        }
    }
    if($_POST['FromBID']==0){
        $insert_shortcut[5]="NULL";
    } else {
        $insert_shortcut[5]=$_POST['FromBID'];
    }
    if($_POST['ConID']==0){
        $insert_shortcut[4]="NULL";
    } else {
        $insert_shortcut[4]=$_POST['ConID'];
    }
    if($_POST['price']==''){
        $insert_shortcut[3]="NULL";
    } else {
        $insert_shortcut[3]=$_POST['price'];
    }
    $insert_shortcut[6]="'{$_POST['comment']}'";
    $test7 = "更新：　名前：" .$insert_shortcut[1] ."日付：" .$insert_shortcut[2] ."支払い方法：" .$insert_shortcut[5] ."項目：" .$insert_shortcut[4] ."金額：" .$insert_shortcut[3] .$insert_shortcut[6];
    $dbshortcut->InsertShortcut($insert_shortcut);
    $shortcutset = $dbshortcut->SelectcreditRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCardCredit()));
}

if(isset($_POST['markset'])) {
    for ($i=0; $i < 6; $i++) { 
        if(isset($_POST['mark'.($i+1)])) {
            $mark[$i]=$_POST['mark'.($i+1)];
        } else {
            $mark[$i]=null;
        }
    }
    $dbshortcut->UpdateMarkup($mark);
    //$dbshortcut->UpdateMarkupPayment($mark);
    //$markup = $dbshortcut->CreateMarkupTable();
    $shortcutset = $dbshortcut->SelectcreditRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCardCredit()));   
}

//削除処理
if (isset($_POST['delete'])) {
    $dbviewer->DeleteRecord($_POST['id'],'credit_current');
    $data = $dbviewer->SelectcreditAll('credit_current');
    $_POST['delete']="";
}

if (isset($_POST['deletesc'])) {
    $dbshortcut->DeleteRecord($_POST['id']);
    $shortcutset = $dbshortcut->SelectcreditRecordSet();
    $_POST['deletesc']="";
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCardCredit()));
}

if (isset($_POST['markupdlt'])) {
    $id = $_POST['id'];
    $dbshortcut->DeleteMarkup($id);
    $markup = $dbshortcut->CreateMarkupTable();
    $shortcutset = $dbshortcut->SelectcreditRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCardCredit()));
}

$credit_statement="";
$tab_js="";
if (isset($_POST['statement'])) {
    $credit_statement=$dbinput->CreditStatement($_POST['id']);
    $tab_js=<<<eof
    <script type="text/javascript">
            document.getElementById('tab5').checked=true;
    </script>
    eof;
}

//その他
$ConIDMAX = $dbinput->QueryMaxID();

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
        <?php include ('nav.php');?>
        <div>
            <p>test</p>
            <p id="test"></p>
            <p>test2</p>
            <p id="test2"></p>
            <button onclick="selectShop_Contents(1)">test-button</button>
            <p>test3</p>
            <p id="test3"><?php echo $te;?></p>
            <p>test4</p>
            <p id="test4"></p>
            <p>test5</p>
            <p id="test5"></p>
            <p>test6</p>
            <p id="test6"></p>
            <p>test7</p>
            <p id="test7"></p>
            <p><?php 
                echo "oo";
                if (isset($_POST['updtdb'])) {
                    echo "更新：　ID：" .$_POST['id'] ."日付：" .$_POST['date'] ."支払い方法：" .$_POST['FromBID'] ."項目：" .$_POST['ConID'] ."金額：" .$_POST['price'];
                };
            ?></p>
            <p><?php echo $test7 ?></p>
        </div>
        <div class="main">
            <div class="main-top">
                <?php echo $data;?>
                <form action="" method="post">
                    <input type="submit" name="insertdb" value="挿入" class="insertdb">
                </form>
            </div>
            <div class="main-bottom">
                <div class="area">
                    <input type="radio" name="tab_name" id="tab1" checked>
                    <label class="tab_class" for="tab1">入力</label>
                    <div class="content_class">
                        <form action="" method="post" class="input_cash">
                            <label><span class="inputlabel">日付</span><input type="date" id="inputcash_date" class="input_form" name="date"></label><br>
                            <label>支払い方法<?php echo $selectTag_Credit;?></label><br>
                            <label><span class="inputlabel">購入項目ID</span><input type="number" id="inputcash_ConID" class="input_form" name="ConID" min="1" max="<?php echo $ConIDMAX%10000 ; ?>"></label><br>
                            <label><span class="inputlabel">購入項目</span><input type="text" id="inputcash_ConName" class="input_form"></label><br>
                            <label><span class="inputlabel">金額</span><input type="number" id="inputcash_price" class="input_form" name="price"></label><br>
                            <label><span class="inputlabel">備考</span><input type="text" id="inputcash_comment" class="input_form" name="comment"></label><br>
                            <input type="submit" name="input_credit" value="入力">
                        </form>
                        <button onclick="InputCashClear()">クリア</button>
                        <?php echo $markupcard;?>
                    </div>
                    <input type="radio" name="tab_name" id="tab2" >
                    <label class="tab_class" for="tab2">リスト入力</label>
                    <div class="content_class">
                        <button onclick="ListShortSet()">セット</button>
                        <button onclick="ListShortClear()">固定値クリア</button>
                        <button onclick="ListShortDataClear()">データクリア</button>
                        <button onclick="ListShortAllClear()">全クリア</button>
                        <form action="" method="post" id="input">
                            <table id="inputary">
                                <tr id="listshort"><td><input type="date" id="listshort_date"></td><td><select id="listshort_credit"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" id="listshort_payid" onchange="SelectPayTag()"><select id="listshort_pay" onchange="InsertPayId()"></select></td><td><input type="number" id="listshort_price"></td><td><input type="text" id="listshort_com"></td></tr>
                                <tr><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th></tr>
                                <tr><td><input type="date" form="input" name="date[0]" class="shortcutdate"></td><td><select form="input" name="FromBID[0]" class="shortcuttagcredit"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutcon" onchange="ShortcutShop_num(0)"><select name="ConID[0]" class="shortcuttagshop" onchange="ShortcutShop_tag(0)"></select></td><td><input type="number" form="input" name="price[0]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[0]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[1]" class="shortcutdate"></td><td><select form="input" name="FromBID[1]" class="shortcuttagcredit"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutcon" onchange="ShortcutShop_num(1)"><select name="ConID[1]" class="shortcuttagshop" onchange="ShortcutShop_tag(1)"></select></td><td><input type="number" form="input" name="price[1]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[1]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[2]" class="shortcutdate"></td><td><select form="input" name="FromBID[2]" class="shortcuttagcredit"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutcon" onchange="ShortcutShop_num(2)"><select name="ConID[2]" class="shortcuttagshop" onchange="ShortcutShop_tag(2)"></select></td><td><input type="number" form="input" name="price[2]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[2]" class="shortcutcom"></td></tr>
                            </table>
                            <input type="submit" form="input" value="挿入" name="inputary">
                        </form>
                        <button onclick="Insert()">追加</button>
                    </div>
                    <input type="radio" name="tab_name" id="tab3" >
                    <label class="tab_class" for="tab3">ショートカット</label>
                    <div class="content_class">
                        <?php echo $dbshortcut->SelectcreditRecordAll('credit_shortcut');?>
                    </div>
                    <input type="radio" name="tab_name" id="tab4" >
                    <label class="tab_class" for="tab4">ショートカット設定</label>
                    <div class="content_class">
                        <?php echo $shortcutset;?>
                        <button onclick="updateshortcutpay()">追加</button>
                        <button id="markbtn" onclick="MarkSetPay()">お気に入り設定</button>
                        <div id="markset"></div>
                        <?php  echo $markup;?>
                    </div>
                    <input type="radio" name="tab_name" id="tab5" >
                    <label class="tab_class" for="tab5">引き落とし</label>
                    <div class="content_class">
                    <?php echo $dbinput->SelectTag_CreditDebit();?>
                    <?php echo $dbinput->CreditDebitTable();?>
                    <?php echo $credit_statement;?>
                    <?php echo $tab_js;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="right">
            <?php echo $dbinput->CurrentPreview();?>
            <?php echo $ContentsList;?>
        </div>
        <div id='updttagcredit' style="display: none;"><?php echo $updateTag_Credit;?></div>
        <div id='updttagshopcon' style="display: none;"><?php echo $updateTag_ShopCon;?></div>
        <div id='shorttagcredit' style="display: none;"><?php echo $shortcutTag_Credit;?></div>
        <div id='shorttagshop' style="display: none;"><?php echo $shortcutTag_ShopCon;?></div>
        <div id='payid_max' style="display: none;"><?php echo $ConIDMAX%10000;?></div>
        <script language="JavaScript" type="text/javascript" src="input.js"></script>
        <script language="JavaScript" type="text/javascript" src="update.js"></script>
        <script language="JavaScript" type="text/javascript" src="shortcut_credit.js"></script>
    </body>
</html>