<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DocketDriveUrl Entity
 *
 * @property int $id
 * @property int $docket_id
 * @property string $doc_name
 * @property string $link
 *
 * @property \App\Model\Entity\Docket $docket
 */
class DocketDriveUrl extends Entity
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
        'docket_id' => true,
        'doc_name' => true,
        'link' => true,
        'docket' => true
    ];
}
