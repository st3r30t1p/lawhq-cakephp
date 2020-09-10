<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MatterCourt Entity
 *
 * @property int $id
 * @property int|null $matter_id
 * @property string|null $court_id
 * @property string|null $case_number
 * @property string|null $start
 * @property string|null $end
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Matter $matter
 * @property \App\Model\Entity\Court $court
 * @property \App\Model\Entity\Docket[] $dockets_old
 */
class MatterCourt extends Entity
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
        'court_id' => true,
        'case_number' => true,
        'start' => true,
        'end' => true,
        'created' => true,
        'modified' => true,
        'matter' => true,
        'court' => true,
        'dockets' => true
    ];
}
