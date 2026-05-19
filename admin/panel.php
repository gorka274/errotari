<?php
session_start();

// Guardián de seguridad: si no ha iniciado sesión, al login directo
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Errotari</title>
    <style>
        :root {
            --primary-color: #7fad39;
            --primary-hover: #6a9430;
            --dark-color: #1c1c1c;
            --bg-light: #f4f7f2;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: var(--bg-light);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- SIDEBAR (BARRA LATERAL DE NAVEGACIÓN) --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            z-index: 10;
            border-right: 1px solid #e2e8f0;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-header h3 {
            margin: 0;
            color: var(--dark-color);
            font-size: 20px;
            font-weight: 700;
        }

        .admin-badge {
            background-color: #e2e8f0;
            color: #4a5568;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 600;
            margin-left: auto;
        }

        /* Menú y Submenús */
        .menu-container {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
            /* Permite scroll vertical si hay muchas opciones */
            overflow-x: hidden;
        }

        .menu-title {
            padding: 0 20px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 10px;
            margin-top: 15px;
        }

        .menu-item {
            display: block;
            width: 100%;
            padding: 12px 20px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background-color: #f8fafc;
            color: var(--dark-color);
        }

        /* Contenedor de submenús */
        .submenu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }

        .submenu-list li a {
            display: block;
            padding: 10px 20px 10px 40px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .submenu-list li a:hover {
            color: var(--primary-color);
            padding-left: 45px;
            /* Efecto sutil de desplazamiento */
        }

        /* Botón de cerrar sesión al fondo */
        .sidebar-footer {
            margin-right: 20px;
            padding: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background-color: #fee2e2;
            color: #ef4444;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: background-color 0.2s;
        }

        .btn-logout:hover {
            background-color: #fca5a5;
        }

        /* --- CONTENIDO PRINCIPAL --- */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .top-bar {
            background-color: white;
            height: 70px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 40px;
            justify-content: space-between;
        }

        .top-bar h2 {
            margin: 0;
            font-size: 18px;
            color: #64748b;
            font-weight: 500;
        }

        .user-info {
            font-weight: 600;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .content-body {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            box-sizing: border-box;
        }

        /* Tarjeta contenedora dinámica */
        .work-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid #e2e8f0;
            min-height: 300px;
        }

        .work-card h1 {
            margin-top: 0;
            color: var(--dark-color);
            font-size: 28px;
            margin-bottom: 15px;
        }

        .work-card p {
            color: #64748b;
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>🍄 Errotari</h3>
            <span class="admin-badge">Admin</span>
        </div>

        <nav class="menu-container">
            <div class="menu-title">Revistas Anuales</div>
            <div class="menu-item" style="cursor: default; background: none;">📖 Revista</div>
            <ul class="submenu-list">
                <li><a href="#" onclick="cargarModulo('revista-crear')">➕ Crear nueva revista</a></li>
                <li><a href="#" onclick="cargarModulo('revista-modificar')">✏️ Modificar existente</a></li>
            </ul>

            <div class="menu-title">Gestión de Contenidos</div>
            <div class="menu-item" style="cursor: default; background: none;">📅 Programación</div>
            <ul class="submenu-list">
                <li><a href="#" onclick="cargarModulo('prog-blog')">📝 Blog</a></li>
                <li><a href="#" onclick="cargarModulo('prog-anual')">🗓️ Programa Anual (Modificar)</a></li>
                <li><a href="#" onclick="cargarModulo('prog-fiestas')">🎉 Programa Fiestas (Modificar)</a></li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">
                Cerrar Sesión
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <h2 id="ruta-actual">Panel > Inicio</h2>
            <div class="user-info">
                <span>Conectado como:</span>
                <strong
                    style="color: var(--primary-color);"><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
            </div>
        </div>

        <div class="content-body">
            <div class="work-card" id="contenedor-dinamico">
                <h1>Bienvenido al Panel de Gestión</h1>
                <p>Selecciona cualquiera de las opciones del menú de la izquierda para empezar a crear o modificar los
                    contenidos de la página web de Errotari.</p>
                <p>Los cambios que realices aquí impactarán directamente en la navegación de los usuarios.</p>
            </div>
        </div>
    </main>

    <script>
        // Esta función simula la carga de las diferentes pantallas para que veas cómo interactúan las opciones
        function cargarModulo(modulo) {
            const contenedor = document.getElementById('contenedor-dinamico');
            const ruta = document.getElementById('ruta-actual');

            if (modulo === 'revista-crear') {
                ruta.innerText = "Panel > Revista > Crear Nueva";
                contenedor.innerHTML = `
                    <h1>➕ Crear Nueva Revista</h1>
                    <p>Aquí se programará el formulario para rellenar los datos de la nueva revista de este año (Título, Año, Imagen de portada y los puntos del Sumario).</p>
                `;
            }
            else if (modulo === 'revista-modificar') {
                ruta.innerText = "Panel > Revista > Modificar Existente";
                contenedor.innerHTML = `
                    <h1>✏️ Modificar Revista Existente</h1>
                    <p>Aquí aparecerá un listado de todas las revistas actuales (Número 1, etc.) para poder editar sus contenidos o cambiar el orden del sumario.</p>
                `;
            }
            else if (modulo === 'prog-blog') {
                ruta.innerText = "Panel > Programación > Blog";
                contenedor.innerHTML = `
                    <h1>📝 Gestión de Blog</h1>
                    <p>Sección del blog. Por el momento, el contenido se mantendrá así según lo solicitado.</p>
                `;
            }
            else if (modulo === 'prog-anual') {
                ruta.innerText = "Panel > Programación > Programa Anual";
                contenedor.innerHTML = `
                    <h1>🗓️ Modificar Programa Anual</h1>
                    <p>Formulario para editar las fechas, actividades y excursiones programadas para el año vigente en la sección del socio.</p>
                `;
            }
            else if (modulo === 'prog-fiestas') {
                ruta.innerText = "Panel > Programación > Programa Fiestas";
                contenedor.innerHTML = `
                    <h1>🎉 Modificar Programa de Fiestas</h1>
                    <p>Formulario para actualizar las actividades, concursos (como el micológico o el fotográfico) referentes a las fiestas anuales de la asociación.</p>
                `;
            }
        }
    </script>
</body>

</html>