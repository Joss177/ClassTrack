<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rede Entity
 *
 * @property int $id
 * @property string $facebook
 * @property string $instagram
 * @property string $whatsapp
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Rede extends Entity
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
        'facebook' => true,
        'instagram' => true,
        'whatsapp' => true,
        'created' => true,
        'modified' => true,
    ];
}
