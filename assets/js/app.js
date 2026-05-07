/* ==========================================================
   app.js — Lógica principal de VehicleRent
   Toda la comunicación con el servidor se hace vía fetch()
   apuntando a los controladores PHP.
========================================================== */

/* ==========================================================
   1. UTILIDADES
========================================================== */

function dias(inicio, fin) {
  return Math.max(1, Math.round((new Date(fin) - new Date(inicio)) / 86400000));
}

function showAlert(mensaje, tipo = 'success') {
  const box = document.getElementById('alert-box');
  box.innerHTML = `<div class="alert alert-${tipo}">${mensaje}</div>`;
  box.style.display = 'block';
  setTimeout(() => { box.style.display = 'none'; }, 3000);
}

async function api(url, params = null) {
  const opts = params
    ? { method: 'POST', body: new URLSearchParams(params) }
    : { method: 'GET' };
  const res = await fetch(url, opts);
  return res.json();
}

/* ==========================================================
   2. NAVEGACIÓN
========================================================== */

function showSection(nombre) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('section-' + nombre).classList.add('active');
  const idx = ['dashboard', 'vehiculos', 'clientes', 'reservas', 'historial'].indexOf(nombre);
  if (idx >= 0) document.querySelectorAll('.nav-tab')[idx].classList.add('active');

  if (nombre === 'dashboard') renderDash();
  if (nombre === 'vehiculos') renderVehiculos();
  if (nombre === 'clientes')  renderClientes();
  if (nombre === 'reservas')  renderReservas();
  if (nombre === 'historial') renderHistorial();
}

/* ==========================================================
   3. DASHBOARD
========================================================== */

async function renderDash() {
  const [stats, vehiculos, reservas] = await Promise.all([
    api('controllers/ReservaController.php?action=stats'),
    api('controllers/VehiculoController.php?action=list'),
    api('controllers/ReservaController.php?action=list'),
  ]);

  document.getElementById('stats-row').innerHTML = `
    <div class="stat-card">
      <div class="stat-label">Total vehículos</div>
      <div class="stat-value">${stats.total_vehiculos}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Disponibles</div>
      <div class="stat-value" style="color:#16a34a">${stats.disponibles}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Alquilados</div>
      <div class="stat-value" style="color:#1d4ed8">${stats.alquilados}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Clientes registrados</div>
      <div class="stat-value">${stats.total_clientes}</div>
    </div>`;

  const disp = vehiculos.filter(v => v.estado === 'disponible');
  document.getElementById('dash-disponibles').innerHTML = disp.length
    ? disp.map(v => `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
          <span><strong>${v.marca}</strong> ${v.modelo} <span style="color:#94a3b8">(${v.anio})</span></span>
          <span class="badge badge-disponible">disponible</span>
        </div>`).join('')
    : '<div class="empty">Sin vehículos disponibles</div>';

  const activas = reservas.filter(r => r.estado === 'activa');
  document.getElementById('dash-activas').innerHTML = activas.length
    ? activas.map(r => `
        <div style="padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
          <div style="font-weight:600">${r.cliente_nombre}</div>
          <div style="color:#64748b">${r.marca} ${r.modelo} · hasta ${r.fecha_fin}</div>
        </div>`).join('')
    : '<div class="empty">Sin reservas activas</div>';
}

/* ==========================================================
   4. VEHÍCULOS
========================================================== */

let _vehiculos = [];

async function renderVehiculos() {
  _vehiculos = await api('controllers/VehiculoController.php?action=list');
  filtrarVehiculos();
}

