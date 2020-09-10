<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DomainRelationship Entity
 *
 * @property int $id
 * @property int|null $domain_id
 * @property int|null $domain_id_link
 * @property int|null $count
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Domain $domain
 */
class DomainRelationship extends Entity
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
        'domain_id' => true,
        'domain_id_link' => true,
        'count' => true,
        'created' => true,
        'modified' => true,
        'domain' => true
    ];

    public function getOtherDomainName($primaryDomainId)
    {
        $lookupId = $this->domain_id;

        if ($this->domain_id == $primaryDomainId) {
            $lookupId = $this->domain_id_link;
        }

        return $lookupId;
    }
}
