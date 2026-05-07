<h1>Nueva Reserva</h1>
<form method="POST" action="index.php?controller=reserva&action=crear">
  <label>Cliente:</label>
  <select name="cliente_id" required>
    <?php foreach ($clientes as $c): ?>
      <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
    <?php endforeach; ?>
  </select><br>

  <label>Vehículo:</label>
  <select name="vehiculo_id" required>
    <?php foreach ($vehiculos as $v): ?>
      <?php if ($v['estado'] === 'disponible'): ?>
        <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['marca'].' '.$v['modelo'].' ('.$v['anio'].')') ?></option>
      <?php endif; ?>
    <?php endforeach; ?>
  </select><br>

  <label>Fecha inicio:</label>
  <input type="date" name="fecha_inicio" required><br>

  <label>Fecha fin:</label>
  <input type="date" name="fecha_fin" required><br>

  <button type="submit" class="btn btn-primary">Crear reserva</button>
</form>
