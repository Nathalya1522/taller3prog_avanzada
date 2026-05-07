<h1>Agregar Cliente</h1>
<form method="POST" action="index.php?controller=cliente&action=crear">
  <label>Nombre:</label>
  <input type="text" name="nombre" required><br>

  <label>Teléfono:</label>
  <input type="text" name="telefono"><br>

  <label>Correo:</label>
  <input type="email" name="correo"><br>

  <label>Número de licencia:</label>
  <input type="text" name="numero_licencia" required><br>

  <button type="submit" class="btn btn-primary">Guardar</button>
</form>
