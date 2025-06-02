<?php

namespace App\Console\Commands;

use App\Enums\Action;
use App\Enums\Module;
use App\Models\Permission;
use Illuminate\Console\Command;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions to database based on Action and Module enums';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Start syncing permissions...");

        $modules = Module::getValues();
        $actions = Action::getValues();

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                if ($module == Module::DASHBOARD && !in_array($action, [Action::ACCESS]))
                    continue;

                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        $this->info("Permissions successfully synced!");
    }
}
