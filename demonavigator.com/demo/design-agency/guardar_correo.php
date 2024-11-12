<?php
header('Content-Type: application/json');

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root"; // Usuario de MySQL
$password = ""; // Contraseña de MySQL
$database = "producto1"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]));
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validar el formato del correo electrónico
    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "Por favor, ingresa un correo electrónico."]);
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare("INSERT INTO suscriptores (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "¡Gracias! Tu correo electrónico ha sido registrado."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hubo un error al guardar tu correo. Inténtalo de nuevo."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Por favor, ingresa un correo electrónico válido."]);
    }
}

$conn->close();
?>
