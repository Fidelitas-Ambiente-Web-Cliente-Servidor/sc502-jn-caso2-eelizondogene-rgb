<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Crear nueva solicitud de inscripción
    public function crear($tallerId, $usuarioId)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO solicitudes (taller_id, usuario_id) VALUES (?, ?)"
        );
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Verifica si el usuario ya tiene una solicitud activa o aprobada para ese taller
    public function existeSolicitud($tallerId, $usuarioId)
    {
        $stmt = $this->conn->prepare(
            "SELECT id FROM solicitudes WHERE taller_id = ? AND usuario_id = ? AND estado IN ('pendiente', 'aprobada')"
        );
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Obtener todas las solicitudes pendientes con info de taller y usuario
    public function getPendientes()
    {
        $stmt = $this->conn->prepare(
            "SELECT s.id, s.fecha_solicitud, s.estado,
                    t.nombre AS taller,
                    u.username AS solicitante
             FROM solicitudes s
             JOIN talleres t ON s.taller_id = t.id
             JOIN usuarios u ON s.usuario_id = u.id
             WHERE s.estado = 'pendiente'
             ORDER BY s.fecha_solicitud ASC"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }

    // Obtener una solicitud por id
    public function getById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT s.*, t.id AS taller_id FROM solicitudes s JOIN talleres t ON s.taller_id = t.id WHERE s.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Cambiar estado a aprobada
    public function aprobar($id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE solicitudes SET estado = 'aprobada' WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Cambiar estado a rechazada
    public function rechazar($id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE solicitudes SET estado = 'rechazada' WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
