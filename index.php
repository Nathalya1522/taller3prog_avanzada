<?php
// index.php — Punto de entrada principal
// Toda la lógica de datos se resuelve aquí en PHP antes de renderizar la vista.

session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/models/VehiculoModel.php';
require_once __DIR__ . '/models/ClienteModel.php';
require_once __DIR__ . '/models/ReservaModel.php';

$vehModel = new VehiculoModel();
$cliModel = new ClienteModel();
$resModel = new ReservaModel();

// ── Sección activa ────────────────────────────────────────────
$seccion = $_GET['seccion'] ?? 'dashboard';
$seccionesValidas = ['dashboard', 'vehiculos', 'clientes', 'reservas', 'historial'];
if (!in_array($seccion, $seccionesValidas)) $seccion = 'dashboard';

// ── Datos del DASHBOARD ───────────────────────────────────────
$stats           = $resModel->getStats();
$todosVehiculos  = $vehModel->getAll();
$todasReservas   = $resModel->getAll();
$disponibles     = array_filter($todosVehiculos, fn($v) => $v['estado'] === 'disponible');
$reservasActivas = array_filter($todasReservas,  fn($r) => $r['estado'] === 'activa');

// ── Datos de VEHÍCULOS con filtros PHP ────────────────────────
$busqV      = trim($_GET['q_v'] ?? '');
$filtroEstV = $_GET['estado_v'] ?? '';
$vehiculos  = array_filter($todosVehiculos, function($v) use ($busqV, $filtroEstV) {
    $okTexto  = !$busqV || stripos($v['marca'] . ' ' . $v['modelo'], $busqV) !== false;
    $okEstado = !$filtroEstV || $v['estado'] === $filtroEstV;
    return $okTexto && $okEstado;
});

// ── Datos de CLIENTES con filtro PHP ─────────────────────────
$busqC         = trim($_GET['q_c'] ?? '');
$todosClientes = $cliModel->getAll();
foreach ($todosClientes as &$c) {
    $c['total_reservas'] = $cliModel->countReservas($c['id']);
}
unset($c);
$clientes = array_filter($todosClientes, function($c) use ($busqC) {
    return !$busqC || stripos($c['nombre'] . ' ' . $c['correo'], $busqC) !== false;
});

// ── Datos de RESERVAS con filtro PHP ─────────────────────────
$filtroEstR = $_GET['estado_r'] ?? '';
$reservas   = $filtroEstR
    ? array_filter($todasReservas, fn($r) => $r['estado'] === $filtroEstR)
    : $todasReservas;

// ── Datos de HISTORIAL ────────────────────────────────────────
$filtroHV         = (int)($_GET['hv'] ?? 0);
$filtroHC         = (int)($_GET['hc'] ?? 0);
$histVehiculo     = $filtroHV ? $resModel->getByVehiculo($filtroHV) : [];
$histCliente      = $filtroHC ? $resModel->getByCliente($filtroHC)  : [];
$actividadReciente = array_slice($todasReservas, 0, 10);

// ── Datos para gráfica (calculados en PHP, solo renderizados en JS) ──
$graficaActivas     = count(array_filter($todasReservas, fn($r) => $r['estado'] === 'activa'));
$graficaCompletadas = count(array_filter($todasReservas, fn($r) => $r['estado'] === 'completada'));
$graficaCanceladas  = count(array_filter($todasReservas, fn($r) => $r['estado'] === 'cancelada'));

// ── Vehículos disponibles para el modal de reserva ───────────
$vehiculosDisponibles = $vehModel->getDisponibles();

// ── Renderizar vista ─────────────────────────────────────────
require_once __DIR__ . '/views/index.php';
