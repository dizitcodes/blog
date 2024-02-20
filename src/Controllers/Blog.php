<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Blog extends ResourceController
{
    public function index()
    {
        return view('site/blog/posts');
    }
}
