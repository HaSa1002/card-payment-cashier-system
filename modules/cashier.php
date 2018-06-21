<?php
$user = $_SESSION["ausweis"];
    if ($PAGE != 'cashier') {
        print("<p>Internal Error (403)</p>");
    } else {
        echo('<form class="form-signin" method="POST" action="pay.php">
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

    <div class="form-label-group">
      <input id="amount" class="form-control" placeholder="Amount" required="" type="text" name="amount">
      <label for="amount">Amount</label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Pay</button>
    <p class="mt-5 mb-3 text-muted text-center">© 2018 by Johannes Witt</p>
  </form>');
    }
?>