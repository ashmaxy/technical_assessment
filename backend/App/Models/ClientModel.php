<?php 

use App\Base;
use App\Database;

class ClientModel {

    private $db;

    function __construct()
    {
        $this->db = Base::resolve(Database::class);
    }

    public function getInitialClients() {
        $clients = $this->db->query('SELECT * FROM clients ORDER BY name DESC LIMIT 10')->get();

        return $clients;
    }

    public function checkClientExist($name, $companyName) {
        $client = $this->db->query('SELECT * FROM clients WHERE name = :name AND company_name = :company_name', [
            'name' => $name,
            'company_name' => $companyName
        ])->find();

        if ($client) {
            return true;
        }

        return false;
    }

    public function searchClientByName($name) {
        $clients = $this->db->query('SELECT * FROM clients WHERE name LIKE :name ORDER BY name DESC LIMIT 10', [
            'name' => $name .'%'
        ])->get();

        return $clients;
    }

    public function addClient($data) {
        $client = $this->db->query('
            INSERT INTO clients (
                name, 
                company_name, 
                street_address, 
                city, 
                state, 
                zip_code, 
                phone_number
            ) VALUES (
                :name, 
                :company_name, 
                :street_address, 
                :city, 
                :state, 
                :zip_code, 
                :phone_number
            )', [
                'name' => $data['name'],
                'company_name' => $data['company_name'],
                'street_address' => $data['street_address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip_code' => $data['zip_code'],
                'phone_number' => $data['phone_number']
            ])->lastInsertId();

        return $client;
    }

    public function getClient($clientId) {
        $client = $this->db->query('SELECT * FROM clients WHERE client_id = :id', [
            'id' => $clientId
        ])->find();

        return $client;
    }
}