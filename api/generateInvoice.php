<?php

require_once 'App/Database/DB.php';
require_once 'App/Utils/utils.php';

use App\Database\DB;
use App\Utils;

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Extract the table name and record ID from the URL parameters
$tableName = Utils\scrub($_GET['table']);

// Perform CRUD operations based on the request method
$db = DB::getInstance();
if ($requestMethod === 'POST') {
    try {
        $payload = Utils\parseRequestBody();
        $result = generateInvoice($db, $payload);

        return array(
            'success' => true,
            'error' => '',
            'message' => '',
            'rows' => $result
        );
    } catch (Exception $e) {
        return array(
            'success' => false,
            'error' => array('errcd' => $e->getCode(), 'errmsg' => $e->getMessage()),
            'message' => ''
        );
    }
} else {
    http_response_code(405);
}

// Set the content type and return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);


function generateInvoice($db, $payload) {
    $invoiceMonth = $payload['invoice_month'];
    $invoiceDate = $invoiceMonth . '-01';
    $totalInvoiceCount = 0;
    $totalInvoiceAmount = 0;
    $rows = $db->sql("SELECT s.name as subscription, s.customer,
            s.subscription_plan, sp.cost
        FROM subscription s, subscription_plan sp
        WHERE IFNULL(s.subscription_start, ?) <= LAST_DAY(?)
        AND IFNULL(s.subscription_end, LAST_DAY(?)) >= ?
        AND sp.name = s.subscription_plan
    ", [$invoiceDate, $invoiceDate, $invoiceDate, $invoiceDate]);

    if (!empty($rows)) {
        foreach($rows as $row) {
            $name = Utils\makeAutoName($db, 'INVOICE');
            $totalInvoiceCount += 1;
            $totalInvoiceAmount += $row['cost'];
            $db->sql("INSERT INTO invoice(name, customer, subscription,
                subscription_plan, invoice_entry, invoice_month, amount,
                paid, outstanding)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [$name, $row['customer'], $row['subscription'],
            $row['subscription_plan'], $payload['name'], $invoiceMonth, $row['cost'],
            0, $row['cost']]);
        }
    }

    $db->sql("UPDATE invoice_entry SET total_invoice_count = ?, total_invoice_amount = ?
        WHERE name = ?", [$totalInvoiceCount, $totalInvoiceAmount, $payload['name']]);
    return array('total_invoice_count' => $totalInvoiceCount,
        'total_invoice_amount' => $totalInvoiceAmount);
}
