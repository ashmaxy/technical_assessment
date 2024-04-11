<?php
$router->get('/api/populateDefaults', 'Invoice@getDefaults');
$router->get('/api/searchClients', 'Invoice@searchClients');
$router->post('/api/addClient', 'Invoice@addClient');
$router->get('/api/getClient', 'Invoice@getClient');
$router->get('/api/searchItems', 'Invoice@searchItems');
$router->post('/api/saveInvoice', 'Invoice@saveInvoice');