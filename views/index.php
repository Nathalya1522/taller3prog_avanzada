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

<div id="alert-box" style="display:none"></div>

<!-- ============================================================
     CONTENIDO PRINCIPAL
============================================================ -->
<div class="main">

  <!-- ==================== INICIO / DASHBOARD ==================== -->
  <div id="section-dashboard" class="section active">
    <div class="stats-row" id="stats-row"></div>
    <div class="grid-2">
      <div class="card">
        <div class="card-header"><span class="card-title">Vehículos disponibles ahora</span></div>
        <div id="dash-disponibles"></div>
      </div>
      <div class="card">
        <div class="card-header"><span class="card-title">Reservas activas</span></div>
        <div id="dash-activas"></div>
      </div>
    </div>

    <section class="flota">
    <h2>Conoce nuestra flota</h2>
    <p>Las mejores opciones para que reserves y aproveches</p>

    <div class="contenedor-carrusel">

        <!-- Flecha izquierda -->
        <button class="flecha izquierda" onclick="moverIzquierda()">
            &#10094;
        </button>

        <!-- Carrusel -->
        <div class="carrusel" id="carrusel">

            <div class="carro">
                <img src="assets/resources/PICZ.png" alt="">
                <h3>Grupo CX - Económico</h3>
                <p>Vehículo similar a Kia Picanto</p>
            </div>

            <div class="carro">
                <img src="assets/resources/SOLU.png" alt="">
                <h3>Grupo F - Intermedio</h3>
                <p>Vehículo similar a Renault Logan</p>
            </div>

            <div class="carro">
                <img src="assets/resources/SOTO.png" alt="">
                <h3>Grupo FL - Mecánico</h3>
                <p>Vehículo similar a Kia Soluto 1.4, Renault Logan 1.6, Onix Turbo 1.0</p>
            </div>
            <div class="carro">
                <img src="assets/resources/LOIX.png" alt="">
                <h3>Grupo FU - Automático Sin Pico Y Placa</h3>
                <p>Vehículo similar a Onix Turbo MT 1.0, Hyundai Accent Advance 1.6, Suzuki Baleno 1.4</p>
            </div>
            <div class="carro">
                <img src="assets/resources/LONI.png" alt="">
                <h3>Grupo FX - Intermedio Automático</h3>
                <p>Vehículo similar a Onix Turbo AT 1.0, Kia Soluto Emotion AT, Suzuki Baleno 1.4</p>
            </div>
            <div class="carro">
                <img src="assets/resources/LTRA.png" alt="">
                <h3>Grupo GC - Suv Compacto Automático</h3>
                <p>Vehículo similar a Chevrolet Tracker 1.2 AT, Nissan Kicks Play, Hyundai Kona 2.0</p>
            </div>
            <div class="carro">
                <img src="assets/resources/KONT.png" alt="">
                <h3>Grupo GL - Suv At Sin Pico Y Placa</h3>
                <p>Vehículo similar a Hyundai Kona 2.0 AT, Tracker Turbo 1.2, Suzuki Vitara 1.6</p>
            </div>
            <div class="carro">
                <img src="assets/resources/DUST.png" alt="">
                <h3>Grupo G4 - Suv Mecánica 4x4</h3>
                <p>Vehículo similar a Renault Duster 1.3</p>
            </div>
            <div class="carro">
                <img src="assets/resources/SFHY.png" alt="">
                <h3>Grupo GY - Suv Híbrido</h3>
                <p>Vehículo similar a Hyundai Santa Fé 1.6 AT</p>
            </div>
            <div class="carro">
                <img src="assets/resources/QASH.png" alt="">
                <h3>Grupo LE - Suv Especial</h3>
                <p>Vehículo similar a Nissan Qashqai 2.0, Kia Sportage 2.0 AT, Citroën C5 Aircross 1.6</p>
            </div>
            <div class="carro">
                <img src="assets/resources/ARKA.png" alt="">
                <h3>Grupo LU - Suv Híbrida Libre De Pico Y Pl</h3>
                <p>Vehículo similar a Renault Arkana 1.3 AT, Grand Vitara 1.4</p>
            </div>
            <div class="carro">
                <img src="assets/resources/AMOK.png" alt="">
                <h3>Grupo P - 4x4 Estándar Mecánico/automáti</h3>
                <p>Vehículo similar a Volkswagen Amarok 2.0, Jac 2.0, Renault Alaskan 2.5</p>
            </div>
            <div class="carro">
                <img src="assets/resources/PICX.png" alt="">
                <h3>Grupo C - Económico Con Aire Mecánico</h3>
                <p>Vehículo similar a Kia Picanto 1.0, Fiat Mobi 1.0, Renault Kwid 1.0</p>
            </div>
        </div>

        <!-- Flecha derecha -->
        <button class="flecha derecha" onclick="moverDerecha()">
            &#10095;
        </button>
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
      <div class="search-bar">
        <input type="text" id="search-vehiculos" placeholder="Buscar por marca o modelo..." oninput="filtrarVehiculos()">
        <select id="filter-estado-v" onchange="filtrarVehiculos()" style="width:auto;min-width:150px">
          <option value="">Todos los estados</option>
          <option value="disponible">Disponible</option>
          <option value="alquilado">Alquilado</option>
          <option value="mantenimiento">Mantenimiento</option>
        </select>
      </div>
      <table>
        <thead>
          <tr>
            <th>Vehículo</th><th>Año</th><th>Categoría</th><th>Estado</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-vehiculos"></tbody>
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
      <div class="search-bar">
        <input type="text" id="search-clientes" placeholder="Buscar por nombre o correo..." oninput="filtrarClientes()">
      </div>
      <table>
        <thead>
          <tr>
            <th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Licencia</th><th>Reservas</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-clientes"></tbody>
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
      <div class="search-bar">
        <select id="filter-estado-r" onchange="filtrarReservas()" style="width:auto;min-width:160px">
          <option value="">Todos los estados</option>
          <option value="activa">Activa</option>
          <option value="completada">Completada</option>
          <option value="cancelada">Cancelada</option>
        </select>
      </div>
      <table>
        <thead>
          <tr>
            <th>Cliente</th><th>Vehículo</th><th>Inicio</th><th>Fin</th><th>Días</th><th>Estado</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-reservas"></tbody>
      </table>
    </div>
  </div>

  <!-- ==================== HISTORIAL ==================== -->
  <div id="section-historial" class="section">
    <div class="grid-2">
      <div class="card">
        <div class="card-header"><span class="card-title">Historial por vehículo</span></div>
        <select id="hist-vehiculo" onchange="renderHistVehiculo()" style="margin-bottom:14px">
          <option value="">Seleccionar vehículo...</option>
        </select>
        <div id="hist-v-list"></div>
      </div>
      <div class="card">
        <div class="card-header"><span class="card-title">Historial por cliente</span></div>
        <select id="hist-cliente" onchange="renderHistCliente()" style="margin-bottom:14px">
          <option value="">Seleccionar cliente...</option>
        </select>
        <div id="hist-c-list"></div>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title">Actividad reciente</span></div>
      <div id="actividad-reciente"></div>
    </div>
    <div class="card">
  <div class="card-header">
    <div class="card-title">Estadísticas de reservas</div>
  </div>

  <div style="height:300px">
    <canvas id="graficaReservas"></canvas>
  </div>
