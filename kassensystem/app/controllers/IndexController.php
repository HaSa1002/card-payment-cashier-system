<?php

class IndexController extends ControllerBase
{

    public function indexAction() {
        if ($this->session->has('access')) {
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
        } else {
            $this->session->set('access', 0);
        }

        switch ($this->session->get('access')) {
            case 1: //Cashier
            $this->dispatcher->forward([
                'controller' => 'cashier',
                'action' => 'index'
            ]);
            break;
            case 2: //Kartentransaktionen
            $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
            ]);
            break;
            case 3: //Finance
            $this->dispatcher->forward([
                'controller' => 'finance',
                'action' => 'index'
            ]);
            break;
            case 4: //sellers
            $this->dispatcher->forward([
                'controller' => 'sellers',
                'action' => 'index'
            ]);
            break;
            case 5: //buyers
            $this->dispatcher->forward([
                'controller' => 'buyers',
                'action' => 'index'
            ]);
            break;
            case 6: //admin
            $this->dispatcher->forward([
                'controller' => 'admin',
                'action' => 'index'
            ]);
            break;
            /*
            case 0: // No access rights or not signed in
            default:
            $this->dispatcher->forward([
                'controller' => 'index',
                'action' => 'index'
            ]);
            break;
            */
        }      
    }

    public function logoutAction() {
        $this->session->destroy();
        $this->session->start();
        $this->dispatcher->forward([
            "controller" => "index",
            "action" => "index"
        ]);
    }
}

