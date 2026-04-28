<?php
require_once __DIR__ . '/../models/ReservaModel.php';
require_once __DIR__ . '/../models/VehiculoModel.php';

header('Content-Type: application/json');

$resModel = new ReservaModel();
$vehModel = new VehiculoModel();
$action   = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {

    case 'list':
        echo json_encode($resModel->getAll());
        break;

    case 'byVehiculo':
        $id = (int)($_GET['id'] ?? 0);
        echo json_encode($resModel->getByVehiculo($id));
        break;

    case 'byCliente':
        $id = (int)($_GET['id'] ?? 0);
        echo json_encode($resModel->getByCliente($id));
        break;

    case 'create':
        $data = [
            'cliente_id'   => (int)($_POST['cliente_id'] ?? 0),
            'vehiculo_id'  => (int)($_POST['vehiculo_id'] ?? 0),
            'fecha_inicio' => trim($_POST['fecha_inicio'] ?? ''),
            'fecha_fin'    => trim($_POST['fecha_fin'] ?? ''),
        ];
        if (!$data['cliente_id'] || !$data['vehiculo_id'] || !$data['fecha_inicio'] || !$data['fecha_fin']) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            break;
        }
        if ($data['fecha_fin'] <= $data['fecha_inicio']) {
            echo json_encode(['success' => false, 'message' => 'La fecha de fin debe ser posterior al inicio']);
            break;
        }
        $id = $resModel->create($data);
        if ($id) {
            $vehModel->updateEstado($data['vehiculo_id'], 'alquilado');
            echo json_encode(['success' => true, 'id' => $id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear la reserva']);
        }
        break;

    case 'completar':
        $id = (int)($_POST['id'] ?? 0);
        $reserva = $resModel->getById($id);
        if ($reserva) {
            $resModel->updateEstado($id, 'completada');
            $vehModel->updateEstado($reserva['vehiculo_id'], 'disponible');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Reserva no encontrada']);
        }
        break;

    case 'cancelar':
        $id = (int)($_POST['id'] ?? 0);
        $reserva = $resModel->getById($id);
        if ($reserva) {
            $resModel->updateEstado($id, 'cancelada');
            $vehModel->updateEstado($reserva['vehiculo_id'], 'disponible');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Reserva no encontrada']);
        }
        break;

    case 'stats':
        echo json_encode($resModel->getStats());
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
}
?>
