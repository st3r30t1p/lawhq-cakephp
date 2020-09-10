<?php
namespace App\Model\Entity;

use App\Service\Docket\BaseDocketService;
use Cake\ORM\Entity;

/**
 * Docket Entity
 *
 * @property int $id
 * @property string|null $case_name
 * @property string $case_number
 * @property string|null $fed_case_number_judges
 * @property string|null $court_fed_abbr
 * @property int $court_id
 * @property int|null $matter_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Court $court
 * @property \App\Model\Entity\Matter $matter
 * @property \App\Model\Entity\DocketAttachment[] $docket_attachments
 * @property \App\Model\Entity\DocketEntry[] $docket_entries
 */
class Docket extends Entity
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
        'case_name' => true,
        'case_number' => true,
        'fed_case_number_judges' => true,
        'court_fed_abbr' => true,
        'court_id' => true,
        'matter_id' => true,
        'created' => true,
        'modified' => true,
        'court' => true,
        'matter' => true,
        'docket_attachments' => true,
        'docket_entries' => true
    ];


    public function getCaseNumber()
    {
            return $this->case_number . $this->fed_case_number_judges;
    }

}
