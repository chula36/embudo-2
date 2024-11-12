<?php
require 'vendor/autoload.php';

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;
use Sendpulse\RestApi\ApiClientException;

define('API_USER_ID', 'd34128ce21ece442357407d4793f0517');
define('API_SECRET', 'f324237dc03220b20a35ca644ec2e58a');

$servername = "sql213.infinityfree.net";
$username = "if0_34588530";
$password = "Oliver040186";
$dbname = "if0_34588530_cxpro1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM ContactFormwinck WHERE sent_to_sendpulse = FALSE";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $apiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

    while ($row = $result->fetch_assoc()) {
        $newEmail = [
            'email' => $row['email'],
            'variables' => [
                'Nombre' => $row['name'],
                'asunto' => $row['subject'],
                'mensaje' => $row['message'],
                'fecha de envio' => $row['submission_date'],
            ]
        ];

        // Imprimir los datos antes de enviarlos a Sendpulse
        echo "Datos a enviar a Sendpulse: " . json_encode($newEmail) . "<br>";

        try {
            $response = $apiClient->post('addressbooks/735697/emails', [
                'emails' => [
                    $newEmail
                ]
            ]);

            if ($response['result']) {
                $updateSql = "UPDATE ContactFormwinck SET sent_to_sendpulse = TRUE WHERE id = " . $row['id'];
                $conn->query($updateSql);
                echo "Los datos se han enviado correctamente a Sendpulse para el email: " . $row['email'] . "<br>";
            } else {
                echo "Hubo un error al intentar enviar los datos a Sendpulse para el email: " . $row['email'] . "<br>";
            }
        } catch (ApiClientException $e) {
            echo "Error al enviar a Sendpulse: " . $e->getMessage() . "<br>";
        }
    }

} else {
    echo "No se encontraron datos en la tabla appointments para enviar a Sendpulse.";
}

$conn->close();
?>
