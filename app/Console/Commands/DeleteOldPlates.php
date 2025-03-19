<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plate;
use Carbon\Carbon;

class DeleteOldPlates extends Command
{
    protected $signature = 'plates:delete-old';
    protected $description = 'Delete plates older than 365 days';

    public function handle()
    {
        $date = Carbon::now()->subDays(365);
        $count = Plate::where('plate_entry_date', '<=', $date)->delete();
        
        $this->info("Deleted $count old plates");
        return 0;
    }
}