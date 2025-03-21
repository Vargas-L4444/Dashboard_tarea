<?php
require_once "config.php";

// Obtener todas las notificaciones
function obtenerNotificaciones() {
    global $pdo;
    $query = "SELECT n.id, u.nombre AS usuario, n.mensaje, n.fecha_envio 
              FROM notificaciones n 
              INNER JOIN usuarios u ON n.usuario_id = u.id 
              ORDER BY n.fecha_envio DESC";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Listar notificaciones en formato HTML para AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'listar') {
    $notificaciones = obtenerNotificaciones();
    
    foreach ($notificaciones as $notificacion) {
        echo "<tr id='fila_{$notificacion['id']}'>
                <td>{$notificacion['id']}</td>
                <td>{$notificacion['usuario']}</td>
                <td>{$notificacion['mensaje']}</td>
                <td>{$notificacion['fecha_envio']}</td>
                <td>
                    <button class='btn btn-warning btn-sm editar-btn' data-id='{$notificacion['id']}' data-mensaje='" . htmlspecialchars($notificacion['mensaje']) . "'>Editar</button>
                    <button class='btn btn-danger btn-sm eliminar-btn' data-id='{$notificacion['id']}'>Eliminar</button>
                </td>
              </tr>";
    }
    exit;
}

// Crear una nueva notificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'crear') {
    $usuario_id = $_POST['usuario_id'];
    $mensaje = $_POST['mensaje'];

    $query = "INSERT INTO notificaciones (usuario_id, mensaje, fecha_envio) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($query);
    
    echo json_encode(["success" => $stmt->execute([$usuario_id, $mensaje]), "message" => "Notificación creada con éxito"]);
    exit;
}

// Editar una notificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'editar') {
    $id = $_POST['id'];
    $mensaje = $_POST['mensaje'];

    $query = "UPDATE notificaciones SET mensaje = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);

    echo json_encode(["success" => $stmt->execute([$mensaje, $id]), "message" => "Notificación actualizada con éxito"]);
    exit;
}

// Eliminar una notificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'eliminar') {
    $id = $_POST['id'];

    $query = "DELETE FROM notificaciones WHERE id = ?";
    $stmt = $pdo->prepare($query);

    echo json_encode(["success" => $stmt->execute([$id]), "message" => "Notificación eliminada con éxito"]);
    exit;
}
?>