<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SwitchEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:switch {environment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '開発環境と本番環境を切り替えます';

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
        $environment = $this->argument('environment');
        $envFile = ".env.{$environment}";

        if (!file_exists($envFile)) {
            $this->error("環境ファイル{$envFile}が見つかりません！");
            return;
        }

        if (file_exists('.env')) {
            copy('.env', '.env.backup');
            $this->info('.envファイルをバックアップしました');
        }

        copy($envFile, '.env');
        $this->info("{$environment}環境に切り替えました");

        $this->call('config:clear');
        $this->call('cache:clear');
        $this->info('キャッシュをクリアしました');
    }
}
