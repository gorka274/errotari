<aside class="sidebar">
    <div class="sidebar-header">
        <h3>🍄 Errotari Admin</h3>
    </div>
    <nav class="menu-container">
        <div class="menu-title">Revistas Anuales</div>
        <div class="menu-item">📖 Revista</div>
        <ul class="submenu-list">
            <li><a href="?mod=revista-crear" class="<?php echo $modulo_actual === 'revista-crear' ? 'active' : ''; ?>">📄 Crear nueva revista</a></li>
            <li><a href="?mod=revista-modificar" class="<?php echo $modulo_actual === 'revista-modificar' ? 'active' : ''; ?>">✏️ Modificar existente</a></li>
        </ul>

        <div class="menu-title">Gestión de Contenidos</div>
        <div class="menu-item">📅 Programación</div>
        <ul class="submenu-list">
            <li><a href="?mod=prog-blog" class="<?php echo $modulo_actual === 'prog-blog' ? 'active' : ''; ?>">📝 Blog</a></li>
            <li><a href="?mod=prog-anual" class="<?php echo $modulo_actual === 'prog-anual' ? 'active' : ''; ?>">🗓️ Programa Anual (Modificar)</a></li>
            <li><a href="?mod=prog-fiestas" class="<?php echo $modulo_actual === 'prog-fiestas' ? 'active' : ''; ?>">🎉 Programa Fiestas (Modificar)</a></li>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout"> Cerrar Sesión</a>
    </div>
</aside>
