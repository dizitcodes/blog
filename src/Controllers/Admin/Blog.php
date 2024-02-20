<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;

class Blog extends ResourceController
{
    public function index()
    {
        return view('admin/blog/posts');
    }
}
