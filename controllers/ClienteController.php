<?php
require_once __DIR__ . '/../models/ClienteModel.php';

header('Content-Type: application/json');

$model = new ClienteModel();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {

    case 'list':
        $clientes = $model->getAll();
        foreach ($clientes as &$c) {
            $c['total_reservas'] = $model->countReservas($c['id']);
        }
        echo json_encode($clientes);
        break;

    case 'create':
        $data = [
            'nombre'          => trim($_POST['nombre'] ?? ''),
            'telefono'        => trim($_POST['telefono'] ?? ''),
            'correo'          => trim($_POST['correo'] ?? ''),
            'numero_licencia' => trim($_POST['numero_licencia'] ?? ''),
        ];
        if (!$data['nombre'] || !$data['numero_licencia']) {
            echo json_encode(['success' => false, 'message' => 'Nombre y número de licencia son obligatorios']);
            break;
        }
        $id = $model->create($data);
        echo json_encode(['success' => (bool)$id, 'id' => $id]);
        break;

    case 'update':
        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'nombre'          => trim($_POST['nombre'] ?? ''),
            'telefono'        => trim($_POST['telefono'] ?? ''),
            'correo'          => trim($_POST['correo'] ?? ''),
            'numero_licencia' => trim($_POST['numero_licencia'] ?? ''),
        ];
        $ok = $model->update($id, $data);
        echo json_encode(['success' => $ok]);
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        if ($model->hasActiveReservations($id)) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar: tiene reservas activas']);
            break;
        }
        $ok = $model->delete($id);
        echo json_encode(['success' => $ok]);
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
}
?>
