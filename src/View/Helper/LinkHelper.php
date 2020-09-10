<?php 
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Http\ServerRequest;

class LinkHelper extends Helper {
	public $helpers = ['Url'];

	public function findUrlsInText($text)
	{
		$reg_exUrl = "/((http|https|ftp|ftps)\:\/\/)*(www\.)*[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}[^)\s<]*/";

		if (preg_match_all($reg_exUrl, $text, $urls)) {
			foreach ($urls[0] as $url) {
				$text = str_replace($url, "<a href='{$url}'>{$url}</a>", $text);
			}
		}

		return $text;
	}

    public function isActivePage($url, $matterNav = null) 
   	{
    	// Remove query parameters
    	$base = explode('?', ( $this->getView()->getRequest()->getPath()) );
    	
 		if ( $url == $base[0] && $matterNav ) {
			return 'active-matter-nav';
		} else if ( $url == $base[0] ) {
			return 'active-nav';
		}

		return '';
    }
}