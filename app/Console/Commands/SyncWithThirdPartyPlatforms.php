<?php

namespace App\Console\Commands;

use App\Models\CalendarSync;
use App\Models\PropertyBlockRoom;
use Illuminate\Console\Command;
use ICal\ICal;

class SyncWithThirdPartyPlatforms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:thirdPartyPlatform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync:thirdPartyPlatform';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $calendars = CalendarSync::get();

        foreach ($calendars as $key => $calendar) {
            $calendarUrl = $calendar->calendar_url;

            try {
                $ical = new ICal($calendarUrl);

                $events = $ical->eventsFromInterval('1 year');

                foreach($events as $event){
                    PropertyBlockRoom::firstOrCreate([
                        'property_id' => $calendar->property_id,
                        'date' => $ical->iCalDateToDateTime($event->dtstart_array[3]),
                    ], [
                        'status' => true,
                        'blocked_by' => 'third party',
                    ]);
                }
            } catch (\Exception $e) {
                // die($e);
            }
        }


        return Command::SUCCESS;
    }
}
