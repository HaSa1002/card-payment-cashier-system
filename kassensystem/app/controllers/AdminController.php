<?php

class AdminController extends \Phalcon\Mvc\Controller
{

    public function indexAction(){
        if ($this->request->isPost()) {
            $ausweis = $this->request->getPost('ausweis', 'int');
            $user = Users::findFirstByAusweis($ausweis);
            $user->pw = $this->request->getPost('pw', 'string');
            $user->access = $this->request->getPost('access', 'int');
            if (!$user)
                $user->ausweis = $ausweis;
            
            $user->save(NULL, NULL, true);
            $this->flash->success("Der Benutzer ($ausweis) wurde aktualisiert. (Access: $user->access)");
        }
        /*
        $this->dispatcher->forward([
            "controller" => "admin",
            "action" => "index"
        ]); */
    }

    public function saveAction() {
        
    }

}

