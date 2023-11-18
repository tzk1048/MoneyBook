<?php
//変更済
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
$dbinput = new DBinput('payment','pay_id');
require_once('DBupdate.php');
$dbupdate = new DBupdate('payment','pay_id');
require_once('DBshortcut.php');
$dbshortcut = new DBshortcut('payment','pay_id');

//テーブルデータの一覧表示
//変更済
$data = $dbviewer->SelectpaymentRecordAll('payment_current');
$ContentsList = $dbviewer->SelectPaymentAll();

//selectタグを生成
//変更済
$selectTag_Pay = $dbinput->SelectTag_Pay();
$updateTag_Pay = $dbupdate->UpdateTag_Pay();
$updateTag_ShopCon = $dbupdate->UpdateTag_ShopCon();
$shortcutTag_Pay = $dbshortcut->ShortcutTag_Pay();
$shortcutTag_ShopCon = $dbshortcut->ShortcutTag_Shop();
$shortcutset = $dbshortcut->SelectpaymentRecordSet();
$markup = $dbshortcut->CreateMarkupTable();
$markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));

//payment_crrentに入力
//変更済
//$_POSTのname名変更要
if(isset($_POST['input_cash'])){
    $input_payment[0]="NULL";
    $input_payment[1]="'{$_POST['date']}'";
    $input_payment[4]=$_POST['FromBID'];
    $input_payment[3]=$_POST['ConID']+10000;
    $input_payment[2]=$_POST['price'];
    $input_payment[5]="'{$_POST['comment']}'";
    $te=$dbinput->Array($input_payment);
    $dbinput->InsertRecordToCurrent($dbinput->Array($input_payment));
    $data = $dbviewer->SelectpaymentRecordAll('payment_current');
}

if(isset($_POST['inputary'])){
    for ($i=0; $i < count($_POST['date']); $i++) { 
        $input_payment[$i][0]="NULL";
        $input_payment[$i][1]="'{$_POST['date'][$i]}'";
        $input_payment[$i][4]=$_POST['FromBID'][$i];
        $input_payment[$i][3]=$_POST['ConID'][$i];
        $input_payment[$i][2]=$_POST['price']{$i};
        $input_payment[$i][5]="'{$_POST['comment'][$i]}'";
    }
    
    for ($i=0; $i < count($input_payment); $i++) { 
        $dbinput->InsertRecordToCurrent($dbinput->Array($input_payment[$i]));
    }
    $data = $dbviewer->SelectpaymentRecordAll('payment_current');
}

//payment_recordにpayment_currentを挿入
//変更済
if(isset($_POST['insertdb'])){
    $dbinput->CurrentToRecord();
    $dbinput->DeletecurrentDBAll();
    header("location:viewer_expenditure.php");
}

//payment_currentを更新
//変更済
$test7 = "";
if (isset($_POST['updtdb'])) {
    $update_payment[0]=$_POST['id'];
    $update_payment[1]="'{$_POST['date']}'";
    $update_payment[4]=$_POST['FromBID'];
    $update_payment[3]=$_POST['ConID'];
    $update_payment[2]=$_POST['price'];
    $update_payment[5]="'{$_POST['comment']}'"; 
    $dbupdate->UpdateMainDB($update_payment,'current');
    $data = $dbviewer->SelectpaymentRecordAll('payment_current');
}

if (isset($_POST['updtsc'])) {
    $update_shortcut[0]=$_POST['id'];
    $update_shortcut[1]="'{$_POST['name']}'";
    if($_POST['date']==''){
        $update_shortcut[2]="NULL";
    } else {
        $update_shortcut[2]="'{$_POST['date']}'";
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
    $shortcutset = $dbshortcut->SelectpaymentRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));
}

if (isset($_POST['insertsc'])) {
    $insert_shortcut[0]="null";
    $insert_shortcut[1]="'{$_POST['name']}'";
    if($_POST['date']==''){
        $insert_shortcut[2]="NULL";
    } else {
        $insert_shortcut[2]="'{$_POST['date']}'";
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
    //$test7 = "更新：　名前：" .$name ."日付：" .$date ."支払い方法：" .$bank_id ."項目：" .$pay_id ."金額：" .$price .$comment;
    $dbshortcut->InsertShortcut($insert_shortcut);
    $shortcutset = $dbshortcut->SelectpaymentRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));
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
    $shortcutset = $dbshortcut->SelectpaymentRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));   
}

//削除処理
//変更済
if (isset($_POST['delete'])) {
    $dbviewer->DeleteRecord($_POST['id'],'payment_current');
    $data = $dbviewer->SelectpaymentRecordAll('payment_current');
    $_POST['delete']="";
}

if (isset($_POST['deletesc'])) {
    $dbshortcut->DeleteRecord($_POST['id']);
    $shortcutset = $dbshortcut->SelectpaymentRecordSet();
    $_POST['deletesc']="";
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));
}

