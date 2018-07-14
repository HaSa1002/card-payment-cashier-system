<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller { 
    protected function authorized($level) {
        if ($this->session->get('access') == $level) return true;
        else {
            $this->dispatcher->forward([
                "controller" => "index",
                "action" => "index"
            ]);
            return false;
        }
    }
}