function filtrarVehiculos() {
  const q   = (document.getElementById('search-vehiculos').value || '').toLowerCase();
  const est = document.getElementById('filter-estado-v').value;
  const lista = _vehiculos.filter(v => {
    const ok1 = !q || (v.marca + ' ' + v.modelo).toLowerCase().includes(q);
    const ok2 = !est || v.estado === est;
    return ok1 && ok2;
  });
  const tb = document.getElementById('tabla-vehiculos');
  tb.innerHTML = lista.length
    ? lista.map(v => `
        <tr>
          <td><strong>${v.marca}</strong> ${v.modelo}</td>
          <td>${v.anio}</td>
          <td>${v.categoria}</td>
          <td><span class="badge badge-${v.estado}">${v.estado}</span></td>
          <td>
            <div class="td-actions">
              <button class="btn btn-sm" onclick="editVehiculo(${v.id})">Editar</button>
              ${v.estado === 'alquilado'
                ? `<button class="btn btn-sm btn-success" onclick="devolverVehiculo(${v.id})">Devolver</button>`
                : ''}
              <button class="btn btn-sm btn-danger" onclick="eliminarVehiculo(${v.id})">Eliminar</button>
            </div>
          </td>
        </tr>`).join('')
    : `<tr><td colspan="5"><div class="empty">Sin vehículos registrados</div></td></tr>`;
}

function openModalVehiculo() {
  document.getElementById('modal-v-title').textContent = 'Agregar vehículo';
  document.getElementById('v-edit-id').value = '';
  document.getElementById('v-marca').value   = '';
  document.getElementById('v-modelo').value  = '';
  document.getElementById('v-anio').value    = '';
  document.getElementById('v-categoria').value = 'Sedán';
  document.getElementById('v-estado').value  = 'disponible';
  openModal('modal-vehiculo');
}

async function guardarVehiculo() {
  const editId = document.getElementById('v-edit-id').value;
  const params = {
    marca:     document.getElementById('v-marca').value.trim(),
    modelo:    document.getElementById('v-modelo').value.trim(),
    anio:      document.getElementById('v-anio').value,
    categoria: document.getElementById('v-categoria').value,
    estado:    document.getElementById('v-estado').value,
  };
  if (!params.marca || !params.modelo || !params.anio) {
    showAlert('Completa marca, modelo y año', 'error'); return;
  }
  let res;
  if (editId) {
    params.id = editId;
    res = await api('controllers/VehiculoController.php?action=update', params);
  } else {
    res = await api('controllers/VehiculoController.php?action=create', params);
  }
  if (res.success) {
    closeModal('modal-vehiculo');
    showAlert(`Vehículo ${params.marca} ${params.modelo} guardado`);
    renderVehiculos();
    if (document.getElementById('section-dashboard').classList.contains('active')) renderDash();
  } else {
    showAlert(res.message || 'Error al guardar', 'error');
  }
}

function editVehiculo(id) {
  const v = _vehiculos.find(x => x.id == id);
  if (!v) return;
  document.getElementById('modal-v-title').textContent = 'Editar vehículo';
  document.getElementById('v-edit-id').value   = v.id;
  document.getElementById('v-marca').value     = v.marca;
  document.getElementById('v-modelo').value    = v.modelo;
  document.getElementById('v-anio').value      = v.anio;
  document.getElementById('v-categoria').value = v.categoria;
  document.getElementById('v-estado').value    = v.estado;
  openModal('modal-vehiculo');
}

async function eliminarVehiculo(id) {
  const res = await api('controllers/VehiculoController.php?action=delete', { id });
  if (res.success) { showAlert('Vehículo eliminado'); renderVehiculos(); renderDash(); }
  else showAlert(res.message || 'Error al eliminar', 'error');
}

async function devolverVehiculo(id) {
  const res = await api('controllers/VehiculoController.php?action=devolver', { id });
  if (res.success) { showAlert('Vehículo marcado como disponible'); renderVehiculos(); renderDash(); }
  else showAlert('Error al devolver', 'error');
}

/* ==========================================================
   5. CLIENTES
========================================================== */

let _clientes = [];

async function renderClientes() {
  _clientes = await api('controllers/ClienteController.php?action=list');
  filtrarClientes();
}

