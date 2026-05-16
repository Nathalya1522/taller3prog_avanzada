/* ================= UTILIDADES ================= */

function showAlert(msg,tipo='success'){
  const box=document.getElementById('alert-box');
  box.innerHTML=`<div class="alert alert-${tipo}">${msg}</div>`;
  box.style.display='block';
  setTimeout(()=>box.style.display='none',3000);
}

async function api(url,data=null){
  const res=await fetch(url,{
    method:data?'POST':'GET',
    body:data?new URLSearchParams(data):null
  });
  return res.json();
}

/* ================= NAVEGACIÓN ================= */

function showSection(nombre){
  document.querySelectorAll('.section,.nav-tab')
    .forEach(x=>x.classList.remove('active'));

  document.getElementById(`section-${nombre}`)
    .classList.add('active');

  const tabs=['dashboard','vehiculos','clientes','reservas','historial'];
  let i=tabs.indexOf(nombre);

  if(i>=0)
    document.querySelectorAll('.nav-tab')[i]
      .classList.add('active');

  ({
    dashboard:renderDash,
    vehiculos:renderVehiculos,
    clientes:renderClientes,
    reservas:renderReservas,
    historial:renderHistorial
  })[nombre]?.();
}

/* ================= DASHBOARD ================= */

async function renderDash(){

 const [stats,vehiculos,reservas]=await Promise.all([
  api('controllers/ReservaController.php?action=stats'),
  api('controllers/VehiculoController.php?action=list'),
  api('controllers/ReservaController.php?action=list')
 ]);

 document.getElementById('stats-row').innerHTML=`
 <div class="stat-card"><div>Total vehículos</div><div>${stats.total_vehiculos}</div></div>
 <div class="stat-card"><div>Disponibles</div><div>${stats.disponibles}</div></div>
 <div class="stat-card"><div>Alquilados</div><div>${stats.alquilados}</div></div>
 <div class="stat-card"><div>Clientes registrados</div><div>${stats.total_clientes}</div></div>`;

 document.getElementById('dash-disponibles').innerHTML=
 vehiculos.filter(v=>v.estado=='disponible')
 .map(v=>`
 <div>
 <b>${v.marca}</b> ${v.modelo} (${v.anio})
 </div>`).join('')
 ||'<div class="empty">Sin disponibles</div>';

 document.getElementById('dash-activas').innerHTML=
 reservas.filter(r=>r.estado=='activa')
 .map(r=>`
 <div>
 <b>${r.cliente_nombre}</b><br>
 ${r.marca} ${r.modelo}
 </div>`).join('')
 ||'<div class="empty">Sin reservas</div>';
}

/* ================= VEHICULOS ================= */

let _vehiculos=[];

async function renderVehiculos(){
 _vehiculos=await api('controllers/VehiculoController.php?action=list');
 filtrarVehiculos();
}

function filtrarVehiculos(){

 const q=document.getElementById('search-vehiculos').value.toLowerCase();
 const est=document.getElementById('filter-estado-v').value;

 let lista=_vehiculos.filter(v=>
 (!q||`${v.marca} ${v.modelo}`.toLowerCase().includes(q))
 &&
 (!est||v.estado==est)
 );

 document.getElementById('tabla-vehiculos').innerHTML=
 lista.map(v=>`
<tr>
<td>${v.marca} ${v.modelo}</td>
<td>${v.anio}</td>
<td>${v.categoria}</td>
<td>${v.estado}</td>
<td>

<button onclick="editVehiculo(${v.id})">
Editar
</button>

${v.estado=="alquilado"
?`<button onclick="devolverVehiculo(${v.id})">Devolver</button>`
:''}

<button onclick="eliminarVehiculo(${v.id})">
Eliminar
</button>

</td>
</tr>`).join('');
}

function openModalVehiculo(){

 ['v-edit-id','v-marca','v-modelo','v-anio']
 .forEach(id=>document.getElementById(id).value='');

 document.getElementById('v-categoria').value='Sedán';
 document.getElementById('v-estado').value='disponible';

 openModal('modal-vehiculo');
}

