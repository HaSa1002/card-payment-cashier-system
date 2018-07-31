<?php

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $ausweis;

    /**
     *
     * @var double
     */
    public $amount;

    /**
     *
     * @var string
     */
    public $pw;

    /**
     *
     * @var integer
     */
    public $access;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("kassensystem");
        $this->setSource("users");
    }

    /**
     * Return the sum of the money in the register, owned by the people
     */
    public function getSum() {
        return round($this->modelsManager->executeQuery("SELECT SUM(amount) AS sum FROM Users")->getFirst()['sum'], 2);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function save($data = NULL, $whiteList = NULL, $hash_pw = false) {
        if ($hash_pw)
            $this->pw = password_hash($this->pw, PASSWORD_BCRYPT, ["cost" => 11]);
        return parent::save($data, $whiteList);
    }

    public function verify($password) {
        return password_verify($password, $this->pw);
    }

}
