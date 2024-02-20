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
        CLI::write('Configurando o blog...', 'green');

        $directories = [
            'Models' => 'Models/',
            'Views' => 'Views/',
            'Controllers' => 'Controllers/',
            // Adicione mais diretórios conforme necessário
        ];

        foreach ($directories as $key => $relativePath) {
            $this->copyFilesFromDirectory($key, $relativePath);
        }

        $this->addRoute("// Auto Routes - Packages");
        $this->addRoute("\$routes->resource('admin/blog', [/*'filter' => 'session'*/]);");
        $this->addRoute("\$routes->resource('blog');");
    }
    private function copyFilesFromDirectory($directoryName, $relativePath)
    {
        $sourceDir = VENDORPATH . 'dizitcodes/blog/src/' . $directoryName . '/';
        $destinationDir = APPPATH . $relativePath;

        $this->recursiveCopy($sourceDir, $destinationDir);
    }

    private function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        while (($file = readdir($dir)) !== false) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    $sourceFile = $src . '/' . $file;
                    $destinationFile = $dst . '/' . $file;
                    if (!file_exists($destinationFile)) {
                        if (!copy($sourceFile, $destinationFile)) {
                            CLI::write("Falha ao copiar {$sourceFile}...", 'red');
                        } else {
                            CLI::write("{$file} copiado com sucesso para {$destinationFile}", 'green');
                        }
                    } else {
                        CLI::write("{$file} já existe em {$destinationFile}. Nenhuma ação foi tomada.", 'yellow');
                    }
                }
            }
        }
        closedir($dir);
    }


    private function addRoute($routeDefinition)
    {
        $routesPath = APPPATH . 'Config/Routes.php';

        // Verifique se o arquivo de rotas existe
        if (!file_exists($routesPath)) {
            CLI::write("O arquivo de rotas não foi encontrado em {$routesPath}", 'red');
            return;
        }

        // Verifique se a linha já existe no arquivo
        $contents = file_get_contents($routesPath);
        if (strpos($contents, $routeDefinition) !== false) {
            CLI::write('A rota especificada já existe no arquivo de rotas.', 'yellow');
            return;
        }

        // Adicione a linha ao final do arquivo
        if (!file_put_contents($routesPath, PHP_EOL . $routeDefinition, FILE_APPEND)) {
            CLI::write("Não foi possível escrever no arquivo de rotas em {$routesPath}", 'red');
        } else {
            CLI::write('A rota foi adicionada com sucesso.', 'green');
        }
    }
}
