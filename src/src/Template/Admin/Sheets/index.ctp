<?= $this->Html->css('sheets', ['block' => true]) ?>
<div class="sheets-container">

    <h2 class="sheets-title">Google Sheets - Falta logica backend</h2>

    <div class="sheets-card">

        <!-- FILTROS -->
        <div class="sheets-filters">
            <select class="sheets-select">
                <option>Todos los Docentes</option>
            </select>

            <select class="sheets-select">
                <option>Todas las Materias</option>
            </select>

            <select class="sheets-select">
                <option>Todos los Grupos</option>
            </select>
        </div>

        <!-- TARJETA -->
        <div class="sheets-item">

            <div class="sheets-info">
                <span class="sheets-label">Docente</span>
                <p class="sheets-text">María González</p>

                <span class="sheets-label">Materia / Grupo</span>
                <p class="sheets-text">Matemáticas - 10-A</p>

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

    </div>

</div>
