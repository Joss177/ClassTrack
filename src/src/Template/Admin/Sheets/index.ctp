<?= $this->Html->css('sheets', ['block' => true]) ?>
<div class="sheets-container">

    <h2 class="sheets-title">Google Sheets - Falta logica backend</h2>

    <div class="sheets-card">

        <!-- FILTROS -->
        <?= $this->Form->create(null, ['type' => 'get']) ?>

        <div class="sheets-filters">

            <select name="docente_id" class="sheets-select" onchange="this.form.submit()">
                <option value="">Todos los Docentes</option>
                <?php foreach ($docentesList as $id => $nombre): ?>
                    <option value="<?= $id ?>" <?= ($docenteId == $id) ? 'selected' : '' ?>>
                        <?= h($nombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="materia_id" class="sheets-select" onchange="this.form.submit()">
                <option value="">Todas las Materias</option>
                <?php foreach ($materiasList as $id => $nombre): ?>
                    <option value="<?= $id ?>" <?= ($materiaId == $id) ? 'selected' : '' ?>>
                        <?= h($nombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="grupo_id" class="sheets-select" onchange="this.form.submit()">
                <option value="">Todos los Grupos</option>
                <?php foreach ($gruposList as $id => $nombre): ?>
                    <option value="<?= $id ?>" <?= ($grupoId == $id) ? 'selected' : '' ?>>
                        <?= h($nombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>

        <?= $this->Form->end() ?>

        <!-- TARJETAS -->
        <?php if (!empty($porDocente)): ?>

            <?php foreach ($porDocente as $docente => $lista): ?>

                <div class="sheets-item">

                    <div class="sheets-info">
                        <span class="sheets-label">Docente</span>
                        <p class="sheets-text"><?= h($docente) ?></p>

                        <span class="sheets-label">Materia / Grupo</span>

                        <?php foreach ($lista as $item): ?>
                            <p class="sheets-text"><?= h($item) ?></p>
                        <?php endforeach; ?>

                        <span class="sheets-label">Enlace</span>
                        <p class="sheets-link">
                            https://docs.google.com/spreadsheets/d/abc123/edit
                        </p>
                    </div>

                    <div class="sheets-divider"></div>

                    <div class="sheets-actions">
                        <button class="btn-secondary">
                            <i class="fas fa-eye"></i> Ver Enlace
                        </button>

                        <button class="btn-primary">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <!-- Mantiene diseño si no hay datos -->
            <div class="sheets-item">
                <div class="sheets-info">
                    <p class="sheets-text">No hay resultados</p>
                </div>
            </div>

        <?php endif; ?>

    </div>

</div>
<script>
document.querySelectorAll('.sheets-select').forEach(select => {
    select.addEventListener('change', function() {

        const form = this.form;

        // eliminar inputs ocultos anteriores
        form.querySelectorAll('.hidden-input').forEach(e => e.remove());

        // mantener valores de los otros selects
        document.querySelectorAll('.sheets-select').forEach(s => {
            if (s !== this && s.value !== '') {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = s.name;
                input.value = s.value;
                input.classList.add('hidden-input');
                form.appendChild(input);
            }
        });

        form.submit();
    });
});
</script>
