<?php

namespace App\Console\Commands;

use DateTime;
use App\Models\CalendarSync;
use Illuminate\Console\Command;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Components\Calendar;

class SyncPartnerCalenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:partnerCalender';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $syncs = CalendarSync::latest()->get();
 $eve =  Calendar::create('$property->name')
                ->event(Event::create('$property->name' . ' bookings')
                    ->startsAt(new DateTime('21 February 2023 15:00'))
                    ->endsAt(new DateTime('22 February 2023 16:00'))
    );

    // dd($eve, );
        foreach ($syncs as $sync ) {
            // $file = file_get_contents(url('/storage/ics/986737f7-4d9a-4fdb-870b-2cff4e76c85f/989f14b0-73b3-4f75-b8de-ed33ee64bac0.ics'));
            $file = file_get_contents(url($sync->calendar_url));

            $parse = (new Calendar)->create($file);

            dd($eve, $parse);
        }
        return Command::SUCCESS;
    }
}