if (isset($_POST['markupdlt'])) {
    $id = $_POST['id'];
    $dbshortcut->DeleteMarkup($id);
    $markup = $dbshortcut->CreateMarkupTable();
    $shortcutset = $dbshortcut->SelectpaymentRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));
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
        <div class="main">
            <div class="main-top">
                <?php echo $data;?>
            </div>
            <div class="main-bottom">
                <div class="area">
                    <input type="radio" name="tab_name" id="tab1" checked>
                    <label class="tab_class" for="tab1">入力</label>
                    <div class="content_class">
                        <form action="" method="post" class="input_cash">
                            <label><span class="inputlabel">日付</span><input type="date" id="inputcash_date" class="input_form" name="date"></label><br>
                            <label>支払い方法<?php echo $selectTag_Pay;?></label><br>
                            <label><span class="inputlabel">カテゴリーID</span><input type="number" id="inputcash_ConID" class="input_form" name="ConID" min="1" max="<?php echo $ConIDMAX%10000 ; ?>"></label><br>
                            <label><span class="inputlabel">カテゴリー</span><input type="text" id="inputcash_ConName" class="input_form"></label><br>
                            <label><span class="inputlabel">金額</span><input type="number" id="inputcash_price" class="input_form" name="price"></label><br>
                            <label><span class="inputlabel">備考</span><input type="text" id="inputcash_comment" class="input_form" name="comment"></label><br>
                            <input type="submit" name="input_cash" value="入力">
                            <button id="input_clear" onclick="InputCashClear()">クリア</button>
                        </form>
                        <?php echo $markupcard;?>
                    </div>
                    <input type="radio" name="tab_name" id="tab2" >
                    <label class="tab_class" for="tab2">リスト入力</label>
                    <div class="content_class">
                        <button class="list_btn" onclick="ListShortSet()">セット</button>
                        <button class="list_btn" onclick="ListShortClear()">固定値クリア</button>
                        <button class="list_btn" onclick="ListShortDataClear()">データクリア</button>
                        <button class="list_btn" onclick="ListShortAllClear()">全クリア</button>
                        <form action="" method="post" id="input">
                            <table id="inputary">
                                <tr id="inputary_title"><td colspan="5">Fixed Value</td></tr>
                                <tr id="listshort"><td><input type="date" id="listshort_date"></td><td><select id="listshort_bank"></select></td><td>ID:<input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" id="listshort_payid" onchange="SelectPayTag()"><select id="listshort_pay" onchange="InsertPayId()"></select></td><td><input type="number" id="listshort_price"></td><td><input type="text" id="listshort_com"></td></tr>
                                <tr><th>日付</th><th>支払い方法</th><th>カテゴリー</th><th>金額</th><th>備考</th></tr>
                                <tr id="inputary_title"><td colspan="5">Insert Value</td></tr>
                                <tr><td><input type="date" form="input" name="date[0]" class="shortcutdate"></td><td><select form="input" name="FromBID[0]" class="shortcuttagpay"></select></td><td>ID:<input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutcon" onchange="ShortcutShop_num(0)"><select name="ConID[0]" class="shortcuttagshop" onchange="ShortcutShop_tag(0)"></select></td><td><input type="number" form="input" name="price[0]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[0]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[1]" class="shortcutdate"></td><td><select form="input" name="FromBID[1]" class="shortcuttagpay"></select></td><td>ID:<input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutcon" onchange="ShortcutShop_num(1)"><select name="ConID[1]" class="shortcuttagshop" onchange="ShortcutShop_tag(1)"></select></td><td><input type="number" form="input" name="price[1]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[1]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[2]" class="shortcutdate"></td><td><select form="input" name="FromBID[2]" class="shortcuttagpay"></select></td><td>ID:<input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutcon" onchange="ShortcutShop_num(2)"><select name="ConID[2]" class="shortcuttagshop" onchange="ShortcutShop_tag(2)"></select></td><td><input type="number" form="input" name="price[2]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[2]" class="shortcutcom"></td></tr>
                            </table>
                            <input type="submit" form="input" value="挿入" name="inputary">
                        </form>
                        <button onclick="Insert()">追加</button>
                    </div>
                    <input type="radio" name="tab_name" id="tab3" >
                    <label class="tab_class" for="tab3">ショートカット</label>
                    <div class="content_class">
                        <?php echo $dbshortcut->SelectpaymentRecordAll('payment_shortcut');?>
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
                    <!--<input type="radio" name="tab_name" id="tab5" >
                    <label class="tab_class" for="tab5">その他</label>
                    <div class="content_class">
                        <p>タブ5</p>
                    </div>-->
                </div>
            </div>
        </div>
        <div class="right">
            <?php echo $dbinput->CurrentPreview();?>
            <?php echo $ContentsList;?>
        </div>
        <div id='updttagpay' style="display: none;"><?php echo $updateTag_Pay;?></div>
        <div id='updttagshopcon' style="display: none;"><?php echo $updateTag_ShopCon;?></div>
        <div id='shorttagpay' style="display: none;"><?php echo $shortcutTag_Pay;?></div>
        <div id='shorttagshop' style="display: none;"><?php echo $shortcutTag_ShopCon;?></div>
        <div id='payid_max' style="display: none;"><?php echo $ConIDMAX%10000;?></div>
        <script language="JavaScript" type="text/javascript" src="input.js"></script>
        <script language="JavaScript" type="text/javascript" src="update.js"></script>
        <script language="JavaScript" type="text/javascript" src="shortcut_cash.js"></script>
    </body>
</html>