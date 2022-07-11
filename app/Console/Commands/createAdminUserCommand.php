<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DataProviders\Models\Admin;
use Illuminate\Support\Facades\Hash;

class createAdminUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create_admin_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'adminユーザーを作成する。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $admin = new Admin();
        $admin->email = config('auth.admin_email');
        $admin->password = Hash::make(config('auth.admin_password'));
        $admin->save();
        $this->info('adminユーザーが登録されました');
        return 0;
    }
}
