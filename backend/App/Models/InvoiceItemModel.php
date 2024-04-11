<?php 

use App\Base;
use App\Database;

class InvoiceItemModel {

    private $db;

    function __construct()
    {
        $this->db = Base::resolve(Database::class);
    }

    public function addInvoiceItem($data) {
        $invoiceItem = $this->db->query('
            INSERT INTO invoice_items (
                invoice_item_default_id, 
                invoice_id,
                taxed, 
                amount
            ) VALUES (
                :invoice_item_default_id, 
                :invoice_id,
                :taxed, 
                :amount
            )', [
                'invoice_item_default_id' => $data['invoice_item_default_id'],
                'invoice_id' => $data['invoice_id'],
                'taxed' => $data['taxed'],
                'amount' => $data['amount']
            ])->lastInsertId();

        return $invoiceItem;
    }
}