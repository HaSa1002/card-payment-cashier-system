<?php

class IndexController extends ControllerBase
{

    public function indexAction() {
        if (!$this->session->has('access'))
            $this->session->set('access', 0);

            if ($this->session->get('access') == 0 ) {
                if ($this->request->isPost()) {
                    $ausweis = $this->request->getPost('ausweis', 'int');
                    $pw = $this->request->getPost('pw', 'string');
                    $res = Users::findFirstByausweis($ausweis);
                    if ($res) {
                        if ($res->verify($pw)) {
                            $this->session->set('ausweis', $ausweis);
                            $this->session->set('access', $res->access);
                            if ($res->access == 0) {
                                $this->flash->notice('Der Benutzer hat keine weitergehende Rechte.');
                            }
                        } else {
                            $this->flash->error('Das Passwort war falsch.');
                        }
                    } else {
                        $this->flash->error('Der Benutzername war falsch.');
                    }
                }
            }
        switch ($this->session->get('access')) {
            case ControllerBase::CASHIER: //Cashier
                $dest = $this->url->get('cashier/index');
                break;
            case ControllerBase::CARDS: //Kartentransaktionen
                $dest = $this->url->get('karten/index');
                break;
            case ControllerBase::GOODS: //Waren
                $dest = $this->url->get('waren/index');
                break;
            case ControllerBase::FINANCE: //Finanzen
                $dest = $this->url->get('finance/index');
                break;
            case ControllerBase::ADMIN: //Admin
                $dest = $this->url->get('admin/index');
                break;
            case 0: break;
            default:
                $dest = $this->url->get('index/select');
        }
        if (isset($dest))
            echo "<script type=\"text/javascript\">window.location = \"$dest\";</script>";
    }
    
    public function selectAction() {
        if (!$this->session->has('access') || $this->session->get('access') == 0) return $this->dispatcher->forward(['controller' => 'index', 'action' => 'index']);
        $this->view->level = $this->session->get('access');
    }

    public function logoutAction() {
        $this->session->destroy();
        $this->session->start();
        $index = $this->url->get('index/index');
        echo "<script type=\"text/javascript\">window.location = \"$index\";</script>";
    }
}

