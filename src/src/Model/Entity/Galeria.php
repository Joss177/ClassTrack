<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Galeria Entity
 *
 * @property int $id
 * @property string $fotos_json
 * @property string|null $path
 * @property int|null $cantidad
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Galeria extends Entity
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
        'img' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
    ];
}
