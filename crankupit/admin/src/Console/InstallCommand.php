<?php

namespace CrankUpIT\Admin\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * Comman Name and Signature
     *
     * @var string
     */
    protected $signature = 'admin:install';

    public function handle()
    {
        copy(__DIR__ . '/../../stubs/config/auth.php', base_path('config/auth.php'));

        copy(__DIR__ . '/../../stubs/database/factories/AdminFactory.php', base_path('database/factories/AdminFactory.php'));

        copy(__DIR__ . '/../../stubs/database/migrations/2022_10_18_195521_create_admins_table.php', base_path('database/migrations/2022_10_18_195521_create_admins_table.php'));

        copy(__DIR__ . '/../../stubs/database/migrations/2022_10_18_200254_create_admins_sessions_table.php', base_path('database/migrations/2022_10_18_200254_create_admins_sessions_table.php'));

        copy(__DIR__ . '/../../stubs/Models/Admin.php', app_path('Models/Admin.php'));

        $this->replaceInFile('// \App\Models\User::factory(10)->create();', '\App\Models\Admin::factory()->create();', base_path('database/seeders/DatabaseSeeder.php'));
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
