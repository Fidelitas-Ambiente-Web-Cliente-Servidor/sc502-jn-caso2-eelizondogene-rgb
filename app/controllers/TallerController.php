<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Taller.php';
require_once __DIR__ . '/../models/Solicitud.php';

class TallerController
{
    private $tallerModel;
    private $solicitudModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->tallerModel = new Taller($db);
        $this->solicitudModel = new Solicitud($db);
    }

    public function index()
    {
        if (!isset($_SESSION['id'])) {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/taller/listado.php';
    }
    
    public function getTalleresJson()
    {
        if (!isset($_SESSION['id'])) {
            echo json_encode([]);
            return;
        }
        
        $talleres = $this->tallerModel->getAllDisponibles();
        header('Content-Type: application/json');
        echo json_encode($talleres);
    }
    
    public function solicitar()
    {
        if (!isset($_SESSION['id'])) {
            echo json_encode(['success' => false, 'error' => 'Debes iniciar sesión']);
            return;
        }

        $tallerId = $_POST['taller_id'] ?? 0;
        $usuarioId = $_SESSION['id'];

        // Verificar que el taller existe y tiene cupo
        $taller = $this->tallerModel->getById($tallerId);
        if (!$taller) {
            echo json_encode(['success' => false, 'error' => 'Taller no encontrado']);
            return;
        }

        if ($taller['cupo_disponible'] <= 0) {
            echo json_encode(['success' => false, 'error' => 'El taller no tiene cupos disponibles']);
            return;
        }

        // Validar que no tenga solicitud activa o aprobada para ese taller
        if ($this->solicitudModel->existeSolicitud($tallerId, $usuarioId)) {
            echo json_encode(['success' => false, 'error' => 'Ya tienes una solicitud activa o aprobada para este taller']);
            return;
        }

        // Crear la solicitud
        if ($this->solicitudModel->crear($tallerId, $usuarioId)) {
            echo json_encode(['success' => true, 'message' => 'Solicitud enviada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al crear la solicitud']);
        }
    }
}