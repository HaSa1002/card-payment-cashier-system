<?php
$user = $_SESSION["ausweis"];
        echo('<form class="form-signin" method="POST" action="index.php?page=balance">
    <div class="text-center mb-4">
        <!-- Create a vector graphic logo for the CPCS-->
      <img class="mb-4" src="assets/roroPAY.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Card Payment Cashier System<br/>
        <small>User: '.$user.'</small></h1>
      <p>Guthabenanzeige. Bitte eine Ausweisnummer eingeben</p>
    </div>

    <div class="form-label-group">
      <input id="ausweis" class="form-control" placeholder="Ausweisnumber" required="" autofocus="" type="text" name="ausweis">
      <label for="ausweis">Id</label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Anzeigen</button>
    <p class="mt-5 mb-3 text-muted text-center">© 2018 by Johannes Witt</p>
  </form>');
?>