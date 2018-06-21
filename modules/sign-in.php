<?php 
if ($PAGE != "sign_in") {
    print("<p>Internal Error (403)</p>");
} else {
    print('<form class="form-signin" method="POST">
    <div class="text-center mb-4">
        <!-- Create a vector graphic logo for the CPCS-->
      <img class="mb-4" src="assets/roroPAY.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Card Payment Cashier System<br/>
        <small>Romain-Rolland-Gymnasium Berlin</small></h1>
      <p>Bitte einloggen.</p>
    </div>

    <div class="form-label-group">
      <input id="ausweis" class="form-control" placeholder="Email address" required="" autofocus="" type="text" name="ausweis">
      <label for="ausweis">Ausweisnummer</label>
    </div>

    <div class="form-label-group">
      <input id="pw" class="form-control" placeholder="Password" required="" type="password" name="pw">
      <label for="pw">Passwort</label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Einloggen</button>
    <p class="mt-5 mb-3 text-muted text-center">© 2018 by Johannes Witt</p>
  </form>');
}

?>