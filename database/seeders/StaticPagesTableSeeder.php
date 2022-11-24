<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class StaticPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        StaticPage::firstOrCreate([
            'slug' => 'terms',
        ], [
            'name'    => 'Conditions générales d\'utilisation',
            'content' => file_get_contents(database_path('seeds/terms.md')),
        ]);
    }
}