</div>

  </div>

</div><!-- fin .main -->


<!-- ============================================================
     MODALES
============================================================ -->

<!-- MODAL: Vehículo -->
<div class="modal-overlay" id="modal-vehiculo">
  <div class="modal">
    <h3 id="modal-v-title">Agregar vehículo</h3>
    <input type="hidden" id="v-edit-id">
    <div class="form-group">
      <label>Marca</label>
      <input id="v-marca" placeholder="Toyota, Chevrolet, Ford...">
    </div>
    <div class="form-group">
      <label>Modelo</label>
      <input id="v-modelo" placeholder="Corolla, Spark, Explorer...">
    </div>
    <div class="form-group">
      <label>Año</label>
      <input id="v-anio" type="number" placeholder="2023" min="1990" max="2030">
    </div>
    <div class="form-group">
      <label>Categoría</label>
      <select id="v-categoria">
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
      <select id="v-estado">
        <option value="disponible">Disponible</option>
        <option value="alquilado">Alquilado</option>
        <option value="mantenimiento">Mantenimiento</option>
      </select>
    </div>
    <div class="modal-actions">
      <button class="btn" onclick="closeModal('modal-vehiculo')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarVehiculo()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL: Cliente -->
<div class="modal-overlay" id="modal-cliente">
  <div class="modal">
    <h3 id="modal-c-title">Agregar cliente</h3>
    <input type="hidden" id="c-edit-id">
    <div class="form-group">
      <label>Nombre completo</label>
      <input id="c-nombre" placeholder="Juan Pérez">
    </div>
    <div class="form-group">
      <label>Teléfono</label>
      <input id="c-telefono" placeholder="+57 300 000 0000">
    </div>
    <div class="form-group">
      <label>Correo electrónico</label>
      <input id="c-correo" type="email" placeholder="juan@email.com">
    </div>
    <div class="form-group">
      <label>Número de licencia</label>
      <input id="c-licencia" placeholder="L-123456">
    </div>
    <div class="modal-actions">
      <button class="btn" onclick="closeModal('modal-cliente')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarCliente()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL: Reserva -->
<div class="modal-overlay" id="modal-reserva">
  <div class="modal">
    <h3>Nueva reserva</h3>
    <div class="form-group">
      <label>Cliente</label>
      <select id="r-cliente"></select>
    </div>
    <div class="form-group">
      <label>Vehículo disponible</label>
      <select id="r-vehiculo"></select>
    </div>
    <div class="form-group">
      <label>Fecha de inicio</label>
      <input id="r-inicio" type="date">
    </div>
    <div class="form-group">
      <label>Fecha de fin</label>
      <input id="r-fin" type="date">
    </div>
    <div class="modal-actions">
      <button class="btn" onclick="closeModal('modal-reserva')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarReserva()">Crear reserva</button>
    </div>
  </div>
</div>

<script src="assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>