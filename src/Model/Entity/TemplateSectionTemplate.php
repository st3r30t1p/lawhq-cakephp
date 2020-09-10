<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TemplateSectionTemplate Entity
 *
 * @property int $id
 * @property int $template_id
 * @property int $section_template_id
 *
 * @property \App\Model\Entity\Template $template
 * @property \App\Model\Entity\SectionTemplate $section_template
 */
class TemplateSectionTemplate extends Entity
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
        'template_id' => true,
        'section_template_id' => true,
        'template' => true,
        'section_template' => true
    ];
}
