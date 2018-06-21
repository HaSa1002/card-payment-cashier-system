        <div class="container">
            <div class="row align-items-center">
    <div class="col">
      
    </div>
    <div class="col">
      
        <?php

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
            echo('<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">'.str_replace('.', ',', $amount).' Euro</h4>
  </div>');
            ?>
        </div>
    <div class="col">
      
    </div>
  </div>
        </div>
  <footer class ="footer">
  <p class="text-muted">
      <a href="index.php" class="btn btn-outline-success">Startseite</a>
      <?php if ($_SESSION['access'] != 0)
                        echo '
                        <a href="account.php" class="btn btn-outline-success">Guthaben</a>
                        <a href="logout.php" class="btn btn-outline-warning">Ausloggen</a>
                        '; ?>
                </p>
            </footer>
            <script type="text/javascript">
<!--
setTimeout("self.location.href='index.php'",2000);
//-->
</script>