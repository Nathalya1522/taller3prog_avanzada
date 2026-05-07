<!DOCTYPE html>
<html>
<head>
    <title>Vehículos</title>
</head>
<body>
    <h1>Lista de Vehículos</h1>
    <a href="index.php?controller=vehiculo&action=crear">Agregar Vehículo</a>
    <table border="1">
        <tr>
            <th>ID</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Categoría</th><th>Estado</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['marca'] ?></td>
                <td><?= $row['modelo'] ?></td>
                <td><?= $row['anio'] ?></td>
                <td><?= $row['categoria'] ?></td>
                <td><?= $row['estado'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

