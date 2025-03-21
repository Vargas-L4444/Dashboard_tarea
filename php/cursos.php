<?php
require_once 'config.php'; // Conexión a la BD

// Método para obtener los cursos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM cursos");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Método para agregar un nuevo curso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO cursos (nombre, descripcion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['nombre'], $data['descripcion'], $data['fecha_inicio'], $data['fecha_fin']]);
    echo json_encode(["mensaje" => "Curso agregado exitosamente"]);
}
?>