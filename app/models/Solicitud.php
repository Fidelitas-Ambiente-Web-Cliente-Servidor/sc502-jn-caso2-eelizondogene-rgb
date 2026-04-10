<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
// Obtener todas las solicitudes pendientes con datos del taller y usuario
    public function getPendientes()
    {
        $query = "SELECT s.id, s.fecha_solicitud, s.estado,
                         t.nombre AS taller_nombre,
                         u.username AS usuario_nombre,
                         s.taller_id, s.usuario_id
                  FROM solicitudes s
                  JOIN talleres t ON s.taller_id = t.id
                  JOIN usuarios u ON s.usuario_id = u.id
                  WHERE s.estado = 'pendiente'
                  ORDER BY s.fecha_solicitud ASC";
        $result = $this->conn->query($query);
        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }

    // Verificar si ya existe solicitud activa o aprobada del usuario para el taller
    public function existeSolicitudActiva($usuarioId, $tallerId)
    {
        $query = "SELECT id FROM solicitudes
                  WHERE usuario_id = ? AND taller_id = ?
                    AND estado IN ('pendiente', 'aprobada')
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $usuarioId, $tallerId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Crear una nueva solicitud de inscripción
    public function crear($usuarioId, $tallerId)
    {
        $query = "INSERT INTO solicitudes (taller_id, usuario_id, estado) VALUES (?, ?, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Obtener solicitud por id
    public function getById($id)
    {
        $query = "SELECT * FROM solicitudes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Cambiar estado de solicitud
    public function cambiarEstado($id, $estado)
    {
        $query = "UPDATE solicitudes SET estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Rechazar solicitud
    public function rechazar($id)
    {
        return $this->cambiarEstado($id, 'rechazada');
    }

    // Aprobar solicitud
    public function aprobar($id)
    {
        return $this->cambiarEstado($id, 'aprobada');
    }
}

