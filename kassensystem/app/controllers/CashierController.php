<?php

use Phalcon\Paginator\Adapter\NativeArray as Paginator;
use Phalcon\Mvc\Model\Query;

class CashierController extends ControllerBase {

    public function indexAction() { if (!parent::authorized(ControllerBase::CASHIER)) return; }

    public function checkinAction() { if (!parent::authorized(ControllerBase::CASHIER)) return; }

    public function addAction($page = 1, $reset = false, $perPage = 6) {
        if (!parent::authorized(ControllerBase::CASHIER)) return;
        if ($reset) {
            $this->flash->warning("Der Warenkorb wurde geleert.");
            $this->session->set('cart', []);
        }

        if ($this->request->isPost()) {
            if (!$this->session->has('cart'))
                $this->session->set('cart', []);
            
            if ($this->request->get('id', 'int') != "") {
                $cart = $this->session->get('cart');
                $id = $this->request->get('id', 'int');
                $menge = $this->request->get('menge', 'int');
                if (empty($menge) || $menge == 0)
                    $menge = 1;
                $cart[] = [$id, $menge];
                $this->session->set('cart', $cart);
            }
        }
        //Mengenfunktion
        $place_komma = false;
        $res = "";
        foreach ($this->session->get('cart') as $e) {
            if (!$place_komma) $place_komma = true;
            else $res .= ",";
            $res .= $this->filter->sanitize($e[0], 'int');
        }
        
        if ($res == "") {
            $res = -1; //Es kann keine negativen Warenid geben, folglich sollten wir nichts zrückbekommen 
        }
        
        //PHQL Binding für IN (...) nochmal nachschauen. Die hiesige Lösung ist GEFÄHRLICH
        $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id AND Waren.id IN ($res)");
        $waren = $waren->toArray(); //Cheeting here, but the next step is impossible with the Resultset
        $i = 0;
        $this->view->a_n = 0;
        $this->view->a_s = 0;
        $this->view->a_b = 0;
        $this->view->b_n = 0;
        $this->view->b_s = 0;
        $this->view->b_b = 0;
        $this->view->n = 0;
        $this->view->s = 0;
        $this->view->b = 0;

        foreach ($waren as $ware) {
            foreach ($this->session->get('cart') as $e) {
                if ($e[0] == $ware['id']) {
                    if (!isset($waren[$i]['menge'])) $waren[$i]['menge'] = 0;
                    $waren[$i]['menge'] += $e[1];
                    
                }
            }
            if ($ware['mehrwertsteuer_voll'] == "1") {
                $this->view->a_n += $ware['price'] * $waren[$i]['menge'];
                $this->view->a_b += round($ware['price'] * 1.19, 2) * $waren[$i]['menge'];
                $this->view->a_s += $this->view->a_b - $this->view->a_n;
            } else {
                $this->view->b_n += $ware['price'] * $waren[$i]['menge'];
                $this->view->b_b += round($ware['price'] * 1.07, 2) * $waren[$i]['menge'];
                $this->view->b_s += $this->view->b_b - $this->view->b_n;
            }
            $i++;
        }
        $this->view->n = $this->view->a_n + $this->view->b_n;
        $this->view->s = $this->view->a_s + $this->view->b_s;
        $this->view->b = $this->view->a_b + $this->view->b_b;
        

        if ($perPage == 0)
            $perPage = 6;
        
        $paginator = new Paginator([
            'data' => $waren,
            'limit'=> $perPage,
            'page' => $page
        ]);

        $this->view->page = $paginator->getPaginate();
        $this->view->perPage = $perPage;

    }

