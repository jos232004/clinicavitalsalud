<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dni'])) {

    $dni = trim($_POST['dni']);

    if (!preg_match('/^[0-9]{8}$/', $dni)) {
        echo json_encode([
            'success' => false,
            'message' => 'El formato del DNI no es válido.'
        ]);
        exit;
    }

    // TOKEN FACTILIZA
    $token = '';

    // ENDPOINT FACTILIZA
    $url = "" . $dni; 

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token
        ]
    ]);

    $response = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    if ($err) {

        echo json_encode([
            'success' => false,
            'message' => 'Error de conexión con Factiliza'
        ]);
    } else {

        echo $response;
    }

    exit;
} else {

    echo json_encode([
        'success' => false,
        'message' => 'Acceso denegado'
    ]);

    exit;
}
