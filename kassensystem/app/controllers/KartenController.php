<?php

class KartenController extends \Phalcon\Mvc\Controller
{
    private function authorized() {
        if ($this->session->get('access') == 2) return true;
        else {
            $this->dispatcher->forward([
                "controller" => "index",
                "action" => "index"
            ]);
            return false;
        }
    }

    public function indexAction() {
        if (!$this->authorized()) return;

    }

    public function einzahlungAction($_ausweisnr = NULL, $_betrag = NULL) {
        if (!$this->authorized()) return;
        $this->view->ausweisnummer = $_ausweisnr;
        $this->view->betrag = $_betrag;
    }

    public function auszahlungAction($_ausweisnr = NULL, $_betrag = NULL) {
        if (!$this->authorized()) return;
        $this->view->ausweisnummer = $_ausweisnr;
        $this->view->betrag = $_betrag;
    }

    public function checkAction($_source) {
        if (!$this->authorized()) return;
        $this->view->source = $_source;
        $this->view->ausweisnummer = $this->request->getPost('ausweis');
        $this->view->betrag = $this->request->getPost('amount');
        $user = Users::findFirstByAusweis($this->view->ausweisnummer);
        if ($user) {
            if ($user->amount < $this->view->betrag && $_source == "Auszahlung") {
                $this->flash->warning("Das Guthabenkonto ist nur mit $user->amount Euro gedeckt.");
            }
        } else {
            $this->flash->warning("Die Ausweisnummer ist dem System unbekannt.");
        }
        if ($this->view->betrag == 0) {
            $this->flash->warning("Ein Betrag von Null ist nicht zulässig.");
        }
        
    }

    public function betragAction() {
        if ($this->request->isPost()) {
            if ($this->request->has('ausweis')) {
                $user = Users::findFirstByAusweis($this->request->get('ausweis', 'int'));
                $amount = str_replace('.', ',', $user->amount).' Euro';
                $this->flash->success("Das Guthaben von $user->ausweis beträgt $amount.");
                $this->dispatcher->forward([
                    'controller' => 'karten',
                    'action' => 'index'
                ]);
            }
        }
    }

    public function belegAction() {
        if (!$this->authorized()) return;
        $this->view->ausweisnummer = $this->request->getPost('ausweis', 'int');
        $this->view->betrag = $this->filter->sanitize(str_replace(',', '.', $this->request->getPost('amount')), 'float');
        $this->view->vertreter = $this->session->get('ausweis');
        $datetime = new DateTime("now", new DateTimeZone("europe/berlin"));
        $this->view->datum = $datetime->format("d.m.Y H:i:s");
        $transaktion = new Kartentransaktionen();
        $transaktion->user = $this->view->ausweisnummer;
        $transaktion->datetime = $datetime->format("Y-m.d H:i:s");;
        $transaktion->vertreter = $this->view->vertreter;

        if ($this->view->betrag <= 0) {
            $this->flash->error("Der Betrag muss größer als Null sein.");
            $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
            ]);
            return;
        }

        if ($this->request->getPost('source', 'string') == "Auszahlung") {
            //Transaktion start

            $user = Users::findFirstByAusweis($this->view->ausweisnummer);
            if (!$user) {
                $this->flash->error("Der Nutzer ist nicht im System vorhanden.");
                $this->dispatcher->forward([
                    'controller' => 'karten',
                    'action' => 'index'
                ]);
            } elseif ($user->amount < $this->view->betrag) {
                $this->flash->error("Der Nutzer hat nicht genügend Guthaben.");
                $this->dispatcher->forward([
                    'controller' => 'karten',
                    'action' => 'index'
                ]);
            } else {
            $user->amount -= $this->view->betrag;
            $transaktion->amount = (-1) * $this->view->betrag;
            $user->save();
            $transaktion->save();
            $this->view->trans_id = $transaktion->trans_id;
            //Logging

            $this->view->pick('karten/auszahlungsbeleg');
            //Transaktion Ende
            }
        } elseif ($this->request->getPost('source', 'string') == "Einzahlung") {
             //Transaktion start
             $user = Users::findFirstByAusweis($this->view->ausweisnummer);
             if (!$user) {
                 $user = new Users();
                $user->ausweis = $this->view->ausweisnummer;
                $user->access = 0;
             }

             $user->amount = $this->view->betrag;
             $transaktion->amount =  $this->view->betrag;
             $user->save();
             $transaktion->save();
             $this->view->trans_id = $transaktion->trans_id;
             //Logging
             //Transaktion Ende
        } else {
            $this->flash->error("Fehler 505. Bitte Vorgang wiederholen. Es wurde keine Transaktion durchgeführt. ".$this->request->getPost('source', 'string'));
            $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
            ]);
        }
    }

}

