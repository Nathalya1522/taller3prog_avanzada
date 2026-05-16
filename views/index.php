<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestor de Alquiler de Vehículos</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ============================================================
     BARRA DE NAVEGACIÓN
============================================================ -->
<div class="topbar">
  <div class="brand">
    <div class="brand-icon">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
        <rect x="2" y="10" width="20" height="8" rx="2" fill="#ffffff"/>
        <circle cx="6.5" cy="18" r="2" fill="#ffffff"/>
        <circle cx="17.5" cy="18" r="2" fill="#ffffff"/>
        <path d="M4 10l3-6h10l3 6" stroke="#ffffff" stroke-width="1.5"/>
      </svg>
    </div>
    VehicleRent
  </div>
  <div class="nav-tab active" onclick="showSection('dashboard')">Inicio</div>
  <div class="nav-tab" onclick="showSection('vehiculos')">Vehículos</div>
  <div class="nav-tab" onclick="showSection('clientes')">Clientes</div>
  <div class="nav-tab" onclick="showSection('reservas')">Reservas</div>
  <div class="nav-tab" onclick="showSection('historial')">Historial</div>
</div>

<!-- Alerta flash desde PHP (sesión) -->
<?php if (!empty($_SESSION['flash'])): ?>
<div id="alert-box" style="display:block">
  <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['tipo']) ?>">
    <?= htmlspecialchars($_SESSION['flash']['mensaje']) ?>
  </div>
</div>
<?php unset($_SESSION['flash']); ?>
<?php else: ?>
<div id="alert-box" style="display:none"></div>
<?php endif; ?>

<!-- ============================================================
     CONTENIDO PRINCIPAL
