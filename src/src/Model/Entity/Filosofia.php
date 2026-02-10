<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Filosofia Entity
 *
 * @property int $id
 * @property string $mision
 * @property string $vision
 * @property string $valores
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Filosofia extends Entity
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
        'mision' => true,
        'vision' => true,
        'valores' => true,
        'created' => true,
        'modified' => true,
    ];
}
