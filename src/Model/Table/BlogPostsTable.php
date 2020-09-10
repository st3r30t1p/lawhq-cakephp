<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class BlogPostsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
    	$this->belongsTo('TeamMembers');
    }
}