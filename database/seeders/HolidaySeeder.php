<?php

// database/seeders/HolidaySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;
use Carbon\Carbon;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        $holidays = [
            ['name' => 'New Year’s Day', 'date' => "$year-01-01", 'type' => 'public', 'description' => 'Start of the year'],
            ['name' => 'Constitution Day', 'date' => "$year-01-07", 'type' => 'public', 'description' => 'Celebrates the 4th Republic'],
            ['name' => 'Independence Day', 'date' => "$year-03-06", 'type' => 'public', 'description' => 'Ghana gained independence in 1957'],
            ['name' => 'Workers’ Day', 'date' => "$year-05-01", 'type' => 'public', 'description' => 'Labour Day'],
            ['name' => 'Founders’ Day', 'date' => "$year-08-04", 'type' => 'public', 'description' => 'Honors all founders of Ghana\'s independence'],
            ['name' => 'Kwame Nkrumah Memorial Day', 'date' => "$year-09-21", 'type' => 'public', 'description' => 'Birthday of Dr. Nkrumah'],
            ['name' => 'Christmas Day', 'date' => "$year-12-25", 'type' => 'public', 'description' => 'Christian holiday'],
            ['name' => 'Boxing Day', 'date' => "$year-12-26", 'type' => 'public', 'description' => 'Celebrated after Christmas'],

            // Historical (non-statutory)
            ['name' => 'Kwame Nkrumah Day (Old)', 'date' => "$year-06-04", 'type' => 'company', 'description' => 'Now replaced by Founders\' Day'],
            ['name' => 'Republic Day', 'date' => "$year-06-28", 'type' => 'company', 'description' => 'No longer a statutory holiday'],
        ];

        // 🎉 Add variable holidays
        $this->addEasterHolidays($holidays, $year);
        $this->addEidHolidays($holidays, $year);

        // Insert/update all
        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date'], 'name' => $holiday['name']],
                $holiday
            );
        }
    }

    protected function addEasterHolidays(array &$holidays, $year)
    {
        $easter = Carbon::createFromTimestamp(easter_date($year));

        $holidays[] = [
            'name' => 'Good Friday',
            'date' => $easter->copy()->subDays(2)->toDateString(),
            'type' => 'religious',
            'description' => 'Christian holiday before Easter',
        ];

        $holidays[] = [
            'name' => 'Easter Monday',
            'date' => $easter->copy()->addDay()->toDateString(),
            'type' => 'religious',
            'description' => 'Christian holiday after Easter',
        ];
    }

    protected function addEidHolidays(array &$holidays, $year)
    {
        // You may want to update these manually each year based on Ghana’s official Islamic calendar
        $holidays[] = [
            'name' => 'Eid-ul-Fitr',
            'date' => "$year-04-10", // Example date, update annually
            'type' => 'religious',
            'description' => 'End of Ramadan',
        ];

        $holidays[] = [
            'name' => 'Eid-ul-Adha',
            'date' => "$year-06-17", // Example date, update annually
            'type' => 'religious',
            'description' => 'Festival of Sacrifice',
        ];
    }
}
