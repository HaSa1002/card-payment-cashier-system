<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller { 

    const CASHIER = 2;
    const CARDS = 3;
    const GOODS = 5;
    const FINANCE = 7;
    const ADMIN = 13;
    //Define here more constants on primes, and extend the user "managment"

    protected function authorized($level) {
        if ($this->session->get('access') % $level == 0) return true;
        else {
            $this->dispatcher->forward([
                "controller" => "index",
                "action" => "index"
            ]);
            return false;
        }
    }
}
