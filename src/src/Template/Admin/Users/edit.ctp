<div class="users-container">

    <h2 class="title">Gestión de Usuarios</h2>

    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Grupo</th>
                <th>Creado</th>
                <th>Modificado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
        <tr>

            <!-- FORMULARIO PARA EDITAR -->
            <?= $this->Form->create(null, ['url' => ['action' => 'edit'], 'class' => 'inline-form']) ?>

            <td>
                <?= $user->id ?>
                <?= $this->Form->hidden('id', ['value' => $user->id]) ?>
            </td>

            <td>
                <span class="text nombre-<?= $user->id ?>">
                    <?= h($user->nombre_completo) ?>
                </span>

                <?= $this->Form->control('nombre_completo', [
                    'value' => $user->nombre_completo,
                    'label' => false,
                    'class' => 'input edit-'. $user->id,
                    'style' => 'display:none;'
                ]) ?>
            </td>

            <td>
                <span class="text correo-<?= $user->id ?>">
                    <?= h($user->correo) ?>
                </span>

                <?= $this->Form->control('correo', [
                    'value' => $user->correo,
                    'label' => false,
                    'class' => 'input edit-'. $user->id,
                    'style' => 'display:none;'
                ]) ?>
            </td>

            <td><?= $user->group_id ?></td>
            <td><?= $user->created ?></td>
            <td><?= $user->modified ?></td>

            <td class="actions">

                <!-- BOTÓN EDITAR -->
                <button type="button"
                    class="btn edit-btn"
                    onclick="enableEdit(<?= $user->id ?>)">
                    Editar
                </button>

                <!-- BOTÓN GUARDAR -->
                <?= $this->Form->button('Guardar', [
                    'class' => 'btn save-btn edit-'. $user->id,
                    'style' => 'display:none;'
                ]) ?>

                <?= $this->Form->end() ?>

                <!-- BOTÓN ELIMINAR (FORM SEPARADO) -->
                <?= $this->Form->postLink(
                    'Eliminar',
                    ['action' => 'delete', $user->id],
                    [
                        'confirm' => '¿Seguro que deseas eliminar este usuario?',
                        'class' => 'btn delete-btn'
                    ]
                ) ?>

            </td>

        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


</div>

<script>
function enableEdit(id) {

    document.querySelectorAll('.nombre-' + id + ', .correo-' + id)
        .forEach(el => el.style.display = 'none');

    document.querySelectorAll('.edit-' + id)
        .forEach(el => el.style.display = 'inline-block');

}
</script>

<style>

/* CONTENEDOR */
.users-container {
    background: #ffffff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* TITULO */
.title {
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 600;
}

/* TABLA */
.users-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.users-table th {
    background: #163b63;
    color: #fff;
    text-align: left;
    padding: 12px;
    font-weight: 500;
}

.users-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.users-table tr:hover {
    background: #f5f8fc;
}

/* FORM INLINE */
.inline-form {
    display: contents;
}

/* INPUTS INLINE */
.input {
    width: 100%;
    padding: 6px 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 13px;
    transition: 0.2s ease;
}

.input:focus {
    outline: none;
    border-color: #2f5f9f;
    box-shadow: 0 0 0 2px rgba(47,95,159,0.15);
}

/* ACCIONES */
.actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

/* BOTONES BASE */
.btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: 0.2s ease;
}

/* EDITAR */
.edit-btn {
    background: #2f5f9f;
    color: white;
}

.edit-btn:hover {
    background: #1f4a78;
}

/* GUARDAR */
.save-btn {
    background: #28a745;
    color: white;
}

.save-btn:hover {
    background: #1e7e34;
}

/* ELIMINAR */
.delete-btn {
    background: #d9534f;
    color: white;
    text-decoration: none;
}

.delete-btn:hover {
    background: #b52b27;
}

/* TEXTO NORMAL */
.text {
    display: inline-block;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .users-table {
        font-size: 12px;
    }

    .btn {
        padding: 4px 8px;
        font-size: 12px;
    }
}

</style>
