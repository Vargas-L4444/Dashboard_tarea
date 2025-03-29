<?php
require_once "config.php";

class Curso_PanelControl_Crud {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener cantidad de cursos activos
    public function obtenerCursosActivos() {
        $query = "SELECT COUNT(*) AS total FROM cursos WHERE estado = 'activo'";
        $stmt = $this->pdo->query($query);
        return $stmt->fetch()['total'] ?? 0;
    }

    // Obtener cantidad de estudiantes inscritos
    public function obtenerEstudiantesInscritos() {
        $query = "SELECT COUNT(*) AS total FROM usuarios WHERE rol = 'estudiante'";
        $stmt = $this->pdo->query($query);
        return $stmt->fetch()['total'] ?? 0;
    }

    // Obtener cantidad de clases programadas
    public function obtenerClasesProgramadas() {
        try {
            $query = "SELECT COUNT(*) AS total FROM calendario_clases";
            $stmt = $this->pdo->prepare($query); // Cambié $this->conexion por $this->pdo
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            die("Error en obtenerClasesProgramadas: " . $e->getMessage());
        }
    }

    // Obtener cantidad de mensajes no leídos
    public function obtenerMensajesNoLeidos() {
        $query = "SELECT COUNT(*) AS total FROM mensajes WHERE leido = 0";
        $stmt = $this->pdo->query($query);
        return $stmt->fetch()['total'] ?? 0;
    }
}