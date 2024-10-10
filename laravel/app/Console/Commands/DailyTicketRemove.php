<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class DailyTicketRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::table('ticket')->whereDate('expired', '<', now())->orWhere('count', 0)->delete();
    }
}
