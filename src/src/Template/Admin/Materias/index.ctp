<div class="gestion-container">

    <h2 class="titulo">Gestión</h2>

    <div class="card-gestion">

        <!-- Tabs -->
        <div class="tabs-header">
            <?php $controller = $this->request->getParam('controller'); ?>

            <div class="tabs">

                <?= $this->Html->link(
                    'Docentes',
                    ['prefix' => 'admin', 'controller' => 'Docentes', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Docentes' ? 'active' : '')]
                ) ?>

                <?= $this->Html->link(
                    'Materias',
                    ['prefix' => 'admin', 'controller' => 'Materias', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Materias' ? 'active' : '')]
                ) ?>

                <?= $this->Html->link(
                    'Grupos',
                    ['prefix' => 'admin', 'controller' => 'Grupos', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Grupos' ? 'active' : '')]
                ) ?>

                <?= $this->Html->link(
                    'Aulas',
                    ['prefix' => 'admin', 'controller' => 'Aulas', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Aulas' ? 'active' : '')]
                ) ?>

            </div>

            <div class="acciones-header">
                <button class="btn-agregar" onclick="abrirModal()">Agregar</button>
            </div>
        </div>

        <!-- LISTADO DE MATERIAS -->
        <div class="materias-list">

            <div class="materia-card">
                <div class="materia-info">
                    <h4>Matemáticas</h4>
                    <p class="clave">MAT-101</p>

                    <div class="color-box">
                        <span class="color-preview" style="background:#3b82f6;"></span>
                        <span class="color-code">#3b82f6</span>
                    </div>
                </div>

                <hr>

                <div class="materia-actions">
                    <button class="btn-editar">Editar</button>
                    <button class="btn-eliminar">Eliminar</button>
                </div>
            </div>

            <div class="materia-card">
                <div class="materia-info">
                    <h4>Física</h4>
                    <p class="clave">FIS-101</p>

                    <div class="color-box">
                        <span class="color-preview" style="background:#10b981;"></span>
                        <span class="color-code">#10b981</span>
                    </div>
                </div>

                <hr>

                <div class="materia-actions">
                    <button class="btn-editar">Editar</button>
                    <button class="btn-eliminar">Eliminar</button>
                </div>
            </div>

        </div>




    </div>
</div>

<style>
body {
    background-color: #f5f6fa;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.gestion-container {
    width: 90%;
    margin: 30px auto;
}

.titulo {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1f2937;
}

/* Card principal */
.card-gestion {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Tabs */
.tabs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.tabs {
    display: flex;
    gap: 10px;
}

.tab {
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 500;
    color: #4b5563;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
}

.tab:hover {
    color: #1e3a5f;
    transform: scale(1.05);
}

.tab.active:hover {
    transform: none;
    background-color: #1e3a5f;
}

.tab.active {
    background-color: #1e3a5f;
    color: #ffffff;
}

/* Botón agregar */
.btn-agregar {
    background-color: #1e3a5f;
    color: #ffffff;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
}

.btn-agregar:hover {
    background-color: #162d49;
}

/* Línea divisoria */
hr {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 15px 0;
}

/* ===== CARDS DOCENTES ===== */
.docente-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 20px;
}

.docente-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.correo {
    margin: 5px 0;
    color: #374151;
    font-size: 14px;
}

.materia {
    color: #6b7280;
    font-size: 14px;
}

.docente-actions {
    display: flex;
    gap: 15px;
}

/* ===== CARDS MATERIAS ===== */
.materias-list {
    margin-top: 10px;
}

.materia-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    transition: 0.2s ease;
}

.materia-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.materia-info h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.clave {
    margin: 6px 0 10px 0;
    font-size: 14px;
    color: #4b5563;
}

/* Color preview */
.color-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
}

.color-preview {
    width: 22px;
    height: 22px;
    border-radius: 4px;
}

.color-code {
    font-size: 14px;
    color: #6b7280;
}

/* ===== BOTONES GENERALES ===== */
.btn-editar,
.btn-eliminar {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #d1d5db;
    background: #ffffff;
}

.btn-editar {
    color: #2563eb;
}

.btn-editar:hover {
    background-color: #eff6ff;
}

.btn-eliminar {
    color: #dc2626;
}

.btn-eliminar:hover {
    background-color: #fee2e2;
}

.materia-actions {
    display: flex;
    gap: 15px;
}
</style>
