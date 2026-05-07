<?php
class Cliente {
    private $conn;
    private $table = "clientes";

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function listar() {
        $stmt = $this->conn->prepare("SELECT * FROM clientes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($data) {
        $stmt = $this->conn->prepare("INSERT INTO clientes (nombre, telefono, correo, numero_licencia) 
                                      VALUES (:nombre, :telefono, :correo, :numero_licencia)");
        return $stmt->execute($data);
    }

    public function editar($data) {
        $stmt = $this->conn->prepare("UPDATE clientes SET nombre=:nombre, telefono=:telefono, correo=:correo, numero_licencia=:numero_licencia WHERE id=:id");
        return $stmt->execute($data);
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
