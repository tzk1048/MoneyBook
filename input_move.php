<?php
//変更済
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
$dbinput = new DBinput('income','income_id');
$dbinput_move = new DBinput('movement','move_id');
$dbinput_withdraw = new DBinput('withdrawal','id');
$dbinput_deposit = new DBinput('deposit','id');
require_once('DBupdate.php');
$dbupdate = new DBupdate('income','income_id');
$dbupdate_move = new DBupdate('movement','move_id');
$dbupdate_withdraw = new DBupdate('withdrawal','id');
$dbupdate_deposit = new DBupdate('deposit','id');
require_once('DBshortcut.php');
$dbshortcut = new DBshortcut('income','income_id');
$dbshortcut_move = new DBshortcut('movement','move_id');
$dbshortcut_withdraw = new DBshortcut('withdrawal','id');
$dbshortcut_deposit = new DBshortcut('deposit','id');

//テーブルデータの一覧表示
//変更済
$data = $dbviewer->SelectmovementRecordAll('current');
$IncomeList = $dbviewer->SelectIncomeAll();

//selectタグを生成
//変更済
$selectTag_Pay = $dbinput_move->SelectTag_Pay();
$selectTag_Pay2 = $dbinput_move->SelectTag_Pay2();
$updateTag_Pay = $dbupdate_move->UpdateTag_Pay();
$updateTag_Pay2 = $dbupdate_move->UpdateTag_Pay2();
$updateTag_Income = $dbupdate->UpdateTag_Income();
$shortcutTag_Pay = $dbshortcut->ShortcutTag_Pay();
$shortcutTag_Income = $dbshortcut->ShortcutTag_Income();
$shortcutset = $dbshortcut_move->SelectmovementRecordSet();
$markup = $dbshortcut_move->CreateMarkupTable();
$markupcard=$dbshortcut_move->MarkupSheet(($dbshortcut_move->MarkupCardMovement()));

//income_crrentに入力
//変更済
//$_POSTのname名変更要
if(isset($_POST['input_cash'])){
    $move_id=date("YmdHis");
    $te=(int)$move_id .":" .$_POST['FromBID'] .":" .$_POST['ToBID'];
    $input_movement[0]=(int)$move_id;
    $input_movement[1]="'{$_POST['date']}'";
    $input_movement[2]=$_POST['price'];
    $input_movement[3]="'{$_POST['comment']}'";
    $dbinput_move->InsertRecordToCurrent($dbinput_move->Array($input_movement));
    $input_withdrawal[0]="NULL";
    $input_withdrawal[1]=(int)$move_id;
    $input_withdrawal[2]=$_POST['FromBID'];
    $dbinput_withdraw->InsertRecordToCurrent($dbinput_withdraw->Array($input_withdrawal));
    $input_deposit[0]="NULL";
    $input_deposit[1]=(int)$move_id;
    $input_deposit[2]=$_POST['ToBID'];
    $dbinput_deposit->InsertRecordToCurrent($dbinput_deposit->Array($input_deposit));
    $data = $dbviewer->SelectmovementRecordAll('current');
}

if(isset($_POST['inputary'])){
    for ($i=0; $i < count($_POST['date']); $i++) { 
        $move_id=date("YmdHis");
    $te=(int)$move_id .":" .$_POST['FromBID'] .":" .$_POST['ToBID'];
    $input_movement[0]=(int)$move_id;
    $input_movement[1]="'{$_POST['date']}'";
    $input_movement[2]=$_POST['price'];
    $input_movement[3]="'{$_POST['comment']}'";
    $dbinput_move->InsertRecordToCurrent($dbinput_move->Array($input_movement));
    $input_withdrawal[0]="NULL";
    $input_withdrawal[1]=(int)$move_id;
    $input_withdrawal[2]=$_POST['FromBID'];
    $dbinput_withdraw->InsertRecordToCurrent($dbinput_withdraw->Array($input_withdrawal));
    $input_deposit[0]="NULL";
    $input_deposit[1]=(int)$move_id;
    $input_deposit[2]=$_POST['ToBID'];
    $dbinput_deposit->InsertRecordToCurrent($dbinput_deposit->Array($input_deposit));
    $data = $dbviewer->SelectmovementRecordAll('current');
    
        $input_movement[$i][0]="NULL";
        $input_movement[$i][1]="'{$_POST['date'][$i]}'";
        $input_movement[$i][4]=$_POST['FromBID'][$i];
        $input_movement[$i][3]=$_POST['ToBID'][$i];
        $input_movement[$i][2]=$_POST['price']{$i};
        $input_movement[$i][5]="'{$_POST['comment'][$i]}'";
    }
    
    for ($i=0; $i < count($input_movement); $i++) { 
        $dbinput->InsertRecordToCurrent($dbinput->Array($input_movement[$i]));
    }
    $data = $dbviewer->SelectmovementRecordAll('current');
}

