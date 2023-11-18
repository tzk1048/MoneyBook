<?php
//変更済
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
$dbinput = new DBinput('chokin','chokin_id');
require_once('DBupdate.php');
$dbupdate = new DBupdate('chokin','chokin_id');

//テーブルデータの一覧表示
//変更済
$data = $dbviewer->SelectoshichokinRecordAll('chokin_current');
$OshichokinList = $dbinput->input_oshichokin();

$updateTag_Pay = $dbupdate->UpdateTag_Pay();
$updateTag_Chokin = $dbupdate->UpdateTag_Chokin();

//設定
$tbl_op=$dbinput->QuerySettingOption(1);
$bank_op=$dbinput->QuerySettingOption(2);

$_POST['FromBID']=$bank_op;

//chokin_recordにchokin_currentを挿入
if(isset($_POST['insertdb'])){
    $dbinput->InsertChokinRecord('chokin_current','chokin_record');
    $dbinput->DeletecurrentDBAll('chokin_current');
    header("location:viewer_oshichokin.php");
}

if (isset($_POST['updtdb'])) {
    $update_chokin[0]=$_POST['id'];
    $update_chokin[1]="'{$_POST['date']}'";
    $update_chokin[2]=$_POST['chokin_id'];
    $update_chokin[3]=$_POST['FromBID'];
    $update_chokin[4]="'{$_POST['comment']}'"; 
    $dbupdate->UpdateMainDB($update_chokin,'current');
    $data = $dbviewer->SelectoshiichokinRecordAll('chokin_current');
}

//削除処理
//変更済
if (isset($_POST['delete'])) {
    $dbviewer->DeleteRecord($_POST['id'],'chokin_current');
    $data = $dbviewer->SelectoshichokinRecordAll('chokin_current');
    $_POST['delete']="";
}

$input="";

//chokin_currentに挿入
//変更済
if(isset($_POST['input_oshichokin'])){
    for ($i=1; $i < $_POST['chokincon_len']; $i++) { 
        $date = $_POST['date'];
        $num = $i + 30000 + ($tbl_op*1000);
        $con = $_POST[$num];
        /*$input .= $num;
        $input .= ":";
        $input .= $price;
        $input .= "*";
        $input .= $con;*/
        for ($n=0; $n < $con; $n++) { 
            $dbinput->InsertChokinCurrent($date,$num,$_POST['FromBID']);
        }
    }
    $data = $dbviewer->SelectoshichokinRecordAll('chokin_current');
}

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
            <div class="top">
                <?php echo $data;?>
                <form action="" method="post">
                    <input type="submit" name="insertdb" value="挿入" class="insertdb">
                </form>
            </div>
            <div class="bottom">
                <form action="" method="post" class="input_oshichokin">
                    <label><span class="inputlabel">日付</span><input type="date" id="inputcash_date" name="date"></label>
                    <label><span class="inputlabel">支払い方法</span><input type="hidden" id="chokinpay" value="<?php echo $bank_op;?>"><?php echo $dbinput->SelectTag_Pay();?></label><br>
                    <?php echo $OshichokinList;?>
                    <input type="submit" name="input_oshichokin" value="入力">
                </form>
            </div>
            <div id='updttagpay' style="display: none;"><?php echo $updateTag_Pay;?></div>
            <div id='updttagchokin' style="display: none;"><?php echo $updateTag_Chokin;?></div>
        <script language="JavaScript" type="text/javascript" src="input.js"></script>
        <script language="JavaScript" type="text/javascript" src="update.js"></script>
    </body>
</html>