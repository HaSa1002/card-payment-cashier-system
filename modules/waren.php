<div class="container">
<table class="table table-hover">
    <thead>
        <tr><th scope="col">Warennummer</th><th scope="col">Betrag</th><th scope="col">Aktion</th></tr>
    </thead>
    <tbody>
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
            $amount = 0;
        $datas = $database->select("waren", ["warennummer", "amount"]);
            foreach($datas as $data) {
                $amount = floatval(str_replace(',', '.', $data['amount']));
                echo "<tr><td>".$data["warennummer"]."</td><td>$amount</td><td><a class=\"btn btn-sm btn-outline-success\" href=\"index.php?page=warenBetrag&warennummer=".$data["warennummer"]."\">Preis ändern</a></td></tr>";
            }
?>
    </tbody>
</table>
</div>