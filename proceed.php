<?php
session_start();
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
        $datas = $database->select("users", ["level","ausweis"], ["AND" => ["ausweis" => $_POST["ausweis"], "pw" => $_POST["pw"], "level[>]" => 0]]);
            foreach($datas as $data) {
                $_SESSION['ausweis'] = $data['ausweis'];
                $_SESSION['access'] = $data['level'];
                break;
            }
            //var_dump($datas);
            //var_dump($_SESSION);
            ?>
        <p> Please wait...</p>
        <script> window.location = "index.php"; </script>