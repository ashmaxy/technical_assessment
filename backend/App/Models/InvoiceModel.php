<?php 

use App\Base;
use App\Database;

class InvoiceModel {

    private $db;

    function __construct()
    {
        $this->db = Base::resolve(Database::class);
    }

    public function checkInvoiceNoExist($invoiceNo) {
        $invoice = $this->db->query('SELECT * FROM invoices WHERE invoice_number = :invoice_number', [
            'invoice_number' => $invoiceNo,
        ])->find();

        if ($invoice) {
            return true;
        }

        return false;
    }

    public function addInvoice($data) {
        $invoice = $this->db->query('
            INSERT INTO invoices (
                client_id, 
                invoice_date, 
                invoice_due_date, 
                invoice_number, 
                tax_rate, 
                other_amount
            ) VALUES (
                :client_id, 
                :invoice_date, 
                :invoice_due_date, 
                :invoice_number, 
                :tax_rate, 
                :other_amount
            )', [
                'client_id' => $data['client_id'],
                'invoice_date' => $data['invoice_date'],
                'invoice_due_date' => $data['invoice_due_date'],
                'invoice_number' => $data['invoice_number'],
                'tax_rate' => $data['tax_rate'],
                'other_amount' => $data['other_amount']
            ])->lastInsertId();

        return $invoice;
    }
}