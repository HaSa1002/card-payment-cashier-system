<?php

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Query;

class CashierController extends \Phalcon\Mvc\Controller {

    public function indexAction() {

    }

    public function checkinAction() {

    }

    public function addAction($page = 1, $perPage = 10, $reset = false) {
        
        if ($reset)
            $this->session->set('cart', []);

        if ($this->request->isPost()) {
            if (!$this->session->has('cart'))
                $this->session->set('cart', []);
            
            if ($this->request->get('id', 'int') != "") {
                $cart = $this->session->get('cart');
                $cart[] = $this->request->get('id', 'int');
                $this->session->set('cart',  $cart);
            }
        }
        $place_komma = false;
        $res = "";
        foreach ($this->session->get('cart') as $id) {
            if (!$place_komma) $place_komma = true;
            else $res .= ",";
            $res .= $id;
        }
        
        if ($res == "") {
            $res = -1; //Es kann keine negativen Warenid geben, folglich sollten wir nichts zrückbekommen 
        }
        
        //PHQL Binding für IN (...) nochmal nachschauen. Die hiesige Lösung ist GEFÄHRLICH
        $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id AND Waren.id IN ($res)", ["id" => "sdfl"]);

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

    public function checkAction($page = 1, $perPage = 10) {
        $place_komma = false;
        $res = "";
        foreach ($this->session->get('cart') as $id) {
            if (!$place_komma) $place_komma = true;
            else $res .= ",";
            $res .= $id;
        }

        //PHQL Binding für IN (...) nochmal nachschauen. Die hiesige Lösung ist GEFÄHRLICH
        $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created 
        FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id AND Waren.id IN ($res)", ["id" => $this->session->get('cart')]);
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
        
        if (!$this->session->has('cart')) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da der Warenkorb leer ist.");
            //Log
            return;
        }
        $price = 0;
        $mwstA = 0; //Voller MwSt. Satz
        $mwstB = 0; //Ermäßigter MwSt. Satz

        foreach($this->session->get('cart') as $c) {
            $q = $this->modelsManager->executeQuery("SELECT price, mehrwertsteuer_voll, deleted FROM Warenrevisionen, Waren 
            WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = :id: AND Waren.id = :id:", ["id" => $c]);
            $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id", ["id" => "sdfl"]);
            if (!$q) continue;
            foreach ($q as $i) {
                if ($i->deleted)
                    continue;
                
                $price += $i->price;
                if ($i->mehrwertsteuer_voll) $mwstA += $i->price * 0.19;
                else $mwstB += $i->price * 0.07;
            }
        }
        $price += $mwstA + $mwstB;

        $this->view->price = str_replace('.', ',', round($price, 2));
        $this->view->mwstA = str_replace('.', ',', round($mwstA, 2));
        $this->view->mwstB = str_replace('.', ',', round($mwstB, 2));
    }

    public function belegAction() {


        $datetime = new DateTime("now", new DateTimeZone("europe/berlin"));
        $dbDate = $datetime->format("Y-m.d H:i:s");
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(["controller" => "cashier", "action" => "check"]);
            //Log
            return;
        }
        if (!$this->session->has('cart')) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da der Warenkorb leer ist.");
            //Log
            return;
        }
        if (!$this->request->has('id')) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da kein Zahlender angegeben worden ist.");
            //Log
            return;
        }
        ///Transaktion startet hier
        $user = Users::findFirstByAusweis($this->request->get('id', 'int'));
        if (!$user) {
            $user = new Users();
            $user->ausweis = $this->request->get('id', 'int'); //Fälle ausschließen...
            $user->amount = 0;
            if (!$user->save()) {
                $this->flash->error("Zahlungsvorgang wurde abgebrochen, da der Zahlende dem System weder bekannt ist noch erstellt werden konnte.");
                //Log
                return;
            }
        }
        $price = 0;
        $mwstA = 0; //Voller MwSt. Satz
        $mwstB = 0; //Ermäßigter MwSt. Satz
        $trans_id = 0;
        $t = new Transaktionen();
        $t->user = $user->ausweis;
        $t->vertreter = $this->session->get('ausweis');
        $t->datetime = $dbDate;
        
        if (!$t->save()) {
            var_dump($t);
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da es einen internen Fehler gab. (0x1t)");
            //Log
            return;
        }
        $trans_id = $t->trans_id;
        foreach($this->session->get('cart') as $c) {
            $q = $this->modelsManager->executeQuery("SELECT price, mehrwertsteuer_voll, deleted, revision FROM Warenrevisionen, Waren 
            WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = :id: AND Waren.id = :id:", ["id" => $c]);
            $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created FROM Warenrevisionen, Waren WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id", ["id" => "sdfl"]);
            if (!$q) continue;
            foreach ($q as $i) {
                if ($i->deleted)
                    continue;

                $price += $i->price;
                if ($i->mehrwertsteuer_voll) $mwstA += $i->price * 0.19;
                else $mwstB += $i->price * 0.07;
                $w = Warentransaktionen::find(["trans_id" => $t->trans_id, "waren_id" => $c, "revision" => $i->revision]);
                if (!isset($w->trans_id)) {
                    $w = new Warentransaktionen();
                    $w->trans_id = $t->trans_id;
                    $w->waren_id = $c;
                    $w->revision = $i->revision;
                    $w->menge = 1;
                } else $w->menge += 1;
                $w->save();
            }
        }
        $price += $mwstA + $mwstB;
        //Eigentliche Checks usw. anstoßen
        if ($user->amount < $price) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da das Konto nicht ausreichend gedeckt ist.");
            //Hier muss ich dann ein Rollback durchführen
            return;
        }
        $user->amount -= $price;

        if (!$user->save()) {
            $this->flash->error("Zahlung konnte nicht durchgeführt werden, da es einen internen Fehler gab. (0x1u)");
            //Log
            return;
        }
        $this->view->price = str_replace('.', ',', round($price, 2));
        $this->view->mwstA = str_replace('.', ',', round($mwstA, 2));
        $this->view->mwstB = str_replace('.', ',', round($mwstB, 2));
        $sum = str_replace('.', ',', round($mwstA, 2) + round($mwstB, 2));
        $price = $this->view->price;
        $this->flash->success("Die Zahlung über $price (MwSt.: $sum) wurde durchgeführt.");
        $this->session->set('cart', []);        
    }

    public function deleteAction($page, $perPage, $what) {
        if ($this->session->has('cart')) {
            $save = [];
            foreach ($this->session->get('cart') as $w) {
                if ($what != $w)
                    $save[] = $w;
            }
        }
        $this->dispatcher->forward(["cashier/add/$page/$perPage/"]); //Über JS was lösen?
    }

}

