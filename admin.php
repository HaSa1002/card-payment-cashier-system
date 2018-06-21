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
            <title>Card Payment Cashier System | CPCS: Admin</title>
        
    </head>
    <body>
        <?php
			require_once  'modules/Medoo.php';
			use Medoo\Medoo;
			if (isset($_POST['ausweis']) && isset($_POST['pw'])) {
				
				$database = new medoo([
					// required
					'database_type' => 'mysql',
					'database_name' => 'cpcs',
					'server' => 'localhost',
					'username' => 'root',
					'password' => '',
					'charset' => 'utf8'
					]);
					$hash = password_hash($_POST['pw'], PASSWORD_BCRYPT, ["cost" => 11]);
		$datas = $database->select("users", ["ausweis"], ["AND" => ["ausweis" => $_POST["ausweis"]]]);
            foreach($datas as $data) {
				$datas = $database->update("users", ["pw" => $hash, "level" => 1], ["ausweis" => $_POST['ausweis']]);
				$exists = true;
				break;
			} 
			if (!isset($exists)) {
				$datas = $database->insert("users", ["ausweis" => $_POST['ausweis'], "pw" => $hash, "level" => 1, "amount" => 0]);
			}

			if ($datas->rowCount() < 0)
			echo "Fail"; //Get nicer
			else
			echo "inserted";
			
            //var_dump($datas);
            //var_dump($_SESSION);

			}

            if (!isset($_SESSION['access'])) {
				$_SESSION['access'] = 1; //Change this to zero if you run normal
				
            }
                switch ($_SESSION['access']) {
                    case '0':/*
                    $PAGE = "sign_in";
                    require 'modules/sign-in.php';
                    break;*/
                    case '1':
                    print('<form class="form-signin" method="POST">
    <div class="text-center mb-4">
        <!-- Create a vector graphic logo for the CPCS-->
      <img class="mb-4" src="assets/roroPAY.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Card Payment Cashier System<br/>
        <small>Romain-Rolland-Gymnasium Berlin</small></h1>
      <p>Kassierer registrieren/Passwörter überschreiben</p>
    </div>

    <div class="form-label-group">
      <input id="ausweis" class="form-control" placeholder="Email address" required="" autofocus="" type="text" name="ausweis">
      <label for="ausweis">Ausweisnummer</label>
    </div>

    <div class="form-label-group">
      <input id="pw" class="form-control" placeholder="Password" required="" type="password" name="pw">
      <label for="pw">Passwort</label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Senden</button>
    <p class="mt-5 mb-3 text-muted text-center">© 2018 by Johannes Witt</p>
  </form>');
                }
        ?>
                <footer class ="footer"><p class="text-muted">
                <a href="index.php" class="btn btn-outline-success">Startseite</a>
                <?php if ($_SESSION['access'] != 0)
                        echo '
                        <a href="account.php" class="btn btn-outline-success">Guthaben</a>
                        <a href="logout.php" class="btn btn-outline-warning">Ausloggen</a>
                        '; ?>
                        </p>
            </footer>
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    </body>
</html>