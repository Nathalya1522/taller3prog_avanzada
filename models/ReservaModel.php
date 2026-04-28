<?php
require_once __DIR__ . '/../config.php';

class ReservaModel {

    public function getAll() {
        $db = getDB();
        $result = $db->query("
            SELECT r.*, c.nombre as cliente_nombre, v.marca, v.modelo
            FROM reservas r
            JOIN clientes c ON r.cliente_id = c.id
            JOIN vehiculos v ON r.vehiculo_id = v.id
            ORDER BY r.id DESC
        ");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        $db->close();
        return $rows;
    }

    public function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $row;
    }

    public function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO reservas (cliente_id, vehiculo_id, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, 'activa')");
        $stmt->bind_param("iiss", $data['cliente_id'], $data['vehiculo_id'], $data['fecha_inicio'], $data['fecha_fin']);
        $ok = $stmt->execute();
        $id = $db->insert_id;
        $db->close();
        return $ok ? $id : false;
    }

    public function updateEstado($id, $estado) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE reservas SET estado=? WHERE id=?");
        $stmt->bind_param("si", $estado, $id);
        $ok = $stmt->execute();
        $db->close();
        return $ok;
    }

    public function getByVehiculo($vehiculoId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT r.*, c.nombre as cliente_nombre
            FROM reservas r
            JOIN clientes c ON r.cliente_id = c.id
            WHERE r.vehiculo_id = ?
            ORDER BY r.id DESC
        ");
        $stmt->bind_param("i", $vehiculoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        $db->close();
        return $rows;
    }

    public function getByCliente($clienteId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT r.*, v.marca, v.modelo
            FROM reservas r
            JOIN vehiculos v ON r.vehiculo_id = v.id
            WHERE r.cliente_id = ?
            ORDER BY r.id DESC
        ");
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        $db->close();
        return $rows;
    }

    public function getActiveByVehiculo($vehiculoId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM reservas WHERE vehiculo_id=? AND estado='activa' LIMIT 1");
        $stmt->bind_param("i", $vehiculoId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $row;
    }

    public function getStats() {
        $db = getDB();
        $stats = [];
        $r = $db->query("SELECT COUNT(*) as cnt FROM vehiculos"); $stats['total_vehiculos'] = $r->fetch_assoc()['cnt'];
        $r = $db->query("SELECT COUNT(*) as cnt FROM vehiculos WHERE estado='disponible'"); $stats['disponibles'] = $r->fetch_assoc()['cnt'];
        $r = $db->query("SELECT COUNT(*) as cnt FROM vehiculos WHERE estado='alquilado'"); $stats['alquilados'] = $r->fetch_assoc()['cnt'];
        $r = $db->query("SELECT COUNT(*) as cnt FROM clientes"); $stats['total_clientes'] = $r->fetch_assoc()['cnt'];
        $db->close();
        return $stats;
    }
}
?>
