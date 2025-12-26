<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Arzavo\Theme;
use Illuminate\Support\Facades\File;

class SyncThemes extends Command
{
    protected $signature = 'themes:sync';
    protected $description = 'Sync filesystem themes with database (create, update, delete)';

    public function handle()
    {
        $this->info('🔄 Starting theme sync...');
        $this->newLine();

        $basePath = resource_path('views/tenant/themes');

        if (!File::exists($basePath)) {
            $this->error("❌ Themes directory not found: {$basePath}");
            return Command::FAILURE;
        }

        $foundSlugs = [];
        $created = 0;
        $updated = 0;
        $deleted = 0;
        $errors  = 0;

        /*
         |--------------------------------------
         | 1. SCAN FILESYSTEM (CREATE / UPDATE)
         |--------------------------------------
         */
        foreach (glob($basePath . '/*/theme.json') as $file) {

            $folder = basename(dirname($file));

            try {
                $data = json_decode(file_get_contents($file), true);

                if (!$data || empty($data['folder']) || empty($data['name'])) {
                    throw new \Exception('Invalid or incomplete theme.json');
                }

                $foundSlugs[] = $data['folder'];

                $theme = Theme::where('slug', $data['folder'])->first();

                if ($theme) {
                    $theme->update([
                        'name'      => $data['name'],
                        'category'  => $data['category'] ?? null,
                        'version'   => $data['version'] ?? $theme->version,
                        'is_paid'   => $data['is_paid'] ?? false,
                        'price'     => $data['price'] ?? null,
                        'is_active' => true,
                    ]);

                    $updated++;
                    $this->line("🔁 Updated: <info>{$data['name']}</info> ({$data['folder']})");
                } else {
                    Theme::create([
                        'slug'      => $data['folder'],
                        'name'      => $data['name'],
                        'category'  => $data['category'] ?? null,
                        'version'   => $data['version'] ?? '1.0.0',
                        'is_paid'   => $data['is_paid'] ?? false,
                        'price'     => $data['price'] ?? null,
                        'source'    => 'system',
                        'is_active' => true,
                    ]);

                    $created++;
                    $this->line("✅ Created: <info>{$data['name']}</info> ({$data['folder']})");
                }
            } catch (\Throwable $e) {
                $errors++;
                $this->error("❌ Failed: {$folder} → {$e->getMessage()}");
            }
        }

        /*
         |--------------------------------------
         | 2. DELETE MISSING SYSTEM THEMES
         |--------------------------------------
         */
        $dbSystemThemes = Theme::where('source', 'system')->pluck('slug')->toArray();

        $toDelete = array_diff($dbSystemThemes, $foundSlugs);

        foreach ($toDelete as $slug) {
            Theme::where('slug', $slug)->delete();
            $deleted++;
            $this->warn("🗑️ Deleted from DB: {$slug}");
        }

        /*
         |--------------------------------------
         | 3. SUMMARY
         |--------------------------------------
         */
        $this->newLine();
        $this->info('📊 Sync Summary');
        $this->line('------------------------');
        $this->line("🆕 Created : {$created}");
        $this->line("🔄 Updated : {$updated}");
        $this->line("🗑️ Deleted : {$deleted}");
        $this->line("❌ Errors  : {$errors}");
        $this->line('------------------------');

        $this->newLine();
        $this->info('✅ Theme sync completed.');

        return Command::SUCCESS;
    }
}
