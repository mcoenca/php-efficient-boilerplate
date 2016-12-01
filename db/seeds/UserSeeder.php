<?php

use Phinx\Seed\AbstractSeed;
// require_once realpath(dirname(dirname(dirname(__FILE__))) . '/app/config.php');
// require_once realpath(ROOT_PATH . '/vendor/autoload.php');

class UserSeeder extends AbstractSeed
{
	/**
	 * Run Method.
	 *
	 * Write your database seeder using this method.
	 *
	 * More information on writing seeders is available here:
	 * http://docs.phinx.org/en/latest/seeding.html
	 */
	public function run()
	{
		$faker = \Faker\Factory::create('fr_FR');
    $data = \Functional\map(range(0, 100), function($idx) use($faker) {
      $faker->seed($idx);
      return [
        'mail' => $faker->email,
        'password' => md5('secret-password'), //using this to test our algo in login
        'date_create' => $faker->dateTimeBetween('2016-01-01', '2016-10-31', 'Europe/Paris')->format('Y-m-d H:i:s'),
        'date_cancel' => '0000-00-00 00:00:00',
        'last_login' => $faker->dateTimeBetween('2016-11-01', '2016-11-20', 'Europe/Paris')->format('Y-m-d H:i:s'),
        'active' => 1,
        'civilite' => 'M',
        'prenom' => $faker->firstName,
        'nom' => $faker->lastName,
        'adress1' => $faker->streetAddress,
        'adress2' => '',
        'city' => $faker->city,
        'zipcode' => $faker->postcode,
        'country' => 'France Métropolitaine',
        'telephone' => $faker->phoneNumber,
        'job' => $faker->jobTitle,
      ];
    });

    $this->insert('users', $data);
	}
}
