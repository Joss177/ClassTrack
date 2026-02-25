<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class GestionController extends AppController
{
public function index()
{
    return $this->redirect([
        'prefix' => 'admin', // ← minúscula
        'controller' => 'Docentes',
        'action' => 'index'
    ]);
}
}
