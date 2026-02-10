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

    <aside class="sidebar">
        <div class="brand">
            <div class="icon"></div>
            <span>ClassTrack</span>
        </div>

        <nav class="menu">

            <?= $this->Html->link(
                '<i class="fas fa-home"></i><span>Inicio</span>',
                ['prefix' => 'admin', 'controller' => 'Admin', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => $this->Menu->activeClass('Admin', 'index', 'admin')
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
                    'class' => $this->Menu->activeClass('Horarios', 'index', 'admin')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-table"></i><span>Google Sheets</span>',
                ['controller' => 'Sheets', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => $this->Menu->activeClass('Sheets')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-video"></i><span>Cámaras</span>',
                ['controller' => 'Camaras', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => $this->Menu->activeClass('Camaras')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-users-cog"></i><span>Gestión</span>',
                ['controller' => 'Gestion', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => $this->Menu->activeClass('Gestion')
                ]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-cog"></i><span>Configuración</span>',
                ['controller' => 'Configuracion', 'action' => 'index'],
                [
                    'escape' => false,
                    'class' => $this->Menu->activeClass('Configuracion')
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

    <main class="content">
        <?= $this->fetch('content') ?>
    </main>

</div>

<?= $this->fetch('script') ?>
</body>
</html>

<style>

    .item i {
    width: 18px;
    text-align: center;
    font-size: 15px;
}

/* Fuente global: Inter 14px */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 14px;
    font-weight: 400; /* Regular */
}


.layout {
    display: flex;
    min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    background: linear-gradient(180deg, #163b63, #1f4a78);
    color: #e9f1fb;
    display: flex;
    flex-direction: column;
}

/* BRAND */
.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px;
    font-weight: 600;
    font-size: 15px;
    border-bottom: 1px solid rgba(255,255,255,.1);
}

.brand .icon {
    width: 18px;
    height: 18px;
    border: 2px solid #fff;
    border-radius: 3px;
}

/* MENU */
.menu {
    flex: 1;
    padding-top: 10px;
}

.item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    color: #dbe7f5;
    text-decoration: none;
    font-size: 14px;
}

.item:hover {
    background: rgba(255,255,255,.07);
}

.item.active {
    background: #2f5f9f;
    border-left: 4px solid #ffffff;
    padding-left: 16px;
    font-weight: 600;
}

/* ICONOS (CSS PURO) */
.menu-icon {
    width: 14px;
    height: 14px;
    border-radius: 3px;
    background: #dbe7f5;
    opacity: .85;
}

.clock { border-radius: 50%; }
.table { border: 1px solid #163b63; }
.camera { clip-path: polygon(0 25%, 20% 25%, 30% 0, 70% 0, 80% 25%, 100% 25%, 100% 100%, 0 100%); }
.users { border-radius: 50% 50% 40% 40%; }
.settings { clip-path: polygon(50% 0, 65% 20%, 100% 35%, 65% 50%, 50% 100%, 35% 50%, 0 35%, 35% 20%); }

/* LOGOUT */
.logout {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-top: 1px solid rgba(255,255,255,.1);
    color: #ffdede;
    text-decoration: none;
}

.logout:hover {
    background: #a83232;
}

/* CONTENT */
.content {
    flex: 1;
    padding: 30px;
    background: #f6f8fb;
}

</style>
