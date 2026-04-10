<?php

namespace Database\Seeders;

use App\Models\Timezone;
use DateTimeZone;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    public function run(): void
    {
        $timezones = collect(DateTimeZone::listIdentifiers())
            ->map(fn (string $name) => ['name' => $name])
            ->all();

        Timezone::query()->upsert($timezones, ['name'], ['name']);
    }
}
