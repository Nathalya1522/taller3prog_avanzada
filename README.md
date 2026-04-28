# VehicleRent — Gestor de Alquiler de Vehículos
## Estructura MVC

```
alquiler_vehiculos/
├── index.php                        ← Punto de entrada
├── config.php                       ← Conexión a la base de datos
│
├── models/
│   ├── VehiculoModel.php            ← Lógica de datos: vehículos
│   ├── ClienteModel.php             ← Lógica de datos: clientes
│   └── ReservaModel.php             ← Lógica de datos: reservas
│
├── views/
│   └── index.php                    ← Vista principal (HTML)
│
├── controllers/
│   ├── VehiculoController.php       ← API REST: vehículos
│   ├── ClienteController.php        ← API REST: clientes
│   └── ReservaController.php        ← API REST: reservas
│
└── assets/
    ├── css/
    │   └── style.css                ← Estilos
    └── js/
        └── app.js                   ← JavaScript (AJAX + lógica UI)
```

## Instalación

1. Copia la carpeta `alquiler_vehiculos` a `C:\xampp\htdocs\`
2. Abre phpMyAdmin en http://localhost/phpmyadmin
3. Importa o ejecuta el archivo `alquiler_vehiculos_db.sql`
4. Abre el navegador en: http://localhost/alquiler_vehiculos

## Base de datos
- Host: localhost
- Usuario: root
- Contraseña: (vacía)
- Base de datos: alquiler_vehiculos_db
