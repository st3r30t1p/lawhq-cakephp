<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class UrlDetailsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Files');
    }

    public function getUrlIdsByDomain($domain)
    {
    	$urlIds = $this->find()
    	->select(['url_id'])
    	->distinct(['url_id'])
    	->where(["url LIKE '%{$domain}%'"]);
    	return $urlIds->toArray();
    }
}