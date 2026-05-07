<?php
require_once __DIR__ . '/../config.php';

class ClienteModel {

    public function getAll() {
        $db = getDB();
        $result = $db->query("SELECT * FROM clientes ORDER BY id DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        $db->close();
        return $rows;
    }

    public function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $row;
    }

    public function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO clientes (nombre, telefono, correo, numero_licencia) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['nombre'], $data['telefono'], $data['correo'], $data['numero_licencia']);
        $ok = $stmt->execute();
        $id = $db->insert_id;
        $db->close();
        return $ok ? $id : false;
    }

    public function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE clientes SET nombre=?, telefono=?, correo=?, numero_licencia=? WHERE id=?");
        $stmt->bind_param("ssssi", $data['nombre'], $data['telefono'], $data['correo'], $data['numero_licencia'], $id);
        $ok = $stmt->execute();
        $db->close();
        return $ok;
    }

    public function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $db->close();
        return $ok;
    }

    public function hasActiveReservations($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM reservas WHERE cliente_id=? AND estado='activa'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $row['cnt'] > 0;
    }

    public function countReservas($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM reservas WHERE cliente_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $row['cnt'];
    }
}
?>