async function guardarVehiculo(){

 let id=document.getElementById('v-edit-id').value;

 let p={
 marca:vMarca.value.trim(),
 modelo:vModelo.value.trim(),
 anio:vAnio.value,
 categoria:vCategoria.value,
 estado:vEstado.value
 };

 if(id)p.id=id;

 let res=await api(
 `controllers/VehiculoController.php?action=${id?'update':'create'}`,
 p
 );

 if(res.success){
 closeModal('modal-vehiculo');
 renderVehiculos();
 renderDash();
 showAlert("Vehículo guardado");
 }
}

/* ================= CLIENTES ================= */

let _clientes=[];

async function renderClientes(){
 _clientes=await api('controllers/ClienteController.php?action=list');
 filtrarClientes();
}

function filtrarClientes(){

 const q=document.getElementById('search-clientes').value.toLowerCase();

 document.getElementById('tabla-clientes').innerHTML=
 _clientes.filter(c=>
 !q||
 `${c.nombre} ${c.correo}`.toLowerCase().includes(q)
 )
 .map(c=>`
<tr>
<td>${c.nombre}</td>
<td>${c.telefono}</td>
<td>${c.correo}</td>
<td>${c.numero_licencia}</td>
<td>${c.total_reservas}</td>

<td>
<button onclick="editCliente(${c.id})">Editar</button>
<button onclick="eliminarCliente(${c.id})">Eliminar</button>
</td>

</tr>
`).join('');
}

/* ================= RESERVAS ================= */

let _reservas=[];

async function renderReservas(){
 _reservas=await api('controllers/ReservaController.php?action=list');
 filtrarReservas();
}

function filtrarReservas(){

 let est=document.getElementById('filter-estado-r').value;

 document.getElementById('tabla-reservas').innerHTML=
 _reservas.filter(r=>!est||r.estado==est)
 .map(r=>`
<tr>

<td>${r.cliente_nombre}</td>
<td>${r.marca} ${r.modelo}</td>
<td>${r.fecha_inicio}</td>
<td>${r.fecha_fin}</td>
<td>${r.total_dias}</td>
<td>${r.estado}</td>

<td>
${r.estado=='activa'
?`
<button onclick="completarReserva(${r.id})">
Completar
</button>

<button onclick="cancelarReserva(${r.id})">
Cancelar
</button>
`:''}
</td>

</tr>
`).join('');
}

/* ================= HISTORIAL ================= */

let chartReservas=null;

function renderGraficaReservas(reservas){

 const ctx=document.getElementById('graficaReservas');

 if(chartReservas) chartReservas.destroy();

 chartReservas=new Chart(ctx,{
 type:'doughnut',
 data:{
 labels:['Activas','Completadas','Canceladas'],
 datasets:[{
 data:[
 reservas.filter(x=>x.estado=='activa').length,
 reservas.filter(x=>x.estado=='completada').length,
 reservas.filter(x=>x.estado=='cancelada').length
 ]
 }]
 }
});
}

/* ================= MODALES ================= */

function openModal(id){
 document.getElementById(id).classList.add('open');
}

function closeModal(id){
 document.getElementById(id).classList.remove('open');
}

/* ================= INICIO ================= */

document.addEventListener(
'DOMContentLoaded',
()=>renderDash()
);


/* ================= CARRUSEL ================= */

document.addEventListener('DOMContentLoaded',()=>{

 const carrusel=document.querySelector('.carrusel');
 const carros=document.querySelectorAll('.carro');

 let i=0;

 function mover(n){

 i=(i+n+carros.length)%carros.length;

 carros.forEach(c=>c.classList.remove('activo'));

 carros[i].classList.add('activo');

 carrusel.style.transform=
 `translateX(-${i*340}px)`;

 }

 window.moverDerecha=()=>mover(1);
 window.moverIzquierda=()=>mover(-1);

 mover(0);

});
