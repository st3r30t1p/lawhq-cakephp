<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Domain Entity
 *
 * @property int $id
 * @property string|null $domain
 * @property int|null $message_frequency
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\DomainRelationship[] $domain_relationships
 */
class Domain extends Entity
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
        'domain' => true,
        'message_frequency' => true,
        'ignore_on_system_generated_rules' => true,
        'ignore_on_system_generated_rules_reason' => true,
        'created' => true,
        'modified' => true,
        'domain_relationships' => true
    ];
}
