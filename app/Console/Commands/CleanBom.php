<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class CleanBom extends Command
{
    protected $signature = 'clean:bom';
    protected $description = 'Remove UTF-8 BOM from all JSON files';

    public function handle()
    {
        $basePath = resource_path('views');
        $count = 0;

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'json') {
                $path = $file->getPathname();
                $content = file_get_contents($path);

                // remove UTF-8 BOM
                $clean = preg_replace('/^\xEF\xBB\xBF/', '', $content);

                if ($clean !== $content) {
                    file_put_contents($path, $clean);
                    $count++;
                }
            }
        }

        $this->info("BOM removed from {$count} JSON files.");
    }
}
