<?php

class LogoutController extends ControllerBase {
    public function indexAction() {
        $this->session->destroy();
        $this->session->start();
        $index = $this->url->get('index/index');
        echo "<script type=\"text/javascript\">window.location = \"$index\";</script>";
    }
}

