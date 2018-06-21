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
        $datas = $database->select("users", ["level","ausweis", "pw"], ["AND" => ["ausweis" => $_POST["ausweis"], "level[>]" => 0]]);
            foreach($datas as $data) {
                if (password_verify($_POST['pw'], $data['pw'])) {
                $_SESSION['ausweis'] = $data['ausweis'];
                $_SESSION['access'] = $data['level'];
                } else {
                    
                }
                break;
            }
            ?>