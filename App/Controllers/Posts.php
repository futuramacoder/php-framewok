<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Post;

class Posts extends \Core\Controller
{
	public function indexAction()
	{
	    $posts = Post::getAll();
		View::renderTemplate('Posts/index.html', [
		    'posts' => $posts
        ]);
	}

	public function addNewAction()
	{
		echo "Hello! addNew";
	}

	public function editAction()
	{
		echo "Hello! Controller posts Action edit";
		echo '<p>Route Parameters: <pre>' .
		htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
	}
}