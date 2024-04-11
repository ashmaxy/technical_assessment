<?php

use Base\Controller;

class InvoiceController extends Controller {
    
    //Gets the defualt data that will be used for client and item dropdowns
    public function getDefaults() 
    {
        $clients = $this->model('Client');
        $clientList = $clients->getInitialClients();

        $items = $this->model('InvoiceItemDefault');
        $itemList = $items->getInitialItems();

        $retVal = array(
            'clients' => $clientList,
            'items' => $itemList
        );

        $this->response->setStatusCode(200);
        $this->response->setContent($retVal);
    }

    //Used to filter clients on client select dropdown
    public function searchClients() {
        $clients = $this->model('Client');
        $clientList = $clients->searchClientByName($_GET['search_term']);

        $this->response->setStatusCode(200);
        $this->response->setContent($clientList);
    }

    //Save new client and return id
    public function addClient() {
        $client = $this->model('Client');

        if ($client->checkClientExist($_POST['newClientName'], $_POST['newCompanyName'])){
            $return = array('status' => 'error', 'message' => 'Client already exist');
            $this->response->setStatusCode(200);
            $this->response->setContent($return);
        } else {
            $data = array (
                'name' => htmlspecialchars($_POST['newClientName']), 
                'company_name' => htmlspecialchars($_POST['newCompanyName']), 
                'street_address' => htmlspecialchars($_POST['newStreetAddress']), 
                'city' => htmlspecialchars($_POST['newCity']), 
                'state' => htmlspecialchars($_POST['newState']), 
                'zip_code' => htmlspecialchars($_POST['newZipCode']), 
                'phone_number' => htmlspecialchars($_POST['newPhoneNumber'])
            );
            $insertId = $client->addClient($data);

            if ($insertId > 0) {
                $retval = $client->getClient($insertId);
                $this->response->setStatusCode(200);
                $this->response->setContent($retval);
            } else {
                $this->response->setStatusCode(400);
                $this->response->setContent('An unexpected error occured');
            }
        }
    }

    //Get client record
    public function getClient() {
        if (isset($_GET["client_id"])) {
            $client = $this->model('Client');
            $retval = $client->getClient(intval(($_GET["client_id"])));
            $this->response->setStatusCode(200);
            $this->response->setContent($retval);
        }
    }

    //Used to get items matching what the user typed on invoice line item
    public function searchItems() {
        if (isset($_GET["search_term"])) {
            $items = $this->model('InvoiceItemDefault');
            $retval = $items->searchData(htmlspecialchars($_GET["search_term"]));
            $this->response->setStatusCode(200);
            $this->response->setContent($retval);
        }
    }

    //Save invoice and all relevant info. 
    public function saveInvoice() {
        $invoice = $this->model('Invoice');

        if ($invoice->checkInvoiceNoExist($_POST['invoiceNumber'])){
            $return = array('status' => 'error', 'message' => 'Invoice number already exist');
            $this->response->setStatusCode(200);
            $this->response->setContent($return);
        } else {

            $dateNow = new DateTime();
            $dueDate = new DateTime();
            $dueDate = $dueDate->add(new DateInterval('P30D'));

            $data = array (
                'client_id' => htmlspecialchars($_POST['clientId']), 
                'invoice_date' => $dateNow->format('Y-m-d'), 
                'invoice_due_date' => $dueDate->format('Y-m-d'), 
                'invoice_number' => htmlspecialchars($_POST['invoiceNumber']), 
                'tax_rate' => htmlspecialchars($_POST['taxRate']), 
                'other_amount' => htmlspecialchars($_POST['otherAmount'])
            );

            $invoiceId = $invoice->addInvoice($data);

            if ($invoiceId > 0) {
                for ($i = 0; $i <= count($_POST['itemId']) - 1; $i++) {
                    $model = $this->model('InvoiceItemDefault');
                    $defaultItem = $model->searcItem($_POST['name'][$i]);

                    if (!$defaultItem) {
                        $data = array(
                            'name' => $_POST['name'][$i],
                            'taxed' => $_POST['taxed'][$i],
                            'amount' => $_POST['amount'][$i]
                        );
                        $defaultItemId = $model->addDefaultItem($data);
                    } else {
                        $defaultItemId = $defaultItem['invoice_item_default_id'];
                    }

                    $model = $this->model('InvoiceItem');
                    $invoiceItem = array(
                        'invoice_item_default_id' => $defaultItemId,
                        'taxed' => $_POST['taxed'][$i],
                        'amount' => $_POST['amount'][$i],
                        'invoice_id' => $invoiceId,
                    );
                    $model->addInvoiceItem($invoiceItem);
                }

                $retval = array('status' => 'success', 'message' => 'Invoice saved succesfully');
                $this->response->setStatusCode(200);
                $this->response->setContent($retval);
            } else {
                $this->response->setStatusCode(200);
                $this->response->setContent('Unexpected error');
            }
        }
    }
}