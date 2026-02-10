<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Servicio Entity
 *
 * @property int $id
 * @property string|null $titulo_es
 * @property string|null $titulo_en
 * @property string|null $descripcion_es
 * @property string|null $descripcion_en
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Servicio extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'nombre_es' => true,
        'nombre_en' => true,
        'descripcion_es' => true,
        'descripcion_en' => true,
        'imagen' => true,
        'path' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
    ];
}