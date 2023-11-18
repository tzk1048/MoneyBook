<?php
//変更済
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
$dbinput = new DBinput('income','income_id');
require_once('DBupdate.php');
$dbupdate = new DBupdate('income','income_id');
require_once('DBshortcut.php');
$dbshortcut = new DBshortcut('income','income_id');

//テーブルデータの一覧表示
//変更済
$data = $dbviewer->SelectincomeRecordAll('income_current');
$IncomeList = $dbviewer->SelectIncomeAll();

//selectタグを生成
//変更済
$selectTag_Pay = $dbinput->SelectTag_Pay();
$updateTag_Pay = $dbupdate->UpdateTag_Pay();
$updateTag_Income = $dbupdate->UpdateTag_Income();
$shortcutTag_Pay = $dbshortcut->ShortcutTag_Pay();
$shortcutTag_Income = $dbshortcut->ShortcutTag_Income();
$shortcutset = $dbshortcut->SelectincomeRecordSet();
$markup = $dbshortcut->CreateMarkupTable();
$markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));

//income_crrentに入力
//変更済
//$_POSTのname名変更要
if(isset($_POST['input_cash'])){
    $input_income[0]="NULL";
    $input_income[1]="'{$_POST['date']}'";
    $input_income[4]=$_POST['FromBID'];
    $input_income[3]=$_POST['ConID']+20000;
    $input_income[2]=$_POST['price'];
    $input_income[5]="'{$_POST['comment']}'";
    $te=$dbinput->Array($input_income);
    $dbinput->InsertRecordToCurrent($dbinput->Array($input_income));
    $data = $dbviewer->SelectincomeRecordAll('income_current');
}

if(isset($_POST['inputary'])){
    for ($i=0; $i < count($_POST['date']); $i++) { 
        $input_income[$i][0]="NULL";
        $input_income[$i][1]="'{$_POST['date'][$i]}'";
        $input_income[$i][4]=$_POST['FromBID'][$i];
        $input_income[$i][3]=$_POST['ConID'][$i];
        $input_income[$i][2]=$_POST['price']{$i};
        $input_income[$i][5]="'{$_POST['comment'][$i]}'";
    }
    
    for ($i=0; $i < count($input_income); $i++) { 
        $dbinput->InsertRecordToCurrent($dbinput->Array($input_income[$i]));
    }
    $data = $dbviewer->SelectincomeRecordAll('income_current');
}

//income_recordにincome_currentを挿入
//変更済
if(isset($_POST['insertdb'])){
    $dbinput->CurrentToRecord();
    $dbinput->DeletecurrentDBAll();
    header("location:viewer_expenditure.php");
}

//income_currentを更新
//変更済
$test7 = "";
if (isset($_POST['updtdb'])) {
    $update_income[0]=$_POST['id'];
    $update_income[1]="'{$_POST['date']}'";
    $update_income[4]=$_POST['FromBID'];
    $update_income[3]=$_POST['ConID'];
    $update_income[2]=$_POST['price'];
    $update_income[5]="'{$_POST['comment']}'"; 
    $dbupdate->UpdateMainDB($update_income,'current');
    $data = $dbviewer->SelectincomeRecordAll('income_current');
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
    $shortcutset = $dbshortcut->SelectincomeRecordSet();
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
    $shortcutset = $dbshortcut->SelectincomeRecordSet();
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
    //$dbshortcut->UpdateMarkupincome($mark);
    //$markup = $dbshortcut->CreateMarkupTable();
    $shortcutset = $dbshortcut->SelectincomeRecordSet();
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));   
}

//削除処理
//変更済
if (isset($_POST['delete'])) {
    $dbviewer->DeleteRecord($_POST['id'],'income_current');
    $data = $dbviewer->SelectincomeRecordAll('income_current');
    $_POST['delete']="";
}

if (isset($_POST['deletesc'])) {
    $dbshortcut->DeleteRecord($_POST['id']);
    $shortcutset = $dbshortcut->SelectincomeRecordSet();
    $_POST['deletesc']="";
    $markup = $dbshortcut->CreateMarkupTable();
    $markupcard=$dbshortcut->MarkupSheet(($dbshortcut->MarkupCard()));
}

