<?php
require_once('DBviewer.php');
$dbviewer = new DBviewer();
require_once('DBinput.php');
$dbinput = new DBinput();
require_once('DBupdate.php');
$dbupdate = new DBupdate();
require_once('DBshortcut.php');
$dbshortcut = new DBshortcut();

//selectタグを生成
$selectTag_Pay = $dbinput->SelectTag_Pay();
$updateTag_Pay = $dbupdate->UpdateTag_Pay();
$updateTag_ShopCon = $dbupdate->UpdateTag_ShopCon();

$data = $dbviewer->SelectexpenditureAll('currentDB');

$date="";

if(isset($_POST['inputary'])){
    $date=$_POST['date'];
    $FromBID=$_POST['FromBID'];
    $ConID=$_POST['ConID'];
    $price=$_POST['price'];
    $com=$_POST['comment'];
    for ($i=0; $i < count($date); $i++) { 
        $dbinput->InsertcurrentDB($date[$i],1,$FromBID[$i],1,(int)$ConID[$i]+10000,$price[$i],$com[$i]);
    }
    $data = $dbviewer->SelectexpenditureAll('currentDB');
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
        <div>
            <p>test</p>
            <p id="test"><?php echo var_dump($date);?></p>
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
            <p id="test7"></p>
            <?php echo $data;?>
            <form action="" method="post" id="input">
            <table id="inputary">
                <tr><th>日付</th><th>支払い方法</th><th>項目</th><th>金額</th><th>備考</th></tr>
                <tr id="0" class="inputary"><td><input type="date" form="input" name="date[0]"></td><td><?php echo $updateTag_Pay;?></td><td><input type="number" form="input" name="ConID[0]"><input type="text" form="input"></td><td><input type="number" form="input" name="price[0]"></td><td><input type="text" form="input" name="comment[0]"></td></tr>
                <tr id="1" class="inputary"><td><input type="date" form="input" name="date[1]"></td><td><input type="number" form="input" name="FromBID[1]"></td><td><input type="number" form="input" name="ConID[1]"><input type="text" form="input"></td><td><input type="number" form="input" name="price[1]"></td><td><input type="text" form="input" name="comment[1]"></td></tr>
                <tr id="2" class="inputary"><td><input type="date" form="input" name="date[2]"></td><td><input type="number" form="input" name="FromBID[2]"></td><td><input type="number" form="input" name="ConID[2]"><input type="text" form="input"></td><td><input type="number" form="input" name="price[2]"></td><td><input type="text" form="input" name="comment[2]"></td></tr>
            </table>
            <input type="submit" form="input" value="挿入" name="inputary">
            </form>
            <button onclick="Insert()">追加</button>
        </div>
            
        <script language="JavaScript" type="text/javascript" src="inputtest.js"></script>
        <script language="JavaScript" type="text/javascript" src="update.js"></script>
        <div id='updttagpay' style="display: none;"><?php echo $updateTag_Pay;?></div>
        <div id='updttagshopcon' style="display: none;"><?php echo $updateTag_ShopCon;?></div>
    </body>
</html>