<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Query;

class WarenController extends ControllerBase {

    public function indexAction() { if (!$this->authorized(ControllerBase::GOODS)) return; }

    public function overviewAction($number_page = 1, $items_per_page = 6) {
        if (!$this->authorized(ControllerBase::GOODS)) return;
        $waren = $this->modelsManager->executeQuery("SELECT 
        Warenrevisionen.id, price, mehrwertsteuer_voll, description, deleted, created, s_mwst_full, s_price, name
        FROM Warenrevisionen, Waren, Sources WHERE (Warenrevisionen.revision = Waren.cur_rev AND Warenrevisionen.id = Waren.id 
        AND source = Sources.id) AND (Waren.id LIKE :id: OR description LIKE :id: OR name LIKE :id:) ORDER BY Waren.id ASC", ["id" => "%".$this->request->get('id', 'string')."%"]);

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

    public function deleteAction($id) {
        if (!$this->authorized(ControllerBase::GOODS)) return;
            $this->view->id = $id;
    }

    public function doDeleteAction($id) {
        if (!$this->authorized(ControllerBase::GOODS)) return;
        if ($this->request->isPost()) {
            $x = Waren::findFirstById($id);
            if (!$x) {
                $this->flash->error("Ware nicht vorhanden.");
            } else {
                $x->deleted = 1;
                $this->db->begin();
                if ($x->save() === false) {
                    $messages = $w->getMessages();
                    foreach ($messages as $mess)
                        $this->flash->error($mess);

                    $this->db->rollback();
                    //Log
                } else {
                $this->db->commit();
                $this->flash->success("Die Ware ($x->id) wurde erfolgreich gelöscht.");
                }
                echo $this->tag->linkTo(["waren/overview", "Fixen! (Weiter)", "class" => "btn btn-primary"]);
                //$overview = $this->url->get('waren/overview');
                //echo "<script type=\"text/javascript\">window.location = \"$overview\";</script>";
            }
        } else {
            $this->flash->error("Unexpected magic happend. (0xwd)");
            echo $this->tag->linkTo(["waren/overview", "Fixen!", "class" => "btn btn-primary"]);
        }
    } 

/*
    2. gleiche Warenrevisionen verhindern
*/

    public function editAction($id = -1) {
        if (!$this->authorized(ControllerBase::GOODS)) return;
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
            if ($this->request->getPost("mwst") == "19")
            $w->mehrwertsteuer_voll = 1;
            else
            $w->mehrwertsteuer_voll = 0;

            if ($this->request->getPost("s_mwst") == "19")
            $w->s_mwst_full = 1;
            else
            $w->s_mwst_full = 0;

            $w->source = $this->request->getPost('source', 'int');
            
            $w->revision = $rev;
            $x->cur_rev = $rev;
            $w->price = $this->filter->sanitize(str_replace(',', '.', $this->request->getPost('preis')), 'float');
            $w->s_price = $this->filter->sanitize(str_replace(',', '.', $this->request->getPost('s_preis')), 'float');
            $datetime = new DateTime("now", new DateTimeZone("europe/berlin"));
            $w->created = $datetime->format("Y-m-d H:i:s");
            $x->deleted = 0;
            
            $this->db->begin();
            if ($x->save() === false) {
                $messages = $w->getMessages();
                foreach ($messages as $mess) {
                    $this->flash->error($mess);
                }
                $this->db->rollback();
                //Log
                return;
            }
            if ($w->save() === false) {
                $messages = $w->getMessages();
                foreach ($messages as $mess) {
                    $this->flash->error($mess);
                }
                $this->db->rollback();
                //Log
                return;
            }
            
            $this->db->commit();
            $this->flash->success("Die Ware ($x->id.$x->cur_rev) wurde erfolgreich eingetragen.");
            $this->dispatcher->forward(["controller" => "waren", "action" => "overview"]);
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
                $this->view->active_source = 0;
                $this->view->sources = $sources;
                $this->view->s_price = 0;
                $this->view->s_mwst = 0;
            } else {
                $this->view->id = $ware[0]->id;
                $this->view->price = str_replace('.', ',', $ware[0]->price);
                $this->view->mwst = $ware[0]->mehrwertsteuer_voll;
                $this->view->description =  $ware[0]->description;
                $this->view->s_price = str_replace('.', ',', $ware[0]->s_price);
                $this->view->s_mwst = $ware[0]->s_mwst_full;
                $this->view->active_source = $ware[0]->source;
                $this->view->sources = $sources;
            }
        }
    }

    public function sourcesAction() {
        if (!$this->authorized(ControllerBase::GOODS)) return;
        if (!$this->request->isPost()) return;
        if (!$this->request->hasPost('source')) return;
        
        $s = Sources::findFirstByName($this->request->getPost('source'));
        if ($s) return;
        $s = new Sources();
        $s->name = $this->request->getPost('source');
        if ($s->save() === false) return;
    }
}