    public function checkAction($page = 1, $perPage = 10) {
        if (!parent::authorized(ControllerBase::CASHIER)) return;
        if (empty($this->session->get('cart'))) {
            $this->flash->error("Der Warenkorb ist leer.");
            //Log
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        $place_komma = false;
        $res = "";
        foreach ($this->session->get('cart') as $e) {
            if (!$place_komma) $place_komma = true;
            else $res .= ",";
            $res .= $e[0];
        }
        
        if ($res == "") {
            $res = -1; //Es kann keine negativen Warenid geben, folglich sollten wir nichts zrückbekommen 
        }
        
        //PHQL Binding für IN (...) nochmal nachschauen. Die hiesige Lösung ist GEFÄHRLICH
        $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id AND Waren.id IN ($res)");
        $waren = $waren->toArray(); //Cheeting here, but the next step is impossible with the Resultset
        $i = 0;
        $this->view->a_n = 0;
        $this->view->a_s = 0;
        $this->view->a_b = 0;
        $this->view->b_n = 0;
        $this->view->b_s = 0;
        $this->view->b_b = 0;
        $this->view->n = 0;
        $this->view->s = 0;
        $this->view->b = 0;

        foreach ($waren as $ware) {
            foreach ($this->session->get('cart') as $e) {
                if ($e[0] == $ware['id']) {
                    if (!isset($waren[$i]['menge'])) $waren[$i]['menge'] = 0;
                    $waren[$i]['menge'] += $e[1];
                    
                }
            }
            if ($ware['mehrwertsteuer_voll'] == "1") {
                $this->view->a_n += $ware['price'] * $waren[$i]['menge'];
                $this->view->a_b += round($ware['price'] * 1.19, 2) * $waren[$i]['menge'];
                $this->view->a_s += $this->view->a_b - $this->view->a_n;
            } else {
                $this->view->b_n += $ware['price'] * $waren[$i]['menge'];
                $this->view->b_b += round($ware['price'] * 1.07, 2) * $waren[$i]['menge'];
                $this->view->b_s += $this->view->b_b - $this->view->b_n;
            }
            $i++;
        }
        $this->view->n = $this->view->a_n + $this->view->b_n;
        $this->view->s = $this->view->a_s + $this->view->b_s;
        $this->view->b = $this->view->a_b + $this->view->b_b;

        if ($perPage == 0)
        $perPage = 10;
        
        $paginator = new Paginator([
            'data' => $waren,
            'limit'=> $perPage,
            'page' => $page
        ]);

        $this->view->page = $paginator->getPaginate();
        $this->view->perPage = $perPage;
    }

    public function checkoutAction() {
        if (!parent::authorized(ControllerBase::CASHIER)) return;
        if (empty($this->session->get('cart'))) {
            $this->flash->error("Der Warenkorb ist leer.");
            //Log
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        $place_komma = false;
        $res = "";
        foreach ($this->session->get('cart') as $e) {
            if (!$place_komma) $place_komma = true;
            else $res .= ",";
            $res .= $e[0];
        }
        
        if ($res == "") {
            $res = -1; //Es kann keine negativen Warenid geben, folglich sollten wir nichts zrückbekommen 
        }
        
        //PHQL Binding für IN (...) nochmal nachschauen. Die hiesige Lösung ist GEFÄHRLICH
        $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id AND Waren.id IN ($res)");
        $waren = $waren->toArray(); //Cheeting here, but the next step is impossible with the Resultset
        $i = 0;
        $this->view->a_n = 0;
        $this->view->a_s = 0;
        $this->view->a_b = 0;
        $this->view->b_n = 0;
        $this->view->b_s = 0;
        $this->view->b_b = 0;
        $this->view->n = 0;
        $this->view->s = 0;
        $this->view->b = 0;

        foreach ($waren as $ware) {
            foreach ($this->session->get('cart') as $e) {
                if ($e[0] == $ware['id']) {
                    if (!isset($waren[$i]['menge'])) $waren[$i]['menge'] = 0;
                    $waren[$i]['menge'] += $e[1];
                    
                }
            }
            if ($ware['mehrwertsteuer_voll'] == "1") {
                $this->view->a_n += $ware['price'] * $waren[$i]['menge'];
                $this->view->a_b += round($ware['price'] * 1.19, 2) * $waren[$i]['menge'];
                $this->view->a_s += $this->view->a_b - $this->view->a_n;
            } else {
                $this->view->b_n += $ware['price'] * $waren[$i]['menge'];
                $this->view->b_b += round($ware['price'] * 1.07, 2) * $waren[$i]['menge'];
                $this->view->b_s += $this->view->b_b - $this->view->b_n;
            }
            $i++;
        }
        $this->view->n = $this->view->a_n + $this->view->b_n;
        $this->view->s = $this->view->a_s + $this->view->b_s;
        $this->view->b = $this->view->a_b + $this->view->b_b;
    }

    public function belegAction() {
        if (!parent::authorized(ControllerBase::CASHIER)) return;
        $datetime = new DateTime("now", new DateTimeZone("europe/berlin"));
        $dbDate = $datetime->format("Y-m.d H:i:s");
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        if (!$this->session->has('cart')) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da der Warenkorb leer ist.");
            //Log
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        if (!$this->request->has('id')) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da kein Zahlender angegeben worden ist.");
            //Log
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        if (empty($this->session->get('cart'))) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da der Warenkorb leer ist.");
            //Log
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }

        $this->db->begin();
        $user = Users::findFirstByAusweis($this->request->get('id', 'int'));
        if (!$user) {
            $user = new Users();
            $user->ausweis = $this->request->get('id', 'int'); //Fälle ausschließen...
            $user->amount = 0;
            if ($user->save() === false) {
                $this->flash->error("Zahlungsvorgang wurde abgebrochen, da der Zahlende dem System weder bekannt ist noch erstellt werden konnte.");
                $this->db->rollback();
                //Log
                return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
            }
        }
        $trans_id = 0;
        $t = new Transaktionen();
        $t->user = $user->ausweis;
        $t->vertreter = $this->session->get('ausweis');
        $t->datetime = $dbDate;
        
        if ($t->save() === false) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da es einen internen Fehler gab. (0x1t)");
            $this->db->rollback();
            //Log wichtig
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        $trans_id = $t->trans_id;

        $place_komma = false;
        $res = "";
        foreach ($this->session->get('cart') as $e) {
            if (!$place_komma) $place_komma = true;
            else $res .= ",";
            $res .= $e[0];
        }
        
        if ($res == "") {
            $res = -1; //Es kann keine negativen Warenid geben, folglich sollten wir nichts zrückbekommen 
        }
        
        //PHQL Binding für IN (...) nochmal nachschauen. Die hiesige Lösung ist GEFÄHRLICH
        $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, deleted, revision, description FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id AND Waren.id IN ($res)");
        $waren = $waren->toArray(); //Cheeting here, but the next step is impossible with the Resultset
        $w_i = 0;
        $this->view->a_n = 0;
        $this->view->a_s = 0;
        $this->view->a_b = 0;
        $this->view->b_n = 0;
        $this->view->b_s = 0;
        $this->view->b_b = 0;
        $this->view->n = 0;
        $this->view->s = 0;
        $this->view->b = 0;

        foreach ($waren as $ware) {
            foreach ($this->session->get('cart') as $e) {
                if ($e[0] == $ware['id']) {
                    if (!isset($waren[$w_i]['menge'])) $waren[$w_i]['menge'] = 0;
                    $waren[$w_i]['menge'] += $e[1];
                }
            }
            if ($ware['mehrwertsteuer_voll'] == "1") {
                $this->view->a_n += $ware['price'] * $waren[$w_i]['menge'];
                $this->view->a_b += round($ware['price'] * 1.19, 2) * $waren[$w_i]['menge'];
                $this->view->a_s += $this->view->a_b - $this->view->a_n;
            } else {
                $this->view->b_n += $ware['price'] * $waren[$w_i]['menge'];
                $this->view->b_b += round($ware['price'] * 1.07, 2) * $waren[$w_i]['menge'];
                $this->view->b_s += $this->view->b_b - $this->view->b_n;
            }

            $w = new Warentransaktionen();
            $w->trans_id = $t->trans_id;
            $w->waren_id = $ware['id'];
            $w->revision = $ware['revision'];
            $w->menge = $waren[$w_i]['menge'];
            if ($w->save() === false) {
                $this->falsh->error("Zahlung konnte aufgrund eines internen Fehlers nicht durchgeführt werden. (0xcbwt)");
                $this->db->rollback();
                //Log wichtig 
                return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
            }
            $w_i++;
        }
        $this->view->n = $this->view->a_n + $this->view->b_n;
        $this->view->s = $this->view->a_s + $this->view->b_s;
        $this->view->b = $this->view->a_b + $this->view->b_b;
        
        //Eigentliche Checks usw. anstoßen
        if ($user->amount < $this->view->b) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da das Konto nicht ausreichend gedeckt ist.");
            $this->db->rollback();
            //Log
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        $user->amount -= $this->view->b;

        if ($user->save() === false) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da es einen internen Fehler gab. (0xcbu)");
            $this->db->rollback();
            //Log wichtig
            return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add"]);
        }
        $this->db->commit();
        $this->view->guthaben = $user->amount;
        $this->session->set('cart', []);
        $this->view->waren = $waren;
        $this->view->trans_id = $t->trans_id;
        $this->view->vertreter = $t->vertreter;
    }

    public function deleteAction($id, $page, $perPage) {
        if (!parent::authorized(ControllerBase::CASHIER)) return;
        $this->view->id = $id;
        $this->view->page = $page;
        $this->view->perPage = $perPage;
    }

    public function doDeleteAction($page, $perPage, $what) {
        if (!parent::authorized(ControllerBase::CASHIER)) return;
        if ($this->session->has('cart')) {
            $save = [];
            foreach ($this->session->get('cart') as $w) {
                if ($what != $w[0])
                    $save[] = $w;
            }
            $this->session->set('cart', $save);
        }
        return $this->dispatcher->forward(['controller' => "cashier", 'action' => "add", 'params' => [$page, 0, $perPage]]);
    }

}

