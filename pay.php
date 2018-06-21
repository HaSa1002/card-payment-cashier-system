<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
            <link rel="stylesheet" href="css/floating-labels.css">
            <link rel="stylesheet" href="css/sticky-footer-navbar.css">
            <title>Card Payment Cashier System | CPCS: Main</title>
        
    </head>
    <body>
        <div class="container">
            <div class="row align-items-center">
    <div class="col">
      
    </div>
    <div class="col">
      
    

        <?php
function payment_success() {
    echo('<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Payment succeded!</h4>
    <p>The payment was successfully processed.</p>
    <hr>
    <p class="mb-0"><a href="index.php">Next customer.</a></p>
  </div>');
}

function payment_failed() {
    echo('<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Payment failed!</h4>
    <p>The payment failed, because the account is not coverd with enough money.</p>
    <hr>
    <p class="mb-0"><a href="index.php">Next customer.</a></p>
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
                if (floatval($_POST['amount']) > $amount) {
                payment_failed();
                } else {
                    $amount -= floatval($_POST['amount']);
                    echo floatval($_POST['amount']);
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
  <footer class ="footer">
  <p class="text-muted">
  <a href="index.php" class="btn btn-outline-success">Home</a>
  <?php if ($_SESSION['access'] != 0)
                        echo '
                        <a href="account.php" class="btn btn-outline-success">Account balance</a>
                        <a href="logout.php" class="btn btn-outline-warning">Log out</a>
                        '; ?>
                </p>
            </footer>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    </body>
</html>