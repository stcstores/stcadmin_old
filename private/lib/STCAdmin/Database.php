<?php
namespace STCAdmin;

class Database extends \LSPHP\DatabaseConnection {

    public function __construct()
    {
        $host = "mysql.stcadmin.stcstores.co.uk";
        $database = "seatontrading";
        $user = "seatontrading";
        $passwd = "Cosworth1";
        parent::__construct($host, $database, $user, $passwd);
    }

    public function getKeyFields()
    {
        $selectQuery = "SELECT field_name, field_title FROM new_product_form_field WHERE `can_be_key` = TRUE ORDER BY position;";
        $results = $this->selectQuery($selectQuery);
        return $results;
    }
}
