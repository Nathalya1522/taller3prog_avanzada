<h1>Clientes</h1>
<a href="index.php?controller=cliente&action=crear" class="btn btn-primary">+ Agregar cliente</a>
<table>
  <thead>
    <tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Licencia</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    <?php foreach ($clientes as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['nombre']) ?></td>
        <td><?= htmlspecialchars($c['telefono']) ?></td>
        <td><?= htmlspecialchars($c['correo']) ?></td>
        <td><?= htmlspecialchars($c['numero_licencia']) ?></td>
        <td>
          <a href="index.php?controller=cliente&action=editar&id=<?= $c['id'] ?>" class="btn btn-sm">Editar</a>
          <a href="index.php?controller=cliente&action=eliminar&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
