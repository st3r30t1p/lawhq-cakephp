<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

/**
 * Template Entity
 *
 * @property int $id
 * @property string $name
 * @property string $google_doc_id
 * @property array $parameters
 * @property FrozenTime $created
 * @property FrozenTime $modified
 */
class Template extends Entity
{
    use LazyLoadEntityTrait;
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
        'name' => true,
        'google_doc_id' => true,
        //'parameters' => true,
        'created' => true,
        'modified' => true,
    ];


//    protected function _setParameters($parameters)
//    {
//        return json_encode($parameters);
//    }


    /**
     * @return array
     */
//    protected function _getParameters($parameters)
//    {
//        return json_decode($parameters, true);
//    }

}
