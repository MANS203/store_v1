<?php
namespace Database\Seeders;

use App\Models\Contact;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $types = [1, 2, 3];

        for ($i = 1; $i <= 9; $i++) {
            $contact = new Contact();
            $contact->type = $faker->randomElement($types);
            $contact->value = $faker->unique()->url;
            $contact->image = 'https://student.valuxapps.com/storage/uploads/contacts/' . $faker->word() . '.png';
            $contact->save();
        }
    }
}
