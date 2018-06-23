<?php
$user = $_SESSION["ausweis"];
    if ($PAGE != 'checkout')
        die("<p>Internal Error (403)</p>");
?>
<form class="form-signin" method="POST" action="index.php?page=pay">
      <div class="text-center mb-4">
          <!-- Create a vector graphic logo for the CPCS-->
        <img class="mb-4" src="assets/roroPAY.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Card Payment Cashier System<br/>
          <small>User:
          <?php echo( $user ); 
          ?>
          </small></h1>
        <p>Kasse. Bitte eine Ausweisnummer eingeben und den Betrag.</p>
      </div>
  
      <div class="form-label-group">
        <input id="ausweis" class="form-control" placeholder="Ausweisnumber" required="" autofocus="" type="text" name="ausweis">
        <label for="ausweis">Ausweisnummer</label>
      </div>
  
      <div class="form-label-group">
        <input id="amount" class="form-control" placeholder="Amount" required="" type="text" name="amount">
        <label for="amount">Betrag</label>
      </div>
  
      <button class="btn btn-lg btn-primary btn-block" type="submit">Zahlen</button>
      <p class="mt-5 mb-3 text-muted text-center">© 2018 by Johannes Witt</p>
    </form>