//income_recordにincome_currentを挿入
//変更済
if(isset($_POST['insertdb'])){
    $dbinput_move->CurrentToRecord();
    $dbinput_move->DeletecurrentDBAll();
    $dbinput_withdraw->CurrentToRecord();
    $dbinput_withdraw->DeletecurrentDBAll();
    $dbinput_deposit->CurrentToRecord();
    $dbinput_deposit->DeletecurrentDBAll();
    header("location:viewer_expenditure.php");
}

//income_currentを更新
//変更済
$test7 = "";
if (isset($_POST['updtdb'])) {
    $update_movement[0]=$_POST['id'];
    $update_movement[1]="'{$_POST['date']}'";
    $update_movement[2]=$_POST['price'];
    $update_movement[3]="'{$_POST['comment']}'";
    $dbupdate_move->UpdateMainDBMovement($update_movement,'current');
    $update_withdrawal[0]=$_POST['from_id'];
    $update_withdrawal[1]=$_POST['id'];
    $update_withdrawal[2]=$_POST['FromBID'];
    $dbupdate_withdraw->UpdateMainDB($update_withdrawal,'current');
    $update_deposit[0]=$_POST['to_id'];
    $update_deposit[1]=$_POST['id'];
    $update_deposit[2]=$_POST['ToBID'];
    $dbupdate_deposit->UpdateMainDB($update_deposit,'current');

    $data = $dbviewer->SelectmovementRecordAll('current');
}

if (isset($_POST['updtsc'])) {
    $update_shortcut_m[0]=$_POST['id'];
    $update_shortcut_m[1]="'{$_POST['name']}'";
    if($_POST['date']==''){
        $update_shortcut_m[2]="NULL";
    } else {
        $update_shortcut_m[2]="'{$_POST['date']}'";
    }
    if($_POST['price']==''){
        $update_shortcut_m[3]="NULL";
    } else {
        $update_shortcut_m[3]=$_POST['price'];
    }
    $update_shortcut_m[4]="'{$_POST['comment']}'";

    $dbshortcut_move->UpdateShortcut($update_shortcut_m);

    $update_shortcut_w[0]=$_POST['id'];
    if($_POST['FromBID']==0){
        $update_shortcut_w[1]="NULL";
    } else {
        $update_shortcut_w[1]=$_POST['FromBID'];
    }

    $dbshortcut_withdraw->UpdateShortcut($update_shortcut_w);

    $update_shortcut_d[0]=$_POST['id'];
    if($_POST['ToBID']==0){
        $update_shortcut_d[1]="NULL";
    } else {
        $update_shortcut_d[1]=$_POST['ToBID'];
    }

    $dbshortcut_deposit->UpdateShortcut($update_shortcut_d);
    
    
    //$test7 = "更新：　ID：" .$id ."名前：" .$name ."日付：" .$date ."支払い方法：" .$bank_id ."項目：" .$pay_id ."金額：" .$price .$comment;
    $shortcutset = $dbshortcut_move->SelectmovementRecordSet();
    $markup = $dbshortcut_move->CreateMarkupTable();
    $markupcard=$dbshortcut_move->MarkupSheet(($dbshortcut_move->MarkupCardMovement()));
}

if (isset($_POST['insertsc'])) {
    $insert_shortcut_m[0]="null";
    $insert_shortcut_m[1]="'{$_POST['name']}'";
    if($_POST['date']==''){
        $insert_shortcut_m[2]="NULL";
    } else {
        $insert_shortcut_m[2]="'{$_POST['date']}'";
    }
    if($_POST['price']==''){
        $insert_shortcut_m[3]="NULL";
    } else {
        $insert_shortcut_m[3]=$_POST['price'];
    }
    $insert_shortcut_m[4]="'{$_POST['comment']}'";

    $dbshortcut_move->InsertShortcut($insert_shortcut_m);

    $insert_shortcut_w[0]=$dbshortcut_move->QueryMovementShortcutMaxID();
    if($_POST['FromBID']==0){
        $insert_shortcut_w[1]="NULL";
    } else {
        $insert_shortcut_w[1]=$_POST['FromBID'];
    }

    $dbshortcut_withdraw->InsertShortcut($insert_shortcut_w);

    $insert_shortcut_d[0]=$dbshortcut_move->QueryMovementShortcutMaxID();
    if($_POST['ToBID']==0){
        $insert_shortcut_d[1]="NULL";
    } else {
        $insert_shortcut_d[1]=$_POST['ToBID'];
    }

    $dbshortcut_deposit->InsertShortcut($insert_shortcut_d);
    
    $shortcutset = $dbshortcut_move->SelectmovementRecordSet();
    $markup = $dbshortcut_move->CreateMarkupTable();
    $markupcard=$dbshortcut_move->MarkupSheet(($dbshortcut_move->MarkupCardMovement()));
}

if(isset($_POST['markset'])) {
    for ($i=0; $i < 6; $i++) { 
        if(isset($_POST['mark'.($i+1)])) {
            $mark[$i]=$_POST['mark'.($i+1)];
        } else {
            $mark[$i]=null;
        }
    }
    $dbshortcut_move->UpdateMarkup($mark);
    //$dbshortcut->UpdateMarkupincome($mark);
    //$markup = $dbshortcut_move->CreateMarkupTable();
    $shortcutset = $dbshortcut_move->SelectmovementRecordSet();
    $markup = $dbshortcut_move->CreateMarkupTable();
    $markupcard=$dbshortcut_move->MarkupSheet(($dbshortcut_move->MarkupCardMovement()));   
}

