<?php
//変更済
require_once('DBviewer.php');
$dbviewer = new DBviewer();
//テーブルデータの一覧表示
//変更済
$data = $dbviewer->SelectpaymentRecordAll('payment_record');

//削除処理
//変更済
if (isset($_POST['delete'])) {
    $dbviewer->DeleteRecord($_POST['id'],'payment_record');
    $data = $dbviewer->SelectpaymentRecordAll('payment_record');
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
        <?php echo $data;?>
        </div>
    </body>
</html>