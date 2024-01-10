<?php

namespace Dizit\Blog\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BlogSetupCommand extends BaseCommand
{
    protected $group = 'Dizit';
    protected $name = 'blog:setup';
    protected $description = 'Configura o blog.';

    public function run(array $params)
    {
        // Sua lógica de setup aqui
        CLI::write('Configurando o blog...', 'green');
    }
}
