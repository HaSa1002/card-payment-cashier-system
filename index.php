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
        <?php 
        if (!isset($_SESSION['access'])) {
            $_SESSION['access'] = 0;
        }
            //Eventuelle Dinge prüfen:
            if (isset($_POST['ausweis'], $_POST['pw'])) {
                require 'modules/proceed.php';
                if ($_SESSION['access'] == 0)
                echo('<div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Login fehlgeschlagen!</h4>
                <p>Ausweisnummer oder Passwort inkorrekt.</p>
              </div>');
            } else if (isset($_SESSION['ausweis'], $_SESSION['amount'])) {

            }

            
            switch ($_SESSION['access']) {
                case '0':
                $PAGE = "sign_in";
                require 'modules/sign-in.php';
                break;
                case '1':
                if (isset($_GET['page']))
                    $PAGE = $_GET['page'];
                else 
                    $PAGE = 'cashier';
                    switch($PAGE) {
                        case 'account':
                            require 'modules/account.php';
                            break;
                        case 'balance':
                        require 'modules/balance.php';
                            break;
                        case 'pay':
                            require 'modules/pay.php';
                            break;
                        case 'waren':
                            require 'modules/waren.php';
                            break;
                        case 'warenBetrag':
                            require 'modules/warenBetrag.php';
                            break;
                        case 'addWare':
                            require 'modules/addWare.php';
                            break;
                        case 'checkout':
                            require 'modules/checkout.php';
                            break;
                        case 'logout':
                            $_SESSION['ausweis'] = 0;
                            $_SESSION['access'] = 0;
                            session_destroy();
                            header("Location: index.php");
                        break;
                        case 'cashier':
                        default:
                        require 'modules/cashier.php';
                        break;
                    }
                break;
            }
            
        ?>
                <footer class ="footer"><p class="text-muted">
                <a href="index.php" class="btn btn-outline-success">Startseite</a>
                <?php if ($_SESSION['access'] != 0)
                        echo '
                        <a href="index.php?page=account" class="btn btn-outline-success">Guthaben</a>
                        <a href="index.php?page=waren" class="btn btn-outline-success">Waren</a>
                        <a href="index.php?page=logout" class="btn btn-outline-warning">Ausloggen</a>
                        '; ?>
                        </p>
            </footer>
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    </body>
</html>