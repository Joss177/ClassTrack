<?php
$controller = $this->request->getParam('controller');
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <?= $this->Html->link(
                'Docentes',
                ['prefix' => 'admin', 'controller' => 'Docentes', 'action' => 'index'],
                ['class' => 'nav-link ' . ($controller == 'Docentes' ? 'active' : '')]
            ) ?>
        </li>

        <li class="nav-item">
            <?= $this->Html->link(
                'Materias',
                ['prefix' => 'admin', 'controller' => 'Materias', 'action' => 'index'],
                ['class' => 'nav-link ' . ($controller == 'Materias' ? 'active' : '')]
            ) ?>
        </li>

        <li class="nav-item">
            <?= $this->Html->link(
                'Grupos',
                ['prefix' => 'admin', 'controller' => 'Grupos', 'action' => 'index'],
                ['class' => 'nav-link ' . ($controller == 'Grupos' ? 'active' : '')]
            ) ?>
        </li>

        <li class="nav-item">
            <?= $this->Html->link(
                'Aulas',
                ['prefix' => 'admin', 'controller' => 'Aulas', 'action' => 'index'],
                ['class' => 'nav-link ' . ($controller == 'Aulas' ? 'active' : '')]
            ) ?>
        </li>
    </ul>

    <?= $this->Html->link(
        'Agregar',
        [
            'prefix' => 'admin',
            'controller' => $controller,
            'action' => 'add'
        ],
        ['class' => 'btn btn-primary']
    ) ?>

</div>