============================================================ -->
<div class="main">

  <!-- ==================== DASHBOARD ==================== -->
  <div id="section-dashboard" class="section active">

    <!-- Stats: renderizadas por PHP -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-label">Total vehículos</div>
        <div class="stat-value"><?= (int)$stats['total_vehiculos'] ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Disponibles</div>
        <div class="stat-value" style="color:#16a34a"><?= (int)$stats['disponibles'] ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Alquilados</div>
        <div class="stat-value" style="color:#1d4ed8"><?= (int)$stats['alquilados'] ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Clientes registrados</div>
        <div class="stat-value"><?= (int)$stats['total_clientes'] ?></div>
      </div>
    </div>

    <div class="grid-2">
      <!-- Vehículos disponibles: PHP -->
      <div class="card">
        <div class="card-header"><span class="card-title">Vehículos disponibles ahora</span></div>
        <?php if ($disponibles): ?>
          <?php foreach ($disponibles as $v): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
              <span><strong><?= htmlspecialchars($v['marca']) ?></strong> <?= htmlspecialchars($v['modelo']) ?> <span style="color:#94a3b8">(<?= (int)$v['anio'] ?>)</span></span>
              <span class="badge badge-disponible">disponible</span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty">Sin vehículos disponibles</div>
        <?php endif; ?>
      </div>

      <!-- Reservas activas: PHP -->
      <div class="card">
        <div class="card-header"><span class="card-title">Reservas activas</span></div>
        <?php if ($reservasActivas): ?>
          <?php foreach ($reservasActivas as $r): ?>
            <div style="padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
              <div style="font-weight:600"><?= htmlspecialchars($r['cliente_nombre']) ?></div>
              <div style="color:#64748b"><?= htmlspecialchars($r['marca']) ?> <?= htmlspecialchars($r['modelo']) ?> · hasta <?= htmlspecialchars($r['fecha_fin']) ?></div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty">Sin reservas activas</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Carrusel (sin cambios) -->
    <section class="flota">
      <h2>Conoce nuestra flota</h2>
      <p>Las mejores opciones para que reserves y aproveches</p>
      <div class="contenedor-carrusel">
        <button class="flecha izquierda" onclick="moverIzquierda()">&#10094;</button>
        <div class="carrusel" id="carrusel">
          <div class="carro"><img src="assets/resources/PICZ.png" alt=""><h3>Grupo CX - Económico</h3><p>Vehículo similar a Kia Picanto</p></div>
          <div class="carro"><img src="assets/resources/SOLU.png" alt=""><h3>Grupo F - Intermedio</h3><p>Vehículo similar a Renault Logan</p></div>
          <div class="carro"><img src="assets/resources/SOTO.png" alt=""><h3>Grupo FL - Mecánico</h3><p>Vehículo similar a Kia Soluto 1.4, Renault Logan 1.6, Onix Turbo 1.0</p></div>
          <div class="carro"><img src="assets/resources/LOIX.png" alt=""><h3>Grupo FU - Automático Sin Pico Y Placa</h3><p>Vehículo similar a Onix Turbo MT 1.0, Hyundai Accent Advance 1.6, Suzuki Baleno 1.4</p></div>
          <div class="carro"><img src="assets/resources/LONI.png" alt=""><h3>Grupo FX - Intermedio Automático</h3><p>Vehículo similar a Onix Turbo AT 1.0, Kia Soluto Emotion AT, Suzuki Baleno 1.4</p></div>
          <div class="carro"><img src="assets/resources/LTRA.png" alt=""><h3>Grupo GC - Suv Compacto Automático</h3><p>Vehículo similar a Chevrolet Tracker 1.2 AT, Nissan Kicks Play, Hyundai Kona 2.0</p></div>
          <div class="carro"><img src="assets/resources/KONT.png" alt=""><h3>Grupo GL - Suv At Sin Pico Y Placa</h3><p>Vehículo similar a Hyundai Kona 2.0 AT, Tracker Turbo 1.2, Suzuki Vitara 1.6</p></div>
          <div class="carro"><img src="assets/resources/DUST.png" alt=""><h3>Grupo G4 - Suv Mecánica 4x4</h3><p>Vehículo similar a Renault Duster 1.3</p></div>
          <div class="carro"><img src="assets/resources/SFHY.png" alt=""><h3>Grupo GY - Suv Híbrido</h3><p>Vehículo similar a Hyundai Santa Fé 1.6 AT</p></div>
          <div class="carro"><img src="assets/resources/QASH.png" alt=""><h3>Grupo LE - Suv Especial</h3><p>Vehículo similar a Nissan Qashqai 2.0, Kia Sportage 2.0 AT, Citroën C5 Aircross 1.6</p></div>
          <div class="carro"><img src="assets/resources/ARKA.png" alt=""><h3>Grupo LU - Suv Híbrida Libre De Pico Y Placa</h3><p>Vehículo similar a Renault Arkana 1.3 AT, Grand Vitara 1.4</p></div>
          <div class="carro"><img src="assets/resources/AMOK.png" alt=""><h3>Grupo P - 4x4 Estándar Mecánico/Automático</h3><p>Vehículo similar a Volkswagen Amarok 2.0, Jac 2.0, Renault Alaskan 2.5</p></div>
          <div class="carro"><img src="assets/resources/PICX.png" alt=""><h3>Grupo C - Económico Con Aire Mecánico</h3><p>Vehículo similar a Kia Picanto 1.0, Fiat Mobi 1.0, Renault Kwid 1.0</p></div>
        </div>
        <button class="flecha derecha" onclick="moverDerecha()">&#10095;</button>
      </div>
    </section>
  </div>

  <!-- ==================== VEHÍCULOS ==================== -->
  <div id="section-vehiculos" class="section">
    <div class="card">
      <div class="card-header">
        <span class="card-title">Flota de vehículos</span>
        <button class="btn btn-primary btn-sm" onclick="openModalVehiculo()">+ Agregar vehículo</button>
      </div>
      <!-- Filtros: PHP vía GET -->
      <form method="GET" action="" class="search-bar" id="form-filtro-vehiculos">
        <input type="hidden" name="seccion" value="vehiculos">
        <input type="text" name="q_v" id="search-vehiculos"
               placeholder="Buscar por marca o modelo..."
               value="<?= htmlspecialchars($_GET['q_v'] ?? '') ?>"
               oninput="this.form.submit()">
        <select name="estado_v" id="filter-estado-v"
                onchange="this.form.submit()" style="width:auto;min-width:150px">
          <option value="">Todos los estados</option>
          <option value="disponible"    <?= ($_GET['estado_v'] ?? '') === 'disponible'    ? 'selected' : '' ?>>Disponible</option>
          <option value="alquilado"     <?= ($_GET['estado_v'] ?? '') === 'alquilado'     ? 'selected' : '' ?>>Alquilado</option>
          <option value="mantenimiento" <?= ($_GET['estado_v'] ?? '') === 'mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
        </select>
      </form>
      <table>
        <thead>
          <tr><th>Vehículo</th><th>Año</th><th>Categoría</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <?php if ($vehiculos): ?>
            <?php foreach ($vehiculos as $v): ?>
              <tr>
                <td><strong><?= htmlspecialchars($v['marca']) ?></strong> <?= htmlspecialchars($v['modelo']) ?></td>
                <td><?= (int)$v['anio'] ?></td>
                <td><?= htmlspecialchars($v['categoria']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($v['estado']) ?>"><?= htmlspecialchars($v['estado']) ?></span></td>
                <td>
                  <div class="td-actions">
                    <button class="btn btn-sm"
                      onclick="editVehiculo(<?= (int)$v['id'] ?>, '<?= htmlspecialchars(addslashes($v['marca'])) ?>', '<?= htmlspecialchars(addslashes($v['modelo'])) ?>', <?= (int)$v['anio'] ?>, '<?= htmlspecialchars($v['categoria']) ?>', '<?= htmlspecialchars($v['estado']) ?>')">
                      Editar
                    </button>
                    <?php if ($v['estado'] === 'alquilado'): ?>
                      <form method="POST" action="actions/vehiculo_devolver.php" style="display:inline">
                        <input type="hidden" name="id" value="<?= (int)$v['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Devolver</button>
                      </form>
                    <?php endif; ?>
                    <form method="POST" action="actions/vehiculo_eliminar.php" style="display:inline"
                          onsubmit="return confirm('¿Eliminar este vehículo?')">
                      <input type="hidden" name="id" value="<?= (int)$v['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5"><div class="empty">Sin vehículos registrados</div></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== CLIENTES ==================== -->
  <div id="section-clientes" class="section">
    <div class="card">
      <div class="card-header">
        <span class="card-title">Clientes registrados</span>
        <button class="btn btn-primary btn-sm" onclick="openModalCliente()">+ Agregar cliente</button>
      </div>
      <form method="GET" action="" class="search-bar">
        <input type="hidden" name="seccion" value="clientes">
        <input type="text" name="q_c" id="search-clientes"
               placeholder="Buscar por nombre o correo..."
               value="<?= htmlspecialchars($_GET['q_c'] ?? '') ?>"
               oninput="this.form.submit()">
      </form>
      <table>
        <thead>
          <tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Licencia</th><th>Reservas</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <?php if ($clientes): ?>
            <?php foreach ($clientes as $c): ?>
              <tr>
                <td><strong><?= htmlspecialchars($c['nombre']) ?></strong></td>
                <td><?= htmlspecialchars($c['telefono']) ?></td>
                <td><?= htmlspecialchars($c['correo']) ?></td>
                <td><code style="font-size:11px;background:#f1f5f9;padding:2px 6px;border-radius:4px"><?= htmlspecialchars($c['numero_licencia']) ?></code></td>
                <td><span class="badge badge-activa"><?= (int)$c['total_reservas'] ?></span></td>
                <td>
                  <div class="td-actions">
                    <button class="btn btn-sm"
                      onclick="editCliente(<?= (int)$c['id'] ?>, '<?= htmlspecialchars(addslashes($c['nombre'])) ?>', '<?= htmlspecialchars(addslashes($c['telefono'])) ?>', '<?= htmlspecialchars(addslashes($c['correo'])) ?>', '<?= htmlspecialchars(addslashes($c['numero_licencia'])) ?>')">
                      Editar
                    </button>
                    <form method="POST" action="actions/cliente_eliminar.php" style="display:inline"
                          onsubmit="return confirm('¿Eliminar este cliente?')">
                      <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6"><div class="empty">Sin clientes registrados</div></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== RESERVAS ==================== -->
  <div id="section-reservas" class="section">
    <div class="card">
      <div class="card-header">
        <span class="card-title">Gestión de reservas</span>
        <button class="btn btn-primary btn-sm" onclick="openModalReserva()">+ Nueva reserva</button>
      </div>
      <form method="GET" action="" class="search-bar">
        <input type="hidden" name="seccion" value="reservas">
        <select name="estado_r" id="filter-estado-r"
                onchange="this.form.submit()" style="width:auto;min-width:160px">
          <option value="">Todos los estados</option>
          <option value="activa"     <?= ($_GET['estado_r'] ?? '') === 'activa'     ? 'selected' : '' ?>>Activa</option>
          <option value="completada" <?= ($_GET['estado_r'] ?? '') === 'completada' ? 'selected' : '' ?>>Completada</option>
          <option value="cancelada"  <?= ($_GET['estado_r'] ?? '') === 'cancelada'  ? 'selected' : '' ?>>Cancelada</option>
        </select>
      </form>
      <table>
        <thead>
          <tr><th>Cliente</th><th>Vehículo</th><th>Inicio</th><th>Fin</th><th>Días</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <?php if ($reservas): ?>
            <?php foreach ($reservas as $r): ?>
              <?php
                // Cálculo de días en PHP (antes era JS)
                $d1   = new DateTime($r['fecha_inicio']);
                $d2   = new DateTime($r['fecha_fin']);
                $dias = max(1, (int)$d1->diff($d2)->days);
              ?>
              <tr>
                <td><strong><?= htmlspecialchars($r['cliente_nombre']) ?></strong></td>
                <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo']) ?></td>
                <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
                <td><?= $dias ?> día<?= $dias > 1 ? 's' : '' ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($r['estado']) ?>"><?= htmlspecialchars($r['estado']) ?></span></td>
                <td>
                  <div class="td-actions">
                    <?php if ($r['estado'] === 'activa'): ?>
                      <form method="POST" action="actions/reserva_completar.php" style="display:inline">
                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Completar</button>
                      </form>
                      <form method="POST" action="actions/reserva_cancelar.php" style="display:inline"
                            onsubmit="return confirm('¿Cancelar esta reserva?')">
                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7"><div class="empty">Sin reservas registradas</div></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== HISTORIAL ==================== -->
  <div id="section-historial" class="section">
    <div class="grid-2">
      <!-- Historial por vehículo -->
      <div class="card">
        <div class="card-header"><span class="card-title">Historial por vehículo</span></div>
        <form method="GET" action="">
          <input type="hidden" name="seccion" value="historial">
          <select name="hv" onchange="this.form.submit()" style="margin-bottom:14px;width:100%">
            <option value="">Seleccionar vehículo...</option>
            <?php foreach ($todosVehiculos as $v): ?>
              <option value="<?= (int)$v['id'] ?>" <?= ((int)($_GET['hv'] ?? 0) === (int)$v['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' (' . $v['anio'] . ')') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
        <div id="hist-v-list">
          <?php if ($histVehiculo): ?>
            <?php foreach ($histVehiculo as $r): ?>
              <?php $color = $r['estado'] === 'activa' ? 'blue' : ($r['estado'] === 'completada' ? 'green' : 'red'); ?>
              <div class="hist-row">
                <div class="hist-dot hist-dot-<?= $color ?>"></div>
                <div>
                  <div class="hist-text"><?= htmlspecialchars($r['cliente_nombre']) ?> — <?= htmlspecialchars($r['fecha_inicio']) ?> al <?= htmlspecialchars($r['fecha_fin']) ?></div>
                  <div class="hist-time"><span class="badge badge-<?= htmlspecialchars($r['estado']) ?>"><?= htmlspecialchars($r['estado']) ?></span></div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php elseif (!empty($_GET['hv'])): ?>
            <div class="empty">Sin historial para este vehículo</div>
          <?php else: ?>
            <div class="empty">Selecciona un vehículo</div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Historial por cliente -->
      <div class="card">
        <div class="card-header"><span class="card-title">Historial por cliente</span></div>
        <form method="GET" action="">
          <input type="hidden" name="seccion" value="historial">
          <select name="hc" onchange="this.form.submit()" style="margin-bottom:14px;width:100%">
            <option value="">Seleccionar cliente...</option>
            <?php foreach ($todosClientes as $c): ?>
              <option value="<?= (int)$c['id'] ?>" <?= ((int)($_GET['hc'] ?? 0) === (int)$c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
        <div id="hist-c-list">
          <?php if ($histCliente): ?>
            <?php foreach ($histCliente as $r): ?>
              <?php $color = $r['estado'] === 'activa' ? 'blue' : ($r['estado'] === 'completada' ? 'green' : 'red'); ?>
              <div class="hist-row">
                <div class="hist-dot hist-dot-<?= $color ?>"></div>
                <div>
                  <div class="hist-text"><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo']) ?> — <?= htmlspecialchars($r['fecha_inicio']) ?> al <?= htmlspecialchars($r['fecha_fin']) ?></div>
                  <div class="hist-time"><span class="badge badge-<?= htmlspecialchars($r['estado']) ?>"><?= htmlspecialchars($r['estado']) ?></span></div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php elseif (!empty($_GET['hc'])): ?>
            <div class="empty">Sin historial para este cliente</div>
          <?php else: ?>
            <div class="empty">Selecciona un cliente</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Actividad reciente -->
    <div class="card">
      <div class="card-header"><span class="card-title">Actividad reciente</span></div>
      <div id="actividad-reciente">
        <?php if ($actividadReciente): ?>
          <?php foreach ($actividadReciente as $r): ?>
            <?php
              $color = $r['estado'] === 'activa' ? 'blue' : ($r['estado'] === 'completada' ? 'green' : 'red');
              if ($r['estado'] === 'activa')
                $msg = "Reserva activa — {$r['cliente_nombre']} alquiló {$r['marca']} {$r['modelo']}";
              elseif ($r['estado'] === 'completada')
                $msg = "Reserva completada — {$r['cliente_nombre']} devolvió {$r['marca']} {$r['modelo']}";
              else
                $msg = "Reserva cancelada — {$r['cliente_nombre']} / {$r['marca']} {$r['modelo']}";
            ?>
            <div class="hist-row">
              <div class="hist-dot hist-dot-<?= $color ?>"></div>
              <div>
                <div class="hist-text"><?= htmlspecialchars($msg) ?></div>
                <div class="hist-time"><?= htmlspecialchars($r['fecha_inicio']) ?> → <?= htmlspecialchars($r['fecha_fin']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty">Sin actividad registrada</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Gráfica (Chart.js solo para renderizar; datos vienen de PHP) -->
    <div class="card">
      <div class="card-header"><div class="card-title">Estadísticas de reservas</div></div>
      <div style="height:300px">
        <canvas id="graficaReservas"></canvas>
      </div>
    </div>
  </div>

</div><!-- fin .main -->

<!-- ============================================================
     MODALES — Formularios POST hacia actions/
============================================================ -->

<!-- MODAL: Vehículo -->
<div class="modal-overlay" id="modal-vehiculo">
  <div class="modal">
    <h3 id="modal-v-title">Agregar vehículo</h3>
    <form method="POST" action="actions/vehiculo_guardar.php">
      <input type="hidden" name="id" id="v-edit-id">
      <div class="form-group">
        <label>Marca</label>
        <input name="marca" id="v-marca" placeholder="Toyota, Chevrolet, Ford...">
      </div>
      <div class="form-group">
        <label>Modelo</label>
        <input name="modelo" id="v-modelo" placeholder="Corolla, Spark, Explorer...">
      </div>
      <div class="form-group">
        <label>Año</label>
        <input name="anio" id="v-anio" type="number" placeholder="2023" min="1990" max="2030">
      </div>
      <div class="form-group">
        <label>Categoría</label>
        <select name="categoria" id="v-categoria">
          <option value="Sedán">Sedán</option>
          <option value="SUV">SUV</option>
          <option value="Camioneta">Camioneta</option>
          <option value="Deportivo">Deportivo</option>
          <option value="Compacto">Compacto</option>
          <option value="Van">Van</option>
        </select>
      </div>
      <div class="form-group">
        <label>Estado</label>
        <select name="estado" id="v-estado">
          <option value="disponible">Disponible</option>
          <option value="alquilado">Alquilado</option>
          <option value="mantenimiento">Mantenimiento</option>
        </select>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn" onclick="closeModal('modal-vehiculo')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Cliente -->
<div class="modal-overlay" id="modal-cliente">
  <div class="modal">
    <h3 id="modal-c-title">Agregar cliente</h3>
    <form method="POST" action="actions/cliente_guardar.php">
      <input type="hidden" name="id" id="c-edit-id">
      <div class="form-group">
        <label>Nombre completo</label>
        <input name="nombre" id="c-nombre" placeholder="Juan Pérez">
      </div>
      <div class="form-group">
        <label>Teléfono</label>
        <input name="telefono" id="c-telefono" placeholder="+57 300 000 0000">
      </div>
      <div class="form-group">
        <label>Correo electrónico</label>
        <input name="correo" id="c-correo" type="email" placeholder="juan@email.com">
      </div>
      <div class="form-group">
        <label>Número de licencia</label>
        <input name="numero_licencia" id="c-licencia" placeholder="L-123456">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn" onclick="closeModal('modal-cliente')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Reserva -->
<div class="modal-overlay" id="modal-reserva">
  <div class="modal">
    <h3>Nueva reserva</h3>
    <form method="POST" action="actions/reserva_crear.php">
      <div class="form-group">
        <label>Cliente</label>
        <select name="cliente_id" id="r-cliente">
          <?php foreach ($todosClientes as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Vehículo disponible</label>
        <select name="vehiculo_id" id="r-vehiculo">
          <?php foreach ($vehiculosDisponibles as $v): ?>
            <option value="<?= (int)$v['id'] ?>"><?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' (' . $v['anio'] . ')') ?></option>
          <?php endforeach; ?>
          <?php if (empty($vehiculosDisponibles)): ?>
            <option value="">Sin vehículos disponibles</option>
          <?php endif; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Fecha de inicio</label>
        <input name="fecha_inicio" id="r-inicio" type="date" value="<?= date('Y-m-d') ?>">
      </div>
      <div class="form-group">
        <label>Fecha de fin</label>
        <input name="fecha_fin" id="r-fin" type="date" value="<?= date('Y-m-d') ?>">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn" onclick="closeModal('modal-reserva')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Crear reserva</button>
      </div>
    </form>
  </div>
</div>

<!-- Datos de gráfica pasados desde PHP a Chart.js -->
<script>
  // Datos calculados en PHP, solo renderizado en JS
  const graficaData = {
    activas:     <?= (int)$graficaActivas ?>,
    completadas: <?= (int)$graficaCompletadas ?>,
    canceladas:  <?= (int)$graficaCanceladas ?>
  };
</script>
<script src="assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>