<?php

class AdminController extends ControllerBase
{

    public function indexAction() {
        if (!parent::authorized(ControllerBase::ADMIN)) return;
        if ($this->request->isPost()) {
            $ausweis = $this->request->getPost('ausweis', 'int');
            $user = Users::findFirstByAusweis($ausweis);
            if (!$user) {
                $user = new Users();
                $user->ausweis = $ausweis;
            }
            $user->pw = $this->request->getPost('pw', 'string');
            $user->access = $this->request->getPost('access', 'int');
            
            
            if ($user->save(NULL, NULL, true) === false) $this->flash->error("Der Benutzer ($ausweis) wurde nicht aktualisiert. (Access: $user->access)");
            else $this->flash->success("Der Benutzer ($ausweis) wurde aktualisiert. (Access: $user->access)");  
        }
    }

    public function saveAction() {
        
    }

}

