<h1>Editar Vehículo</h1>
<form method="POST" action="index.php?controller=vehiculo&action=editar">
  <input type="hidden" name="id" value="<?= $vehiculo['id'] ?>">

  <label>Marca:</label>
  <input type="text" name="marca" value="<?= htmlspecialchars($vehiculo['marca']) ?>" required><br>

  <label>Modelo:</label>
  <input type="text" name="modelo" value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required><br>

  <label>Año:</label>
  <input type="number" name="anio" value="<?= htmlspecialchars($vehiculo['anio']) ?>" required><br>

  <label>Categoría:</label>
  <input type="text" name="categoria" value="<?= htmlspecialchars($vehiculo['categoria']) ?>"><br>

  <label>Estado:</label>
  <select name="estado">
    <option value="disponible" <?= $vehiculo['estado']=='disponible'?'selected':'' ?>>Disponible</option>
    <option value="alquilado" <?= $vehiculo['estado']=='alquilado'?'selected':'' ?>>Alquilado</option>
    <option value="mantenimiento" <?= $vehiculo['estado']=='mantenimiento'?'selected':'' ?>>Mantenimiento</option>
  </select><br>

  <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
