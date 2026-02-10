<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Http\ServerRequest;

/**
 * MenuHelper
 *
 * Helper para generar clases de menú activas automáticamente según
 * el controller, action y prefix actuales.
 */
class MenuHelper extends Helper
{
    /**
     * Devuelve la clase CSS para un item del menú.
     *
     * @param string $controller Nombre del controller
     * @param string $action Nombre de la acción (default 'index')
     * @param string|null $prefix Prefijo de la ruta (ej: 'admin')
     * @return string 'item active' si es la ruta actual, 'item' si no
     */
    public function activeClass(string $controller, string $action = 'index', ?string $prefix = null): string
    {
        /** @var ServerRequest $request */
        $request = $this->getView()->getRequest();

        $currentController = $request->getParam('controller');
        $currentAction     = $request->getParam('action');
        $currentPrefix     = $request->getParam('prefix');

        return ($currentController === $controller &&
                $currentAction === $action &&
                $currentPrefix === $prefix)
            ? 'item active'
            : 'item';
    }
}