function filtrarClientes() {
  const q = (document.getElementById('search-clientes').value || '').toLowerCase();
  const lista = _clientes.filter(c =>
    !q || (c.nombre + ' ' + c.correo).toLowerCase().includes(q)
  );
  const tb = document.getElementById('tabla-clientes');
  tb.innerHTML = lista.length
    ? lista.map(c => `
        <tr>
          <td><strong>${c.nombre}</strong></td>
          <td>${c.telefono}</td>
          <td>${c.correo}</td>
          <td><code style="font-size:11px;background:#f1f5f9;padding:2px 6px;border-radius:4px">${c.numero_licencia}</code></td>
          <td><span class="badge badge-activa">${c.total_reservas}</span></td>
          <td>
            <div class="td-actions">
              <button class="btn btn-sm" onclick="editCliente(${c.id})">Editar</button>
              <button class="btn btn-sm btn-danger" onclick="eliminarCliente(${c.id})">Eliminar</button>
            </div>
          </td>
        </tr>`).join('')
    : `<tr><td colspan="6"><div class="empty">Sin clientes registrados</div></td></tr>`;
}

function openModalCliente() {
  document.getElementById('modal-c-title').textContent = 'Agregar cliente';
  document.getElementById('c-edit-id').value   = '';
  document.getElementById('c-nombre').value    = '';
  document.getElementById('c-telefono').value  = '';
  document.getElementById('c-correo').value    = '';
  document.getElementById('c-licencia').value  = '';
  openModal('modal-cliente');
}

async function guardarCliente() {
  const editId = document.getElementById('c-edit-id').value;
  const params = {
    nombre:          document.getElementById('c-nombre').value.trim(),
    telefono:        document.getElementById('c-telefono').value.trim(),
    correo:          document.getElementById('c-correo').value.trim(),
    numero_licencia: document.getElementById('c-licencia').value.trim(),
  };
  if (!params.nombre || !params.numero_licencia) {
    showAlert('Nombre y licencia son obligatorios', 'error'); return;
  }
  let res;
  if (editId) {
    params.id = editId;
    res = await api('controllers/ClienteController.php?action=update', params);
  } else {
    res = await api('controllers/ClienteController.php?action=create', params);
  }
  if (res.success) {
    closeModal('modal-cliente');
    showAlert(`Cliente ${params.nombre} guardado`);
    renderClientes();
    renderDash();
  } else {
    showAlert(res.message || 'Error al guardar', 'error');
  }
}

function editCliente(id) {
  const c = _clientes.find(x => x.id == id);
  if (!c) return;
  document.getElementById('modal-c-title').textContent = 'Editar cliente';
  document.getElementById('c-edit-id').value  = c.id;
  document.getElementById('c-nombre').value   = c.nombre;
  document.getElementById('c-telefono').value = c.telefono;
  document.getElementById('c-correo').value   = c.correo;
  document.getElementById('c-licencia').value = c.numero_licencia;
  openModal('modal-cliente');
}

async function eliminarCliente(id) {
  const res = await api('controllers/ClienteController.php?action=delete', { id });
  if (res.success) { showAlert('Cliente eliminado'); renderClientes(); renderDash(); }
  else showAlert(res.message || 'Error al eliminar', 'error');
}

/* ==========================================================
   6. RESERVAS
========================================================== */

let _reservas = [];

async function renderReservas() {
  _reservas = await api('controllers/ReservaController.php?action=list');
  filtrarReservas();
}

function filtrarReservas() {
  const est = document.getElementById('filter-estado-r').value;
  const lista = _reservas.filter(r => !est || r.estado === est);
  const tb = document.getElementById('tabla-reservas');
  tb.innerHTML = lista.length
    ? lista.map(r => {
        const d = dias(r.fecha_inicio, r.fecha_fin);
        return `
          <tr>
            <td><strong>${r.cliente_nombre}</strong></td>
            <td>${r.marca} ${r.modelo}</td>
            <td>${r.fecha_inicio}</td>
            <td>${r.fecha_fin}</td>
            <td>${d} día${d > 1 ? 's' : ''}</td>
            <td><span class="badge badge-${r.estado}">${r.estado}</span></td>
            <td>
              <div class="td-actions">
                ${r.estado === 'activa' ? `
                  <button class="btn btn-sm btn-success" onclick="completarReserva(${r.id})">Completar</button>
                  <button class="btn btn-sm btn-danger" onclick="cancelarReserva(${r.id})">Cancelar</button>
                ` : ''}
              </div>
            </td>
          </tr>`;
      }).join('')
    : `<tr><td colspan="7"><div class="empty">Sin reservas registradas</div></td></tr>`;
}

