<?php
date_default_timezone_set('America/Mexico_City');

/**
 * Response to the client
 * 
 * @param mixed $data
 * @param int $httpCode
 */
function response($data = null, $httpCode = 200)
{
    http_response_code($httpCode);
    echo json_encode([
        'http_status_code' => $httpCode,
        'data' => $data
    ]);
    exit;
}

// Check if SOAP Client is enabled
if (!extension_loaded('soap')) {
    response('SOAP Client is not enabled', 500);
}

// Check if WSDL URL is provided
if (empty($_GET['wsdl'])) {
    response('El campo WSDL es requerido', 400);
}

// Check if WSDL URL is valid
if (!filter_var($_GET['wsdl'], FILTER_VALIDATE_URL)) {
    response('El WSDL proporcionado no es una URL válida', 400);
}

// Check if WSDL URL is reachable
if (!@fopen($_GET['wsdl'], 'r')) {
    response('El WSDL proporcionado no es alcanzable', 400);
}

// Create SOAP Client
try {
    $client = new SoapClient($_GET['wsdl']);
} catch (\Exception $e) {
    response("Error al crear el cliente SOAP: {$e->getMessage()}", 500);
}

response([
    'message' => 'Cliente SOAP creado exitosamente',
    'wsdl'    => [
        'url'     => $_GET['wsdl'],
        'methods' => $client->__getFunctions()
    ]
]);
