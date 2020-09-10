<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DocketEntry Entity
 *
 * @property int $id
 * @property int|null $docket_id
 * @property int|null $sequence_id
 * @property \Cake\I18n\FrozenDate|null $date_filed
 * @property \Cake\I18n\FrozenDate|null $date_entered
 * @property string|null $docket_type
 * @property string|null $description
 * @property bool|null $has_attachment
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Docket $docket
 */
class DocketEntry extends Entity
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
        'id' => true,
        'docket_id' => true,
        'sequence_id' => true,
        'date_filed' => true,
        'date_entered' => true,
        'docket_type' => true,
        'description' => true,
        'has_attachment' => true,
        'created' => true,
        'modified' => true,
        'docket' => true
    ];
}
