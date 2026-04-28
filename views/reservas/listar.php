<h1>Reservas</h1>
<a href="index.php?controller=reserva&action=crear" class="btn btn-primary">+ Nueva reserva</a>
<table>
  <thead>
    <tr><th>Cliente</th><th>Vehículo</th><th>Inicio</th><th>Fin</th><th>Estado</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    <?php foreach ($reservas as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['cliente']) ?></td>
        <td><?= htmlspecialchars($r['marca'].' '.$r['modelo']) ?></td>
        <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
        <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
        <td><?= htmlspecialchars($r['estado']) ?></td>
        <td>
          <?php if ($r['estado']=='activa'): ?>
            <a href="index.php?controller=reserva&action=completar&id=<?= $r['id'] ?>" class="btn btn-sm btn-success">Completar</a>
            <a href="index.php?controller=reserva&action=cancelar&id=<?= $r['id'] ?>" class="btn btn-sm btn-danger">Cancelar</a>
          <?php endif; ?>
          <a