<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\ORM\TableRegistry;

class BlogPost extends Entity 
{
	protected function _setSlug($title)
	{	
		$title = str_replace(["'", '"'], "", $title );
	    return Text::slug(strtolower($title));
	}

	protected function _setExcerpt($body)
	{
	    $text = strip_tags($body);
	    $text = preg_replace("/[\r\n]+/", "\n", $text);
	    $text = preg_replace("/\s+/", ' ', $text);

	    return Text::truncate(
	        $text,
	        150,
	        [
	            'ellipsis' => '...',
	            'exact' => true
	        ]
	    );
	}

	public function getSha1() 
	{
		$filesTable = TableRegistry::getTableLocator()->get('Files');
		$file = $filesTable->findByFilename('blog_' . $this->cover_img)->first();

		if ($file) {
			return $file->sha1;
		}

		return null;
	}

	public function state()
	{
		if ($this->state == 'published') 
			return '<span class="tag is-success">Published</span>';
		else
			return '<span class="tag is-warning">'. ucfirst($this->state) .'</span>';
	}
}