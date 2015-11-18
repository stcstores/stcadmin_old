<?php
namespace STCAdmin;

class Database extends \LSPHP\DatabaseConnection {

    public function __construct()
    {
        $host = "mysql.stcadmin.stcstores.co.uk";
        $database = "seatontrading";
        $user = "seatontrading";
        $passwd = "Cosworth1";
        $formFieldTable = 'new_product_form_field';
        $specialCharactersTable = 'special_characters';
        parent::__construct($host, $database, $user, $passwd);
    }

    public function getKeyFields()
    {
        $selectQuery = "SELECT field_name, field_title FROM {$formFieldTable} WHERE `can_be_key` = TRUE ORDER BY position;";
        $results = $this->selectQuery($selectQuery);
        return $results;
    }

    public function getFormFieldsByPage($page)
    {
        $query = "SELECT * FROM {$formFieldTable} WHERE page='{$page}' ORDER BY position;";
        $results = $this->selectQuery($query);
        return $results;
    }

    public function getVarSetupFields()
    {
        $varSetup = $this->getFormFieldsByPage('var_setup');
        $extendedProperties = $this->getFormFieldsByPage('extended_properties');
        foreach ($varSetup as $varSetupField) {
            $fields[] = $varSetupField;
        }
        foreach ($extendedProperties as $property) {
            $fields[] = $property;
        }

        return $fields;
    }

    public function getExtendedProperties()
    {
        $selectQuery = "SELECT field_name, field_title FROM {$formFieldTable} WHERE csv='extended'";
        $results = $this->selectQuery($selectQuery);
        $extendedProps = array();
        foreach ($results as $result) {
            $extendedProps[] = array('field_name' => $result['field_name'], 'field_title' => $result['field_title']);
        }
        return $extendedProps;
    }

    public function getSpecialCharacters()
    {
        $selectQuery = "SELECT sc, name FROM {$specialCharactersTable};";
        $results = $this->selectQuery($selectQuery);
        return $results;
    }
}
