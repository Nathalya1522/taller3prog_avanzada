<?php
class Reserva {
    private $conn;
    private $table = "reservas";

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function listar() {
        $stmt = $this->conn->prepare("SELECT r.*, c.nombre AS cliente, v.marca, v.modelo 
                                      FROM reservas r 
                                      JOIN clientes c ON r.cliente_id = c.id 
                                      JOIN vehiculos v ON r.vehiculo_id = v.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($data) {
        $stmt = $this->conn->prepare("INSERT INTO reservas (cliente_id, vehiculo_id, fecha_inicio, fecha_fin, estado) 
                                      VALUES (:cliente_id, :vehiculo_id, :fecha_inicio, :fecha_fin, 'activa')");
        return $stmt->execute($data);
    }

    public function editar($data) {
        $stmt = $this->conn->prepare("UPDATE reservas SET cliente_id=:cliente_id, vehiculo_id=:vehiculo_id, fecha_inicio=:fecha_inicio, fecha_fin=:fecha_fin, estado=:estado WHERE id=:id");
        return $stmt->execute($data);
    }

    public function completar($id) {
        $stmt = $this->conn->prepare("UPDATE reservas SET estado='completada' WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }

    public function cancelar($id) {
        $stmt = $this->conn->prepare("UPDATE reservas SET estado='cancelada' WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }
}
?>