//削除処理
//変更済
if (isset($_POST['delete'])) {
    $dbviewer->DeleteRecord($_POST['id'],'movement_current');
    $data = $dbviewer->SelectmovementRecordAll('current');
    $_POST['delete']="";
}

if (isset($_POST['deletesc'])) {
    $dbshortcut_move->DeleteRecord($_POST['id']);
    $shortcutset = $dbshortcut_move->SelectmovementRecordSet();
    $_POST['deletesc']="";
    $markup = $dbshortcut_move->CreateMarkupTable();
    $markupcard=$dbshortcut_move->MarkupSheet(($dbshortcut_move->MarkupCardMovement()));
}

if (isset($_POST['markupdlt'])) {
    $id = $_POST['id'];
    $dbshortcut_move->DeleteMarkup($id);
    $markup = $dbshortcut_move->CreateMarkupTable();
    $shortcutset = $dbshortcut_move->SelectmovementRecordSet();
    $markup = $dbshortcut_move->CreateMarkupTable();
    $markupcard=$dbshortcut_move->MarkupSheet(($dbshortcut_move->MarkupCardMovement()));
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
                            <label>出金元<?php echo $selectTag_Pay;?></label><br>
                            <label>入金先<?php echo $selectTag_Pay2;?></label><br>
                            <label><span class="inputlabel">金額</span><input type="number" id="inputcash_price" class="input_form" name="price"></label><br>
                            <label><span class="inputlabel">備考</span><input type="text" id="inputcash_comment" class="input_form" name="comment"></label><br>
                            <input type="submit" name="input_cash" value="入力">
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
                                <tr id="listshort"><td><input type="date" id="listshort_date"></td><td><select id="listshort_bank"></select></td><td><select id="listshort_bank2"></select></td><td><input type="number" id="listshort_price"></td><td><input type="text" id="listshort_com"></td></tr>
                                <tr><th>日付</th><th>出金元</th><th>入金先</th><th>金額</th><th>備考</th></tr>
                                <tr><td><input type="date" form="input" name="date[0]" class="shortcutdate"></td><td><select form="input" name="FromBID[0]" class="shortcuttagpay"></select></td><td><select form="input" name="ToBID[0]" class="shortcuttagpay2"></select></td><td><input type="number" form="input" name="price[0]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[0]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[1]" class="shortcutdate"></td><td><select form="input" name="FromBID[1]" class="shortcuttagpay"></select></td><td><select form="input" name="ToBID[1]" class="shortcuttagpay2"></select></td><td><input type="number" form="input" name="price[1]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[1]" class="shortcutcom"></td></tr>
                                <tr><td><input type="date" form="input" name="date[2]" class="shortcutdate"></td><td><select form="input" name="FromBID[2]" class="shortcuttagpay"></select></td><td><select form="input" name="ToBID[2]" class="shortcuttagpay2"></select></td><td><input type="number" form="input" name="price[2]" class="shortcutprice"></td><td><input type="text" form="input" name="comment[2]" class="shortcutcom"></td></tr>
                            </table>
                            <input type="submit" form="input" value="挿入" name="inputary">
                        </form>
                        <button onclick="Insert()">追加</button>
                    </div>
                    <input type="radio" name="tab_name" id="tab3" >
                    <label class="tab_class" for="tab3">ショートカット</label>
                    <div class="content_class">
                        <?php echo $dbshortcut->SelectmovementRecordAll('income_shortcut');?>
                    </div>
                    <input type="radio" name="tab_name" id="tab4" >
                    <label class="tab_class" for="tab4">ショートカット設定</label>
                    <div class="content_class">
                        <?php echo $shortcutset;?>
                        <button onclick="updateshortcutmovement()">追加</button>
                        <button id="markbtn" onclick="MarkSetMovement()">お気に入り設定</button>
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
        <!--<div class="right">
            <?php echo $dbinput->CurrentPreview();?>
            <?php echo $IncomeList;?>
        </div>-->
        <div id='updttagpay' style="display: none;"><?php echo $updateTag_Pay;?></div>
        <div id='updttagpay2' style="display: none;"><?php echo $updateTag_Pay2;?></div>
        <div id='updttagincome' style="display: none;"><?php echo $updateTag_Income;?></div>
        <div id='shorttagpay' style="display: none;"><?php echo $shortcutTag_Pay;?></div>
        <div id='shorttagincome' style="display: none;"><?php echo $shortcutTag_Income;?></div>
        <div id='payid_max' style="display: none;"><?php echo $ConIDMAX%10000;?></div>
        <script language="JavaScript" type="text/javascript" src="input.js"></script>
        <script language="JavaScript" type="text/javascript" src="update.js"></script>
        <script language="JavaScript" type="text/javascript" src="shortcut_move.js"></script>
    </body>
</html>