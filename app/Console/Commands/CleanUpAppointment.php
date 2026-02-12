<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanUpAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-up-appointment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Every appointment gets deleted after the time has passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Appointment::where('end_time','<', Carbon::now())->delete();
    }
}
