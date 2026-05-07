<?php
require_once __DIR__ . '/../config.php';

class VehiculoModel {

    public function getAll() {
        $db = getDB();
        $result = $db->query("SELECT * FROM vehiculos ORDER BY id DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        $db->close();
        return $rows;
    }

    public function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM vehiculos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $row;
    }

    public function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO vehiculos (marca, modelo, anio, categoria, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $data['marca'], $data['modelo'], $data['anio'], $data['categoria'], $data['estado']);
        $ok = $stmt->execute();
        $id = $db->insert_id;
        $db->close();
        return $ok ? $id : false;
    }

    public function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE vehiculos SET marca=?, modelo=?, anio=?, categoria=?, estado=? WHERE id=?");
        $stmt->bind_param("ssissi", $data['marca'], $data['modelo'], $data['anio'], $data['categoria'], $data['estado'], $id);
        $ok = $stmt->execute();
        $db->close();
        return $ok;
    }

    public function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM vehiculos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $db->close();
        return $ok;
    }

    public function updateEstado($id, $estado) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE vehiculos SET estado=? WHERE id=?");
        $stmt->bind_param("si", $estado, $id);
        $ok = $stmt->execute();
        $db->close();
        return $ok;
    }

    public function getDisponibles() {
        $db = getDB();
        $result = $db->query("SELECT * FROM vehiculos WHERE estado='disponible' ORDER BY marca");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        $db->close();
        return $rows;
    }

    public function hasActiveReservations($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM reservas WHERE vehiculo_id=? AND estado='activa'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $row['cnt'] > 0;
    }
}
?>
