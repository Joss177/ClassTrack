<!DOCTYPE html>
<html lang="es">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?: 'Admin' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->css('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap', ['rel' => 'stylesheet']) ?>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('all') ?>
    <?= $this->Html->css('menu') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar collapsed" id="sidebar">

        <div class="brand">

            <div class="hamburger" id="toggleMenu">
                <i class="fas fa-bars"></i>
            </div>

            <?= $this->Html->image('LOGOCLASSTRACK.png', [
                'alt' => 'ClassTrack',
                'class' => 'logo-img'
            ]) ?>

            <span class="brand-text">ClassTrack</span>

        </div>

        <nav class="menu">

            <?= $this->Html->link(
                '<i class="fas fa-home"></i><span>Inicio</span>',
                ['prefix' => 'admin', 'controller' => 'Admin', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => 'item ' . $this->Menu->activeClass('Admin', 'index', 'admin')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="far fa-clock"></i><span>Horarios</span>',
                [
                    'prefix' => 'admin',
                    'controller' => 'Horarios',
                    'action' => 'index'
                ],
                [
                    'escape' => false,
                    'class' => 'item ' . $this->Menu->activeClass('Horarios', 'index', 'admin')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-table"></i><span>Google Sheets</span>',
                ['controller' => 'Sheets', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => 'item ' . $this->Menu->activeClass('Sheets')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-video"></i><span>Cámaras</span>',
                ['controller' => 'Camaras', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => 'item ' . $this->Menu->activeClass('Camaras')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-users-cog"></i><span>Gestión</span>',
                ['controller' => 'Gestion', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => 'item ' . $this->Menu->activeClass('Gestion')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-users"></i><span>Usuarios</span>',
                ['controller' => 'Users', 'action' => 'edit', 1],
                [
                    'escape' => false,
                    'class' => 'item ' . $this->Menu->activeClass('Users')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-cog"></i><span>Configuración</span>',
                ['controller' => 'Configuracion', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => 'item ' . $this->Menu->activeClass('Configuracion')
                ]
            ) ?>

        </nav>

        <?= $this->Html->link(
            '<i class="fas fa-sign-out-alt"></i><span>Salir</span>',
            ['controller' => 'Users', 'action' => 'logout'],
            [
                'escape' => false,
                'class' => 'item logout'
            ]
        ) ?>

    </aside>

    <!-- CONTENIDO -->
    <main class="content">
        <?= $this->fetch('content') ?>
    </main>

</div>

<div class="overlay" id="overlay"></div>

<?= $this->fetch('script') ?>


</body>
</html>

<style>

/* ============================= */
/* RESET Y FUENTE GLOBAL */
/* ============================= */



* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}

body {
    min-height: 100vh;
    background: #f6f8fb;
}

/* ============================= */
/* LAYOUT */
/* ============================= */

.layout {
    display: flex;
}

/* ============================= */
/* SIDEBAR */
/* ============================= */

.sidebar {
    width: 220px;
    background: linear-gradient(180deg, #1E3A5F, #1f4a78);
    color: #e9f1fb;
    display: flex;
    flex-direction: column;
    transition: width .3s ease;
    overflow: hidden;
    position: sticky;
    top: 0;
    height: 100vh;
}

.sidebar.collapsed {
    width: 55px;
}

/* ============================= */
/* BRAND */
/* ============================= */

.brand {
    display: flex;
    align-items: center;
    gap: 12px;              /* igual que .item */
    padding: 14px 20px;     /* igual que .item */
    font-weight: 600;
    font-size: 15px;
    border-bottom: 1px solid rgba(255,255,255,.1);
}

.logo-img {
    width: 38px;
    height: 38px;
    object-fit: contain;
}

.brand-text {
    white-space: nowrap;
    transition: opacity .2s ease;
}

.hamburger {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    min-width: 18px;
    font-size: 15px;
    cursor: pointer;
    color: white;
}

/* ============================= */
/* MENU */
/* ============================= */

.menu {
    flex: 1;
    padding-top: 10px;
}

/* ITEMS Y LOGOUT COMPARTEN BASE */

.item,
.logout {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    text-decoration: none;
    white-space: nowrap;
    transition: background .2s ease;
}

.item {
    color: #dbe7f5;
}

.logout {
    color: #ffdede;
    border-top: 1px solid rgba(255,255,255,.1);
}

/* ICONOS (MISMO TAMAÑO SIEMPRE) */

.item i,
.logout i {
    width: 18px;
    min-width: 18px;
    text-align: center;
    font-size: 15px;
}

/* OCULTAR TEXTO EN MODO COLAPSADO SIN MOVER ICONOS */

.sidebar.collapsed .brand-text,
.sidebar.collapsed .item span,
.sidebar.collapsed .logout span {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

/* NO CAMBIAMOS padding NI justify → NO HAY SALTO */

/* HOVER */

.item:hover {
    background: rgba(255,255,255,.07);
}

.logout:hover {
    background: #a83232;
}

/* ACTIVO */

.item.active {
    background: rgba(255,255,255,.1);
    border-left: 4px solid #ffffff;
    font-weight: 600;
}

.sidebar.collapsed .item.active {
    border-left: none;
    position: relative;
}

.sidebar.collapsed .item.active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 8px;
    bottom: 8px;
    width: 4px;
    background: #ffffff;
    border-radius: 4px;
}

/* ============================= */
/* CONTENT */
/* ============================= */

.content {
    flex: 1;
    padding: 30px;
}

/* ============================= */
/* RESPONSIVE */
/* ============================= */

@media (max-width: 768px) {

    .sidebar {
        position: fixed;
        height: 100%;
        z-index: 1000;
        left: -220px;
    }

    .sidebar.active {
        left: 0;
    }

    .overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.3);
        opacity: 0;
        pointer-events: none;
        transition: opacity .3s ease;
    }

    .overlay.active {
        opacity: 1;
        pointer-events: all;
    }
}

</style>



<script>
    document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleMenu");
    const overlay = document.getElementById("overlay");

    // Restaurar estado guardado
    const saved = localStorage.getItem("sidebarCollapsed");

    if (saved === "false") {
        sidebar.classList.remove("collapsed");
    }

    toggleBtn.addEventListener("click", function () {

        if (window.innerWidth > 768) {

            sidebar.classList.toggle("collapsed");

            localStorage.setItem(
                "sidebarCollapsed",
                sidebar.classList.contains("collapsed")
            );

        } else {
            sidebar.classList.toggle("active");
            overlay.classList.toggle("active");
        }
    });

    overlay.addEventListener("click", function () {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    });

});
</script>
