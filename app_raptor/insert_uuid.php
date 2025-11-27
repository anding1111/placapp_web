<?php
// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'mipospro_raptor';
$username = 'mipospro_raptor';
$password = '7EVT%Zltzn2;';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Establecer el modo de error de PDO para excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos $dbname :" . $e->getMessage());
}

// Recibir el correo electrónico del usuario enviado por POST
$email = isset($_POST['user']) ? $_POST['user'] : '';
$uuid = isset($_POST['uuid']) ? $_POST['uuid'] : '';

if (!empty($email) && !empty($uuid)) {
    // Paso 1: Consulta para obtener el user_id
    $sql = "SELECT id FROM users WHERE username = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Paso 2: Extraer el user_id
        $user_id = $result['id'];

        // Verificar si ya existe el user_id en uuids
        $sqlExist = "SELECT user_id FROM uuids WHERE user_id = :user_id LIMIT 1";
        $stmtExist = $pdo->prepare($sqlExist);
        $stmtExist->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtExist->execute();

        if ($stmtExist->rowCount() > 0) {
            // El user_id ya existe, actualizar el uuid
            $sqlUpdate = "UPDATE uuids SET uuid = :uuid, status = :state WHERE user_id = :user_id";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $state = 1; // O el estado que necesites establecer
            $stmtUpdate->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':state', $state, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            try {
                $stmtUpdate->execute();
                echo "UUID actualizado con éxito";
            } catch (PDOException $e) {
                echo "Error al actualizar en la base de datos: " . $e->getMessage();
            }
        } else {
            // El user_id no existe, insertar nuevo registro
            $sqlInsert = "INSERT INTO uuids (`user_id`, `uuid`, `status`) VALUES (:user_id, :uuid, :state)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $state = 1; // Suponiendo que el estado siempre es 1
            $stmtInsert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtInsert->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $stmtInsert->bindParam(':state', $state, PDO::PARAM_INT);
            try {
                $stmtInsert->execute();
                echo "Dato insertado con éxito";
            } catch (PDOException $e) {
                echo "Error al insertar en la base de datos: " . $e->getMessage();
            }
        }
    } else {
        echo "No se encontró un usuario con ese correo electrónico.";
    }
} else {
    echo "Correo electrónico o UUID no proporcionado.";
}
