<?php

namespace App\ArticleModule\Model;

class Article extends \Nette\Object
{
	public function getTime()
	{
		return time();
	}

}
