<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Query;

class WarenController extends \Phalcon\Mvc\Controller {

    private function authorized() {
        if ($this->session->get('access') == 3) return true;
        else {
            $this->dispatcher->forward([
                "controller" => "index",
                "action" => "index"
            ]);
            return false;
        }
    }

    public function indexAction() {

    }

    public function overviewAction($number_page = 1, $items_per_page = 10) {
        
        $waren = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created, s_mwst_full, s_price, name 
        FROM Warenrevisionen, Waren, Sources WHERE Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id AND source = Sources.id AND Waren.id LIKE :id:", ["id" => "%".$this->request->get('id', 'int')."%"]);

        if ($items_per_page == 0)
            $items_per_page = 10;
        
        $paginator = new Paginator([
            'data' => $waren,
            'limit'=> $items_per_page,
            'page' => $number_page
        ]);

        $this->view->page = $paginator->getPaginate();
        $this->view->perPage = $items_per_page;

    }

    public function addAction() {
        $this->dispatcher->forward(["waren/edit/"]);
    }

/*
    TODOs: 1. autentifikation
    3. transaktionen
*/

    public function deleteAction($id) {
        if ($this->request->isPost()) {
            $x = Waren::findFirstById($id);

            if (!$x) {
                $this->flash->error("Ware nicht vorhanden.");
            } else {
                $x->deleted = 1;
                if ($x->save() === false) {
                    $messages = $w->getMessages();
                    foreach ($messages as $mess)
                        $this->flash->error($mess);
                        
                } else
                $this->flash->success("Die Ware ($x->id) wurde erfolgreich gelöscht.");
            }
            $this->dispatcher->forward(["controller" => "verkauf", "action" => "overview"]);
        } else
            $this->view->id = $id;
    }

/*
    TODOs: 1. autentifikation
    2. gleiche Warenrevisionen verhindern
    3. transaktionen
*/

    public function editAction($id = -1) {

        if ($this->request->isPost()) {
            $rev = $this->modelsManager->executeQuery("SELECT cur_rev FROM Waren WHERE Waren.id = :id:", ["id" => $this->request->getPost("id", "int")]);
            $x = Waren::findFirstById($this->request->getPost("id", "int"));
            
            if ($rev->count() == 0)
                $rev = 0;
            else
                $rev = $rev[0]->cur_rev + 1;

            if (!$x) {
                $x = new Waren();
                $x->deleted = 0;
            }

            $w = new Warenrevisionen();
            $w->id = $this->request->getPost("id", "int");
            $x->id = $this->request->getPost("id", "int");
            $w->description = $this->request->getPost("produkt", "string");
            if ($this->request->getPost("mwst") == "on")
            $w->mehrwertsteuer_voll = 1;
            else
            $w->mehrwertsteuer_voll = 0;

            $w->revision = $rev;
            $x->cur_rev = $rev;
            $w->price = $this->filter->sanitize(str_replace(',', '.', $this->request->getPost('preis')), 'float');
            $datetime = new DateTime("now", new DateTimeZone("europe/berlin"));
            $w->created = $datetime->format("Y-m-d H:i:s");
            $x->deleted = 0;
            
            if ($w->save() === false) {
                $messages = $w->getMessages();
                foreach ($messages as $mess) {
                    $this->flash->error($mess);
                }
            }
            if ($x->save() === false) {
                
                $messages = $w->getMessages();
                foreach ($messages as $mess) {
                    $this->flash->error($mess);
                    
                }
            }
            $this->flash->success("Die Ware ($x->id.$x->cur_rev) wurde erfolgreich eingetragen.");
            $this->dispatcher->forward(["controller" => "verkauf", "action" => "overview"]);
            
        } else {
            $ware = $this->modelsManager->executeQuery("SELECT Warenrevisionen.id, price, mehrwertsteuer_voll, description, s_price, s_mwst_full, source
            FROM Warenrevisionen, Waren WHERE Waren.id = :id: AND Warenrevisionen.id = Waren.id
            AND Warenrevisionen.revision = Waren.cur_rev",
            ["id" => $id]);
            $sources = $this->modelsManager->executeQuery("SELECT * FROM Sources");
            
            if (!isset($ware[0])) {
                $this->view->id = "";
                $this->view->price = "";
                $this->view->mwst =  0;
                $this->view->description = "";
            } else {
                $this->view->id = $ware[0]->id;
                $this->view->price = $ware[0]->price;
                $this->view->mwst = $ware[0]->mehrwertsteuer_voll;
                $this->view->description =  $ware[0]->description;
                $this->view->s_price = $ware[0]->s_price;
                $this->view->s_mwst_full = $ware[0]->s_mwst_full;
                $this->view->active_source = $ware[0]->source;
                $this->view->sources = $sources;


            }
        }

        
        
    }
}

