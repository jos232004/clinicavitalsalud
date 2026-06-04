<?php

/**
 * Controlador de Consulta Segura a API RENIEC
 * Ubicación sugerida: controllers/contReniec.php
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dni'])) {
    $dni = trim($_POST['dni']);

    // Validar sintaxis básica en servidor antes de consumir saldo de la API
    if (!preg_match('/^[0-9]{8}$/', $dni)) {
        echo json_encode(['success' => false, 'message' => 'El formato del DNI no es válido.']);
        exit;
    }

    $token = '';
    $url = '' . $dni;

    // Inicializar cURL para una petición segura externa
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        )
    ));

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión con el servidor externo.']);
    } else {
        // Retornamos directamente el JSON oficial procesado de miapi.cloud
        echo $response;
    }
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
    exit;
}
?>
