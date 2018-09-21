<?php

class KartenController extends ControllerBase {

    public function indexAction() {
        if (!$this->authorized(ControllerBase::CARDS)) return;

    }

    public function einzahlungAction($_ausweisnr = NULL, $_betrag = NULL) {
        if (!$this->authorized(ControllerBase::CARDS)) return;
        $this->view->ausweisnummer = $_ausweisnr;
        $this->view->betrag = $_betrag;
    }

    public function auszahlungAction($_ausweisnr = NULL, $_betrag = NULL) {
        if (!$this->authorized(ControllerBase::CARDS)) return;
        $this->view->ausweisnummer = $_ausweisnr;
        $this->view->betrag = $_betrag;
    }

    public function checkAction($_source) {
        if (!$this->authorized(ControllerBase::CARDS)) return;
        $this->view->source = $_source;
        $this->view->ausweisnummer = $this->request->getPost('ausweis');
        $this->view->betrag = $this->filter->sanitize(str_replace(',', '.', $this->request->getPost('amount')), 'float');

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
        if ($this->view->betrag > 50) {
            $this->flash->warning("Die maximale Transaktionsmenge beträgt 50 Euro.");
            $this->view->betrag = 50;
        }
        
    }

    public function betragAction() {
        if (!$this->authorized(ControllerBase::CARDS)) return;
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
        if (!$this->authorized(ControllerBase::CARDS)) return;
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
            ]);
        }

        // Transform the german string to a float val
        $this->view->betrag = $this->filter->sanitize(str_replace(',', '.', $this->request->getPost('amount')), 'float');
        

        //We don't allow negativ amounts
        if ($this->view->betrag <= 0) {
            $this->flash->error("Der Betrag muss größer als Null sein.");
            return $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
            ]);
        }

        //We have a limit of 50€
        if ($this->view->betrag > 50) {
            $this->flash->error("Die maximale Transaktionsmenge beträgt 50 Euro.");
            return $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
            ]);
        }


        $datetime = new DateTime("now", new DateTimeZone("europe/berlin"));
        // Set the view data
        $this->view->ausweisnummer = $this->request->getPost('ausweis', 'int');
        $this->view->vertreter = $this->session->get('ausweis');
        $this->view->datum = $datetime->format("d.m.Y H:i:s");

        
        //Create a new Transaktion and fill it with data
        $transaktion = new Kartentransaktionen();
        $transaktion->user = $this->view->ausweisnummer;
        $transaktion->datetime = $datetime->format("Y-m-d H:i:s");
        $transaktion->vertreter = $this->view->vertreter;
        
        $this->view->trans_id = $transaktion->trans_id;

        $user = Users::findFirstByAusweis($this->view->ausweisnummer);
        if (!$user) {
            $user = new Users();
            $user->ausweis = $this->view->ausweisnummer;
            $user->access = 0;
        }

        if ($this->request->getPost('source', 'string') == "Auszahlung") {
            if ($user->amount < $this->view->betrag) {
                $this->flash->error("Der Nutzer hat nicht genügend Guthaben.");
                return $this->dispatcher->forward([
                    'controller' => 'karten',
                    'action' => 'index'
                    ]);
            }
            
            $user->amount -= $this->view->betrag;
            $transaktion->amount = (-1) * $this->view->betrag;
            $this->view->pick('karten/auszahlungsbeleg');

        } elseif ($this->request->getPost('source', 'string') == "Einzahlung") {        
            $user->amount += $this->view->betrag;
            $transaktion->amount = $this->view->betrag;
        } else {
            $this->flash->error("Fehler 505. Bitte Vorgang wiederholen. Es wurde keine Transaktion durchgeführt.");
            return $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
                ]);
        }
                
        $this->db->begin();
        if ($user->save() === false) {
            $this->flash->error("Geloggter Datenbankfehler. Sie können nichts tun, außer den Admin um Hilfe zu bitten.");
            $this->db->rollback();
            //We need to log here, because this could be an error that is persistent
            $f = __METHOD__ . ':' . __LINE__;
            $this->logger->critical("[$f] User can't be saved.");
            return $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
                ]);
        }
        if ($transaktion->save() === false) {
            $this->flash->error("Geloggter Datenbankfehler. Sie können nichts tun, außer den Admin um Hilfe zu bitten.");
            $this->db->rollback();
            //We need to log here, because this could be an error that is persistent
            $f = __METHOD__ . ':' . __LINE__;
            $this->logger->critical("[$f] Kartentransaktion can't be saved.");
            return $this->dispatcher->forward([
                'controller' => 'karten',
                'action' => 'index'
                ]);
        }
        $this->db->commit();
        $this->flash->success("Die Transaktion über $transaktion->amount war erfolgreich.");
        return $this->dispatcher->forward(['action' => 'einzahlung']);
    } // function
} //controller

            