if (isset($_POST['markupdlt'])) {
    $id = $_POST['id'];
    $dbshortcut->DeleteMarkup($id);
    $markup = $dbshortcut->CreateMarkupTable();
    $shortcutset = $dbshortcut->SelectincomeRecordSet();
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
        <div>
            <p>test</p>
            <p id="test"></p>
            <p>test2</p>
            <p id="test2"></p>
            <button onclick="selectShop_Contents(1)">test-button</button>
            <p>test3</p>
            <p id="test3"></p>
            <?php echo $te;?>
            <p>test4</p>
            <p id="test4"><?php echo var_dump($dbshortcut->MarkupCard());?></p>
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
                            <label>入金先<?php echo $selectTag_Pay;?></label><br>
                            <label><span class="inputlabel">収入項目ID</span><input type="number" id="inputcash_ConID" class="input_form" name="ConID" min="1" max="<?php echo $ConIDMAX%10000 ; ?>"></label><br>
                            <label><span class="inputlabel">収入項目</span><input type="text" id="inputcash_ConName" class="input_form"></label><br>
                            <label><span class="inputlabel">金額</span><input type="number" id="inputcash_price" class="input_form" name="price"></label><br>
                            <label><span class="inputlabel">備考</span><input type="text" id="inputcash_comment" class="input_form" name="comment"></label><br>
                            <input type="submit" name="input_cash" value="入力">
                        </form>
                        <button onclick="InputCashClear()">クリア</button>
                        <?php //echo $markupcard;?>
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
                                <tr id="listshort"><td><input type="date" id="listshort_date"></td><td><select id="listshort_bank"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" id="listshort_incomeid" onchange="SelectPayTag()"><select id="listshort_income" onchange="InsertPayId()"></select></td><td><input type="number" id="listshort_price"></td><td><input type="text" id="listshort_com"></td></tr>
                                <tr><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th></tr>
                                <tr><td><input type="date" form="input" name="date[0]" class="shortcutdate"></td><td><select form="input" name="FromBID[0]" class="shortcuttagpay"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutincomeid" onchange="ShortcutShop_num(0)"><select name="ConID[0]" class="shortcuttagincome" onchange="ShortcutShop_tag(0)"></select></td><td><input type="number" form="input" name="price[0]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[0]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[1]" class="shortcutdate"></td><td><select form="input" name="FromBID[1]" class="shortcuttagpay"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutincomeid" onchange="ShortcutShop_num(1)"><select name="ConID[1]" class="shortcuttagincome" onchange="ShortcutShop_tag(1)"></select></td><td><input type="number" form="input" name="price[1]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[1]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[2]" class="shortcutdate"></td><td><select form="input" name="FromBID[2]" class="shortcuttagpay"></select></td><td><input type="number" min="1" max="<?php echo $ConIDMAX%10000;?>" form="input" class="shortcutincomeid" onchange="ShortcutShop_num(2)"><select name="ConID[2]" class="shortcuttagincome" onchange="ShortcutShop_tag(2)"></select></td><td><input type="number" form="input" name="price[2]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[2]" class="shortcutcom"></td></tr>
                            </table>
                            <input type="submit" form="input" value="挿入" name="inputary">
                        </form>
                        <button onclick="Insert()">追加</button>
                    </div>
                    <input type="radio" name="tab_name" id="tab3" >
                    <label class="tab_class" for="tab3">ショートカット</label>
                    <div class="content_class">
                        <?php echo $dbshortcut->SelectincomeRecordAll('income_shortcut');?>
                    </div>
                    <input type="radio" name="tab_name" id="tab4" >
                    <label class="tab_class" for="tab4">ショートカット設定</label>
                    <div class="content_class">
                        <?php echo $shortcutset;?>
                        <button onclick="updateshortcutincome()">追加</button>
                        <button id="markbtn" onclick="MarkSetIncome()">お気に入り設定</button>
                        <div id="markset"></div>
                        <?php  echo $markup;?>
                    </div>
                    <input type="radio" name="tab_name" id="tab5" >
                    <label class="tab_class" for="tab5">その他</label>
                    <div class="content_class">
                        <p>タブ5</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="right">
            <?php echo $dbinput->CurrentPreview();?>
            <?php echo $IncomeList;?>
        </div>
        <div id='updttagpay' style="display: none;"><?php echo $updateTag_Pay;?></div>
        <div id='updttagincome' style="display: none;"><?php echo $updateTag_Income;?></div>
        <div id='shorttagpay' style="display: none;"><?php echo $shortcutTag_Pay;?></div>
        <div id='shorttagincome' style="display: none;"><?php echo $shortcutTag_Income;?></div>
        <div id='payid_max' style="display: none;"><?php echo $ConIDMAX%10000;?></div>
        <script language="JavaScript" type="text/javascript" src="input.js"></script>
        <script language="JavaScript" type="text/javascript" src="update.js"></script>
        <script language="JavaScript" type="text/javascript" src="shortcut_income.js"></script>
    </body>
</html>