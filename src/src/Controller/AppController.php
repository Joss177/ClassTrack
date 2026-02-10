<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
     public function initialize()
     {
         parent::initialize();

         $this->loadComponent('RequestHandler', [
             'enableBeforeRedirect' => false,
         ]);
         $this->loadComponent('Flash');
         $link = $this->request->getAttribute("here");
         $link = explode('/',$link);
         if($link[1]=='admin'){
           $this->loadComponent('Auth', [
             'loginAction' => [
               'controller' => 'Users',
               'action' => 'login',
             ],
             'loginRedirect'=> [
               'controller'=>'Admin',
               'action'=>'index',
             ],
             'logoutRedirect'=> [
               'controller'=>'Users',
               'action'=>'login',
             ],
             'authError' => false,
             'authenticate' => [
               'Form' => [
                 'fields' => ['username' => 'email', 'password'=>'password']
               ]
             ],
             'storage' => ['className' => 'Session', 'key' => 'Auth.Admin']
           ]);
         }/*else{
          $this->loadComponent('Auth', [
            'loginAction' => [
              'controller' => 'Maestros',
              'action' => 'login',
            ],
            'loginRedirect'=> [
              'controller'=>'Maestros',
              'action'=>'index',
            ],
            'logoutRedirect'=> [
              'controller' => 'Maestros',
              'action' => 'login',
            ],
            'authError' => 'No tienes permisos para ver este contenido',
            'authenticate' => [
              'Form' => [
                'userModel' => 'Maestros',
                'fields' => ['username' => 'email', 'password'=>'password'],
                'finder' => 'auth'
              ]
            ],
            'storage' => ['className' => 'Session', 'key' => 'Auth.User']
          ]);
        }*/


         /*
          * Enable the following component for recommended CakePHP security settings.
          * see https://book.cakephp.org/3/en/controllers/components/security.html
          */
         //$this->loadComponent('Security');
     }

     public function beforeRender(Event $event) {
       parent::beforeRender($event);
       $link = $this->request->getAttribute("here");
       $link = explode('/',$link);
       if($link[1]=='admin'){
         $this->viewBuilder()->setLayout('admin');
       }

     }

     public function beforeFilter(Event $event){
       parent::beforeFilter($event);
       $link = $this->request->getAttribute("here");
       $link = explode('/',$link);
       if($link[1]=='admin'){
         $this->Auth->deny('');
       }else{
         // $this->Auth->deny('');
         // $this->Auth->setConfig('checkAuthIn', 'Maestros.initialize');
       }
     }

     public function isAuthorized($user = null){
       // Any registered user can access public functions
       if (!$this->request->getParam('prefix')) {
         return true;
       }

       // Only admins can access admin functions
       if ($this->request->getParam('prefix') === 'admin') {
         return (bool)($user['role'] === 'admin');
       }

       // Default deny
       return false;
     }
}