async function openModalReserva() {
  const [clientes, vehiculos] = await Promise.all([
    api('controllers/ClienteController.php?action=list'),
    api('controllers/VehiculoController.php?action=disponibles'),
  ]);
  document.getElementById('r-cliente').innerHTML =
    clientes.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
  document.getElementById('r-vehiculo').innerHTML = vehiculos.length
    ? vehiculos.map(v => `<option value="${v.id}">${v.marca} ${v.modelo} (${v.anio})</option>`).join('')
    : '<option value="">Sin vehículos disponibles</option>';
  const hoy = new Date().toISOString().split('T')[0];
  document.getElementById('r-inicio').value = hoy;
  document.getElementById('r-fin').value    = hoy;
  openModal('modal-reserva');
}

async function guardarReserva() {
  const params = {
    cliente_id:   document.getElementById('r-cliente').value,
    vehiculo_id:  document.getElementById('r-vehiculo').value,
    fecha_inicio: document.getElementById('r-inicio').value,
    fecha_fin:    document.getElementById('r-fin').value,
  };
  if (!params.cliente_id || !params.vehiculo_id || !params.fecha_inicio || !params.fecha_fin) {
    showAlert('Completa todos los campos', 'error'); return;
  }
  const res = await api('controllers/ReservaController.php?action=create', params);
  if (res.success) {
    closeModal('modal-reserva');
    showAlert('Reserva creada exitosamente');
    renderReservas();
    renderDash();
  } else {
    showAlert(res.message || 'Error al crear la reserva', 'error');
  }
}

async function completarReserva(id) {
  const res = await api('controllers/ReservaController.php?action=completar', { id });
  if (res.success) { showAlert('Reserva completada — vehículo disponible'); renderReservas(); renderDash(); }
  else showAlert('Error', 'error');
}

async function cancelarReserva(id) {
  const res = await api('controllers/ReservaController.php?action=cancelar', { id });
  if (res.success) { showAlert('Reserva cancelada'); renderReservas(); renderDash(); }
  else showAlert('Error', 'error');
}

/* ==========================================================
   7. HISTORIAL
========================================================== */

