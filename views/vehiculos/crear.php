<!DOCTYPE html>
<html>
<head>
    <title>Agregar Vehículo</title>
</head>
<body>
    <h1>Nuevo Vehículo</h1>
    <form method="POST" action="index.php?controller=vehiculo&action=crear">
        Marca: <input type="text" name="marca"><br>
        Modelo: <input type="text" name="modelo"><br>
        Año: <input type="number" name="anio"><br>
        Categoría: <input type="text" name="categoria"><br>
        Estado: 
        <select name="estado">
            <option value="disponible">Disponible</option>
            <option value="alquilado">Alquilado</option>
            <option value="mantenimiento">Mantenimiento</option>
        </select><br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
