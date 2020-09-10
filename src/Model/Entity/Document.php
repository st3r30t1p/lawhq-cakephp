<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Document Entity
 *
 * @property int $id
 * @property int $matter_id
 * @property string $link
 * @property FrozenTime $created
 *
 * @property Matter $matter
 */
class Document extends Entity
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
        'matter_id' => true,
        'link' => true,
        'created' => true,
        'matter' => true
    ];
}
