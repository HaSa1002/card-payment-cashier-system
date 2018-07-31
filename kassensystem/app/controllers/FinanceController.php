<?php

class FinanceController extends ControllerBase {
    public function indexAction() {
        if (!parent::authorized(ControllerBase::FINANCE)) return;
    }

    public function transactionAction() {
        if (!parent::authorized(ControllerBase::FINANCE)) return;
        $time = "now";
        if ($this->request->isPost()) {
            if ($this->request->has('dt')) {
                $time = $this->request->get('dt');
            } else {
                
            }
            $time = "$time ";
            if ($this->request->has('t')) {
                if (!empty($this->request->get('t')))
                $time = $time . $this->request->get('t');
                else $time = "$time 00:00";
            } else {
                $time = "$time 00:00";
            }
            $time = "$time:00";
        }

        $t = new Transaktionen();
        $datetime = new DateTime($time, new DateTimeZone("europe/berlin"));
        $dbDate = $datetime->format("Y-m.d H:i:s");
        $this->view->t = $t->getTransactionsSince($dbDate);
        
    }

    public function registerAction() {
        if (!parent::authorized(ControllerBase::FINANCE)) return;
        $time = "now";
        if ($this->request->isPost()) {
            if ($this->request->has('dt')) {
                $time = $this->request->get('dt');
            } else {
                
            }
            $time = "$time ";
            if ($this->request->has('t')) {
                if (!empty($this->request->get('t')))
                $time = $time . $this->request->get('t');
                else $time = "$time 00:00";
            } else {
                $time = "$time 00:00";
            }
            $time = "$time:00";
        }

        $t = new Transaktionen();
        $datetime = new DateTime($time, new DateTimeZone("europe/berlin"));
        $u = new Users();
        $this->view->t_sum = $u->getSum();
        $k = new Kartentransaktionen();
        $dbDate = $datetime->format("Y-m.d H:i:s");
        $this->view->t_saldo = $k->getSumSince($dbDate);
        $income = $t->getIncomeSince($dbDate);
        $this->view->income = $income[0];
        $this->view->taxes = $income[1];
        $this->view->i_since = $datetime->format("d.m.Y H:i:s");

        //Change Datetime to now
        $datetime = new DateTime("now", new DateTimeZone("europe/berlin"));
        $this->view->t_date = $datetime->format("d.m.Y H:i:s");
    }

    public function printTransactionAction() {
        if (!parent::authorized(ControllerBase::FINANCE)) return;
        if ($this->request->isPost()) {
            $this->view->a_n = 0;
            $this->view->a_s = 0;
            $this->view->a_b = 0;
            $this->view->b_n = 0;
            $this->view->b_s = 0;
            $this->view->b_b = 0;
            $this->view->n = 0;
            $this->view->s = 0;
            $this->view->b = 0;

            $t = new Transaktionen();
            $trans_id = $this->request->getPost('id', 'int');
            $w = $t->getTransaction($trans_id);
            if (!$w) {
                $this->flash->error("Die Transaktion $trans_id ist nicht vorhanden.");
                return;
            }
            $this->view->trans_id = $trans_id;

            $waren = $w[1]->toArray();
            $w_i = 0;
            foreach ($waren as $ware) {
                if ($ware['mehrwertsteuer_voll'] == "1") {
                    $this->view->a_n += $ware['price'] * $waren[$w_i]['menge'];
                    $this->view->a_b += round($ware['price'] * 1.19, 2) * $waren[$w_i]['menge'];
                    $this->view->a_s += $this->view->a_b - $this->view->a_n;
                } else {
                    $this->view->b_n += $ware['price'] * $waren[$w_i]['menge'];
                    $this->view->b_b += round($ware['price'] * 1.07, 2) * $waren[$w_i]['menge'];
                    $this->view->b_s += $this->view->b_b - $this->view->b_n;
                }
                $w_i++;
            }

            $this->view->n = $this->view->a_n + $this->view->b_n;
            $this->view->s = $this->view->a_s + $this->view->b_s;
            $this->view->b = $this->view->a_b + $this->view->b_b;

            $this->view->waren = $waren;
            $this->view->trans_id = $trans_id;
            $this->view->vertreter = $w[0]->vertreter;
            $this->view->pick('cashier/beleg');
            return;
        }

    }

    public function exportAction() {
        
    }

    protected static function getDt($y) {
        $dt = date_create_from_format( "Y", $y);
        $feb = date("L", $dt->getTimestamp());
        if ($feb) $feb = "$y-02-29";
        else $feb = "$y-02-28";
        return ["$y-01-01", "$y-01-31", "$y-02-01", $feb, "$y-03-01", "$y-03-31", "$y-04-01", "$y-04-30", "$y-05-01", "$y-05-31",
        "$y-06-01", "$y-06-30", "$y-07-01", "$y-07-31", "$y-08-01", "$y-08-31", "$y-09-01", "$y-09-30", "$y-10-01", "$y-10-31",
        "$y-11-01", "$y-11-30", "$y-12-01", "$y-12-31"];
    }

    public function incomeAction($year = -1) {
        if (!parent::authorized(ControllerBase::FINANCE)) return;
        //If the year is below 0, we have to set the current year
        if ($year < 0) $year = date("Y");


        $cur_mon = date("n") - 1;
        $t = new Transaktionen();
        $out = [];
        $mons = $this->getDt($year);
        for ($i = 0; $i < 24; $i += 2) {
            $out[] = $t->getIncomeBetween($mons[$i], $mons[$i+1]);
        }
        //Set the current month a additional value, so that we can do nice stuff with the cards
        if ($year == date("Y")) $out[$cur_mon][5] = 1;
        $this->view->t = $out;
        $this->view->year = $year;


    }

    public function kartentransaktionenAction() {
        if (!parent::authorized(ControllerBase::FINANCE)) return;
        $time = "now";
        if ($this->request->isPost()) {
            if ($this->request->has('dt')) {
                $time = $this->request->get('dt');
            } else {
                
            }
            $time = "$time ";
            if ($this->request->has('t')) {
                if (!empty($this->request->get('t')))
                $time = $time . $this->request->get('t');
                else $time = "$time 00:00";
            } else {
                $time = "$time 00:00";
            }
            $time = "$time:00";
        }

        $datetime = new DateTime($time, new DateTimeZone("europe/berlin"));
        $dbDate = $datetime->format("Y-m-d H:i:s");
        $t = Kartentransaktionen::find(["datetime" => $dbDate]);
        
        $this->view->t = $t;
        
    }

}
