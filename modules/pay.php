        <div class="container">
            <div class="row align-items-center">
    <div class="col">
      
    </div>
    <div class="col">
      
    

        <?php
function payment_success() {
    echo('<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Zahlung erfolgreich!</h4>
    <p>Die Zahlung wurde erfolgreich durchgeführt.</p>
    <hr>
    <p class="mb-0"><a href="index.php">Nächster Kunde.</a></p>
  </div>');
}

function payment_failed() {
    echo('<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Zahlung fehlgeschlagen!</h4>
    <p>Die Zahlung wurde zurückgewiesen, da das Konto nicht ausreichend gedeckt ist.</p>
    <hr>
    <p class="mb-0"><a href="index.php">Nächster Kunde.</a></p>
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
            $ausweis = 0;
            $amount = 0.0;
        $datas = $database->select("users", ["ausweis","amount"], ["ausweis" => $_POST["ausweis"]]);
            foreach($datas as $data) {
              $ausweis = $data['ausweis'];
                $amount = $data['amount'];
                break;
            }
            if ($ausweis == 0) {
                $datas = $database->insert("users", ["ausweis" => $_POST["ausweis"], "amount" => 0]);
                $datas = $database->select("users", ["ausweis","amount"], ["ausweis" => $_POST["ausweis"]]);
                foreach($datas as $data) {
                    $ausweis = $data['ausweis'];
                      $amount = $data['amount'];
                      break;
                  }
            }
                if (floatval(str_replace(',', '.', str_replace('.', '', $_POST['amount']))) > $amount) {
                payment_failed();
                } else {
                    $amount -= floatval(str_replace(',', '.', str_replace('.', '', $_POST['amount'])));
                    $datas = $database->update("users", ["amount" => $amount], ["ausweis" => $ausweis]);
                    if ($datas->rowCount() > 0)
                    payment_success();
                    else
                    echo "Something went terribly wrong.";
                }
            ?>
        </div>
    <div class="col">
      
    </div>
  </div>
  </div>
            <script type="text/javascript">
<!--
setTimeout("self.location.href='index.php'",5000);
//-->
</script>