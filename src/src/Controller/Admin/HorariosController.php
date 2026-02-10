<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * HorariosController
 *
 * Controlador para la sección Admin de Horarios
 */
class HorariosController extends AppController
{
    /**
     * Inicialización del controlador
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash'); // Para mensajes si los necesitas
    }

    /**
     * Index
     *
     * Muestra la vista de horarios
     */
    public function index()
    {
        // Aquí puedes pasar variables a la vista si es necesario
        $this->set('title', 'Horarios');
    }
}
