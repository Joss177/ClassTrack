<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comunidade Entity
 *
 * @property int $id
 * @property string $tittle
 * @property string $description
 * @property string $img
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Comunidade extends Entity
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
        'tittle' => true,
        'description' => true,
        'img' => true,
        'created' => true,
        'modified' => true,
    ];
}
