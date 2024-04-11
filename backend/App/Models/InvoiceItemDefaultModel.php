<?php 

use App\Base;
use App\Database;

class InvoiceItemDefaultModel {

    private $db;

    function __construct()
    {
        $this->db = Base::resolve(Database::class);
    }

    public function getInitialItems() {
        $items = $this->db->query('SELECT * FROM invoice_item_defaults ORDER BY name ASC LIMIT 10')->get();

        return $items;
    }

    public function searchData($searchTerm) {
        $note = $this->db->query('SELECT * FROM invoice_item_defaults WHERE name LIKE :name ORDER BY name ASC LIMIT 10', [
            'name' => $searchTerm . "%"
        ])->get();

        return $note;
    }

    public function searcItem($name) {
        $item = $this->db->query('SELECT * FROM invoice_item_defaults WHERE name = :name ', [
            'name' => $name
        ])->find();

        return $item;
    }

    public function addDefaultItem($data) {
        $item = $this->db->query('
            INSERT INTO invoice_item_defaults (
                name, 
                taxed, 
                amount
            ) VALUES (
                :name, 
                :taxed, 
                :amount
            )', [
                'name' => $data['name'],
                'taxed' => $data['taxed'],
                'amount' => $data['amount']
            ])->lastInsertId();

        return $item;
    }

    
}