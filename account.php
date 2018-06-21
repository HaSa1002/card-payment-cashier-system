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
$user = $_SESSION["ausweis"];
        echo('<form class="form-signin" method="POST" action="balance.php">
    <div class="text-center mb-4">
        <!-- Create a vector graphic logo for the CPCS-->
      <img class="mb-4" src="" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Card Payment Cashier System<br/>
        <small>User: ');
        echo( $user );
        echo('</small></h1>
      <p>Cashier. Please enter the number and the amount.</p>
    </div>

    <div class="form-label-group">
      <input id="ausweis" class="form-control" placeholder="Ausweisnumber" required="" autofocus="" type="text" name="ausweis">
      <label for="ausweis">Id</label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Pay</button>
    <p class="mt-5 mb-3 text-muted text-center">© 2018 by Johannes Witt</p>
  </form>');
?>
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