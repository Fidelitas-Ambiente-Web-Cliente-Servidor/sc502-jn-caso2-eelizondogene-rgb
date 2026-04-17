<?php
class Taller
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $result = $this->conn->query("SELECT * FROM talleres ORDER BY nombre");
        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }
        return $talleres;
    }

    public function getAllDisponibles()
    {
        // Solo retorna talleres con cupo_disponible > 0
        $stmt = $this->conn->prepare("SELECT * FROM talleres WHERE cupo_disponible > 0 ORDER BY nombre");
        $stmt->execute();
        $result = $stmt->get_result();
        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }
        return $talleres;
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM talleres WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function descontarCupo($tallerId)
    {
        // Verifica que haya cupo antes de descontar (evita cupo negativo)
        $stmt = $this->conn->prepare("UPDATE talleres SET cupo_disponible = cupo_disponible - 1 WHERE id = ? AND cupo_disponible > 0");
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function sumarCupo($tallerId)
    {
        $stmt = $this->conn->prepare("UPDATE talleres SET cupo_disponible = cupo_disponible + 1 WHERE id = ?");
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
