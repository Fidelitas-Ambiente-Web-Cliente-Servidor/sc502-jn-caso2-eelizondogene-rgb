<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->solicitudModel = new Solicitud($db);
        $this->tallerModel = new Taller($db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/admin/solicitudes.php';
    }
    
    public function getSolicitudesJson()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode([]);
            return;
        }

        $solicitudes = $this->solicitudModel->getPendientes();
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

    // Aprobar solicitud
    public function aprobar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }

        $solicitudId = $_POST['id_solicitud'] ?? 0;

        try {
            // Obtener la solicitud para saber el taller
            $solicitud = $this->solicitudModel->getById($solicitudId);
            if (!$solicitud) {
                echo json_encode(['success' => false, 'error' => 'Solicitud no encontrada']);
                return;
            }

            // Verificar nuevamente que haya cupo disponible en tiempo real
            $taller = $this->tallerModel->getById($solicitud['taller_id']);
            if (!$taller || $taller['cupo_disponible'] <= 0) {
                echo json_encode(['success' => false, 'error' => 'No hay cupo disponible para este taller']);
                return;
            }

            // Descontar cupo y aprobar solicitud
            $cupoDescontado = $this->tallerModel->descontarCupo($solicitud['taller_id']);
            if (!$cupoDescontado) {
                echo json_encode(['success' => false, 'error' => 'No se pudo descontar el cupo']);
                return;
            }

            $this->solicitudModel->aprobar($solicitudId);

            echo json_encode(['success' => true, 'message' => 'Solicitud aprobada correctamente']);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function rechazar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        if ($this->solicitudModel->rechazar($solicitudId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }
}