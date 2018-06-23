<?php
$user = $_SESSION["ausweis"];
    if ($PAGE != 'cashier')
        die("<p>Internal Error (403)</p>");

if (!isset($_SESSION['warenkorb']))
  $_SESSION['warenkorb'] = [];

require_once  'modules/Medoo.php';
use Medoo\Medoo;
if (isset($_POST['warennummer'])) {
  $database = new medoo([
          // required
          'database_type' => 'mysql',
          'database_name' => 'cpcs',
          'server' => 'localhost',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8'
      ]);
  $datas = $database->select("waren", ["warennummer", "amount", "produkt"], ["warennummer" => $_POST['warennummer']]);

  foreach ($datas as $data) {
    $_SESSION['warenkorb'][] = [$data['warennummer'], $data['produkt'], $data['amount']];
  }
}
?>
<form class="form-signin" method="POST" action="index.php?page=cashier#jump">
      <div class="text-center mb-4">
          <!-- Create a vector graphic logo for the CPCS-->
        <img class="mb-4" src="assets/roroPAY.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Card Payment Cashier System<br/>
          <small>User:
          <?php echo( $user ); 
          ?>
          </small></h1>
        <p>Kasse. Bitte eine Warennummer eingeben.</p>
      </div>
     <table class="table table-hover">
       <thead>
         <tr><th scope="col">Warennummer</th><th scope="col">Produkt<th scope="col">Betrag</th></tr>
        </thead>
        <tbody>
          <?php
          $sum = 0;
            foreach ($_SESSION['warenkorb'] as $w) {
              $sum += $w[2];
              echo '<tr><td>'.$w[0].'</td><td>'.$w[1].'</td><td>'.$w[2].'</td></tr>';
            }
          ?>
        </tbody>
        <tfoot>
          <tr><td>Summe</td><td></td><td><?php echo $sum; ?></td></tr>
        </tfoot>
     </table>
      <div id="jump" class="form-label-group">
        <input id="ausweis" class="form-control" placeholder="Warennummer" required="" autofocus="" type="text" name="warennummer">
        <label for="ausweis">Warennummer</label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Hinzufügen</button>
      <a class="btn btn-lg btn-success btn-block" href="index.php?page=checkout">Zahlen</a>
      <p class="mt-5 mb-3 text-muted text-center">© 2018 by Johannes Witt</p>
    </form>