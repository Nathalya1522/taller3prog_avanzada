<h1>Editar Reserva</h1>
<form method="POST" action="index.php?controller=reserva&action=editar">
  <input type="hidden" name="id" value="<?= $reserva['id'] ?>">

  <label>Cliente:</label>
  <select name="cliente_id" required>
    <?php foreach ($clientes as $c): ?>
      <option value="<?= $c['id'] ?>" <?= $c['id']==$reserva['cliente_id']?'selected':'' ?>>
        <?= htmlspecialchars($c['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select><br>

  <label>Vehículo:</label>
  <select name="vehiculo_id" required>
    <?php foreach ($vehiculos as $v): ?>
      <option value="<?= $v['id'] ?>" <?= $v['id']==$reserva['vehiculo_id']?'selected':'' ?>>
        <?= htmlspecialchars($v['marca'].' '.$v['modelo'].' ('.$v['anio'].')') ?>
      </option>
    <?php endforeach; ?>
  </select><br>

  <label>Fecha inicio:</label>
  <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($reserva['fecha_inicio']) ?>" required><br>

  <label>Fecha fin:</label>
  <input type="date" name="fecha_fin" value="<?= htmlspecialchars($reserva['fecha_fin']) ?>" required><br>

  <label>Estado:</label>
  <select name="estado">
    <option value="activa" <?= $reserva['estado']=='activa'?'selected':'' ?>>Activa</option>
    <option value="completada" <?= $reserva['estado']=='completada'?'selected':'' ?>>Completada</option>
    <option value="cancelada" <?= $reserva['estado']=='cancelada'?'selected':'' ?>>Cancelada</option>
  </select><br>

  <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