async function renderHistorial() {
  const [vehiculos, clientes, reservas] = await Promise.all([
    api('controllers/VehiculoController.php?action=list'),
    api('controllers/ClienteController.php?action=list'),
    api('controllers/ReservaController.php?action=list'),
  ]);

  document.getElementById('hist-vehiculo').innerHTML =
    '<option value="">Seleccionar vehículo...</option>' +
    vehiculos.map(v => `<option value="${v.id}">${v.marca} ${v.modelo} (${v.anio})</option>`).join('');

  document.getElementById('hist-cliente').innerHTML =
    '<option value="">Seleccionar cliente...</option>' +
    clientes.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');

  // Actividad reciente: últimas 10 reservas
  document.getElementById('actividad-reciente').innerHTML = reservas.slice(0, 10).map(r => {
    const tipo = r.estado === 'activa' ? 'blue' : r.estado === 'completada' ? 'green' : 'red';
    const msg  = r.estado === 'activa'
      ? `Reserva activa — ${r.cliente_nombre} alquiló ${r.marca} ${r.modelo}`
      : r.estado === 'completada'
        ? `Reserva completada — ${r.cliente_nombre} devolvió ${r.marca} ${r.modelo}`
        : `Reserva cancelada — ${r.cliente_nombre} / ${r.marca} ${r.modelo}`;
    return `
      <div class="hist-row">
        <div class="hist-dot hist-dot-${tipo}"></div>
        <div>
          <div class="hist-text">${msg}</div>
          <div class="hist-time">${r.fecha_inicio} → ${r.fecha_fin}</div>
        </div>
      </div>`;
  }).join('') || '<div class="empty">Sin actividad registrada</div>';

  let chartReservas = null;

function renderGraficaReservas(reservas) {

  const activas = reservas.filter(r => r.estado === 'activa').length;
  const completadas = reservas.filter(r => r.estado === 'completada').length;
  const canceladas = reservas.filter(r => r.estado === 'cancelada').length;

  const ctx = document.getElementById('graficaReservas');

  // evitar duplicar gráfica
  if (chartReservas) {
    chartReservas.destroy();
  }

  chartReservas = new Chart(ctx, {
    type: 'doughnut',

    data: {
      labels: ['Activas', 'Completadas', 'Canceladas'],

      datasets: [{
        data: [activas, completadas, canceladas],

        backgroundColor: [
          '#1d4ed8',
          '#16a34a',
          '#dc2626'
        ],

        borderWidth: 0
      }]
    },

    options: {
      responsive: true,

      maintainAspectRatio: false,

      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
}
  renderHistVehiculo();
  renderHistCliente();
  renderGraficaReservas(reservas);
}

async function renderHistVehiculo() {
  const id = document.getElementById('hist-vehiculo').value;
  const box = document.getElementById('hist-v-list');
  if (!id) { box.innerHTML = '<div class="empty">Selecciona un vehículo</div>'; return; }
  const lista = await api(`controllers/ReservaController.php?action=byVehiculo&id=${id}`);
  box.innerHTML = lista.length
    ? lista.map(r => {
        const tipo = r.estado === 'activa' ? 'blue' : r.estado === 'completada' ? 'green' : 'red';
        return `
          <div class="hist-row">
            <div class="hist-dot hist-dot-${tipo}"></div>
            <div>
              <div class="hist-text">${r.cliente_nombre} — ${r.fecha_inicio} al ${r.fecha_fin}</div>
              <div class="hist-time"><span class="badge badge-${r.estado}">${r.estado}</span></div>
            </div>
          </div>`;
      }).join('')
    : '<div class="empty">Sin historial para este vehículo</div>';
}

async function renderHistCliente() {
  const id = document.getElementById('hist-cliente').value;
  const box = document.getElementById('hist-c-list');
  if (!id) { box.innerHTML = '<div class="empty">Selecciona un cliente</div>'; return; }
  const lista = await api(`controllers/ReservaController.php?action=byCliente&id=${id}`);
  box.innerHTML = lista.length
    ? lista.map(r => {
        const tipo = r.estado === 'activa' ? 'blue' : r.estado === 'completada' ? 'green' : 'red';
        return `
          <div class="hist-row">
            <div class="hist-dot hist-dot-${tipo}"></div>
            <div>
              <div class="hist-text">${r.marca} ${r.modelo} — ${r.fecha_inicio} al ${r.fecha_fin}</div>
              <div class="hist-time"><span class="badge badge-${r.estado}">${r.estado}</span></div>
            </div>
          </div>`;
      }).join('')
    : '<div class="empty">Sin historial para este cliente</div>';
}

/* ==========================================================
   8. MODALES
========================================================== */

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });
});

/* ==========================================================
   9. INICIALIZACIÓN
========================================================== */
document.addEventListener('DOMContentLoaded', () => {
  renderDash();
});

/* ==========================================================
   crrusel.js — Lógica para el carrusel de vehículos destacados
========================================================== */

document.addEventListener('DOMContentLoaded', () => {

    const carrusel = document.querySelector('.carrusel');
    const carros = document.querySelectorAll('.carro');

    let indice = 0;

    function actualizarCarrusel(){

        carros.forEach(carro =>{
            carro.classList.remove('activo');
        });

        carros[indice].classList.add('activo');

        const desplazamiento = indice * 340;

        carrusel.style.transform =
            `translateX(-${desplazamiento}px)`;
    }

    function moverDerecha(){

        indice++;

        if(indice >= carros.length){
            indice = 0;
        }

        actualizarCarrusel();
    }

    function moverIzquierda(){

        indice--;

        if(indice < 0){
            indice = carros.length - 1;
        }

        actualizarCarrusel();
    }

    // Hacer funciones globales
    window.moverDerecha = moverDerecha;
    window.moverIzquierda = moverIzquierda;

    actualizarCarrusel();

});

actualizarCarrusel();
