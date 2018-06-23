<div class="container">
<div class="row align-items-center">
<div class="col"></div>
<div class="col">
<?php
function success() {
echo('<div class="alert alert-success" role="alert">
<h4 class="alert-heading">Betragsänderung erfolgreich!</h4>
<p>Der Betrag wurde erfolgreich geändert.</p>
</div>');
}

function failed() {
echo('<div class="alert alert-danger" role="alert">
<h4 class="alert-heading">Betragsänderung fehlgeschlagen!</h4>
<p>Der Betrag konnte aus unbekannten Gründen nicht geändert werden.</p>
</div>');
}


require_once  'modules/Medoo.php';
use Medoo\Medoo;
    $database = new medoo([
            // required
            'database_type' => 'mysql',
            'database_name' => 'cpcs',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8'
        ]);
        $amount = 0;
    $datas = $database->select("waren", ["warennummer"], ["warennummer" => $_POST["ware"]]);
        foreach($datas as $data) {
            $amount = floatval(str_replace(',', '.', str_replace('.', '', $_POST['amount'])));
                $datas = $database->update("waren", ["amount" => $amount], ["warennummer" => $_POST['ware']]);
            break;
        }
        if ($amount == 0) {
            $amount = floatval(str_replace(',', '.', str_replace('.', '', $_POST['amount'])));
            $datas = $database->insert("waren", ["warennummer" => $_POST["ware"], "amount" => $amount]);
        }
        if ($datas->rowCount() > 0)
            success();
        else
            failed();
?>
</div><div class="col">
  
</div>
</div>
</div>
<script type="text/javascript">
<!--
setTimeout("self.location.href='index.php'",2000);
//-->
</script>