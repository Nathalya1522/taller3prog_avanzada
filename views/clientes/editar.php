<h1>Editar Cliente</h1>
<form method="POST" action="index.php?controller=cliente&action=editar">
  <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

  <label>Nombre:</label>
  <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required><br>

  <label>Teléfono:</label>
  <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>"><br>

  <label>Correo:</label>
  <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>"><br>

  <label>Número de licencia:</label>
  <input type="text" name="numero_licencia" value="<?= htmlspecialchars($cliente['numero_licencia']) ?>" required><br>

  <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
