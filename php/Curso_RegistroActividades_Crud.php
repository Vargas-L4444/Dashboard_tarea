<?php
require_once "config.php";

// Obtener todas las actividades registradas
function listarActividades($busqueda = "") {
    global $pdo;
    $query = "SELECT ra.id, u.nombre AS usuario_nombre, c.nombre AS curso_nombre, 
                     ra.accion, ra.descripcion, ra.fecha_hora 
              FROM registro_actividades ra
              INNER JOIN usuarios u ON ra.usuario_id = u.id
              INNER JOIN cursos c ON ra.curso_id = c.id";
    
    if (!empty($busqueda)) {
        $query .= " WHERE u.nombre LIKE :busqueda OR c.nombre LIKE :busqueda OR ra.accion LIKE :busqueda";
    }
    $query .= " ORDER BY ra.fecha_hora DESC, ra.id DESC";
    
    $stmt = $pdo->prepare($query);
    if (!empty($busqueda)) {
        $busqueda = "%$busqueda%";
        $stmt->bindParam(":busqueda", $busqueda, PDO::PARAM_STR);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Listar actividades en formato HTML para AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'listar') {
    $actividades = listarActividades();
    
    foreach ($actividades as $actividad) {
        echo "<tr id='fila_{$actividad['id']}'>
                <td>{$actividad['id']}</td>
                <td>{$actividad['usuario_nombre']}</td>
                <td>{$actividad['curso_nombre']}</td>
                <td>{$actividad['accion']}</td>
                <td>{$actividad['descripcion']}</td>
                <td>{$actividad['fecha_hora']}</td>
                <td>
                    <button class='btn btn-warning btn-sm editar-btn' data-id='{$actividad['id']}'>Editar</button>
                    <button class='btn btn-danger btn-sm eliminar-btn' data-id='{$actividad['id']}'>Eliminar</button>
                </td>
              </tr>";
    }
    exit;
}

// Crear una nueva actividad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'crear') {
    $usuario_id = $_POST['usuario_id'];
    $curso_id = $_POST['curso_id'];
    $accion = $_POST['accion'];
    $descripcion = $_POST['descripcion'];

    $query = "INSERT INTO registro_actividades (usuario_id, curso_id, accion, descripcion, fecha_hora) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($query);
    
    echo json_encode(["success" => $stmt->execute([$usuario_id, $curso_id, $accion, $descripcion]), "message" => "Actividad registrada con éxito"]);
    exit;
}

// Editar una actividad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'editar') {
    $id = $_POST['id'];
    $accion = $_POST['accion'];
    $descripcion = $_POST['descripcion'];

    $query = "UPDATE registro_actividades SET accion = ?, descripcion = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);

    echo json_encode(["success" => $stmt->execute([$accion, $descripcion, $id]), "message" => "Actividad actualizada con éxito"]);
    exit;
}

// Eliminar una actividad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'eliminar') {
    $id = $_POST['id'];

    $query = "DELETE FROM registro_actividades WHERE id = ?";
    $stmt = $pdo->prepare($query);

    echo json_encode(["success" => $stmt->execute([$id]), "message" => "Actividad eliminada con éxito"]);
    exit;
}
?>