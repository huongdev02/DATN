<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use Illuminate\Support\Carbon;

class DeleteExpiredTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-tokens-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expirationTime = Carbon::now()->subDays(1); // Xác định thời gian hết hạn

        User::with('tokens')->get()->each(function ($user) use ($expirationTime) {
            $user->tokens()->where('created_at', '<', $expirationTime)->delete();
        });

        $this->info('Expired tokens deleted successfully.');
    }
}
