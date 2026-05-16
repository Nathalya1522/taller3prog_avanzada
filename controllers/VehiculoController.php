<?php
require_once __DIR__ . '/../models/VehiculoModel.php';
require_once __DIR__ . '/../models/ReservaModel.php';

header('Content-Type: application/json');

$model = new VehiculoModel();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {

    case 'list':
        $vehiculos = $model->getAll();
        foreach ($vehiculos as &$v) {
            $v['puedeDevolver'] =
                $v['estado'] === 'alquilado';
            $v['badge'] =
                'badge-' . $v['estado'];
            $v['textoEstado'] =
                ucfirst($v['estado']);
            // opcional: acción disponible
            $v['puedeEliminar'] =
                $v['estado'] !== 'alquilado';
        }
        echo json_encode($vehiculos);
        break;
    case 'disponibles':
        $vehiculos = $model->getDisponibles();
        foreach ($vehiculos as &$v) {
            $v['badge'] =
                'badge-disponible';
            $v['textoEstado'] =
                'Disponible';
            $v['puedeDevolver'] = false;
        }
        echo json_encode($vehiculos);
        break;
    case 'create':
        $data = [
            'marca' =>
            trim($_POST['marca'] ?? ''),
            'modelo' =>
            trim($_POST['modelo'] ?? ''),
            'anio' =>
            (int)($_POST['anio'] ?? 0),
            'categoria' =>
            trim($_POST['categoria'] ?? 'Sedán'),
            'estado' =>
            trim($_POST['estado'] ?? 'disponible'),
        ];
        if(
            !$data['marca']
            ||
            !$data['modelo']
            ||
            !$data['anio']
        ){
            echo json_encode([
                'success'=>false,
                'message'=>
                'Marca, modelo y año son obligatorios'
            ]);
            break;
        }
        $id=$model->create($data);
        echo json_encode([
            'success'=>(bool)$id,
            'id'=>$id
        ]);
        break;
    case 'update':
        $id=(int)($_POST['id'] ?? 0);
        $data=[
            'marca'=>
            trim($_POST['marca'] ?? ''),

            'modelo'=>
            trim($_POST['modelo'] ?? ''),

            'anio'=>
            (int)($_POST['anio'] ?? 0),

            'categoria'=>
            trim($_POST['categoria'] ?? ''),

            'estado'=>
            trim($_POST['estado'] ?? ''),
        ];
        $ok=$model->update($id,$data);

        echo json_encode([
            'success'=>$ok
        ]);
        break;
    case 'delete':
        $id=(int)($_POST['id'] ?? 0);
        if($model->hasActiveReservations($id)){

            echo json_encode([
                'success'=>false,
                'message'=>
                'No se puede eliminar: tiene reservas activas'
            ]);
            break;
        }
        $ok=$model->delete($id);
        echo json_encode([
            'success'=>$ok
        ]);
        break;
    case 'devolver':
        $id=(int)($_POST['id'] ?? 0);
        $resModel =
        new ReservaModel();
        $reserva=
        $resModel
        ->getActiveByVehiculo($id);
        if($reserva){
            $resModel
            ->updateEstado(
                $reserva['id'],
                'completada'
            );
        }
        $ok=
        $model
        ->updateEstado(
            $id,
            'disponible'
        );
        echo json_encode([
            'success'=>$ok
        ]);
        break;
    case 'stats':
        $resModel =
        new ReservaModel();
        $stats=
        $resModel->getStats();
        echo json_encode($stats);
        break;
    default:

        echo json_encode([
            'error'=>'Acción no válida'
        ]);
}
?>
