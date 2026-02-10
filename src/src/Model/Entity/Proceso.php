<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Proceso Entity
 *
 * @property int $id
 * @property string $tittle1
 * @property string $description1
 * @property string $tittle2
 * @property string $description2
 * @property \Cake\I18n\FrozenDate|null $created
 * @property \Cake\I18n\FrozenDate|null $modified
 */
class Proceso extends Entity
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
        'tittle1' => true,
        'description1' => true,
        'tittle2' => true,
        'description2' => true,
        'created' => true,
        'modified' => true,
    ];
}
