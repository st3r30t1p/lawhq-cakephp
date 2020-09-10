<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class DoNotCallRegistry extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
}
