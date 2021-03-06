<?php
namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppDatabaseSeeder extends Seeder {

	protected $toTruncate = [
		'users',
		'user_profiles',
		'roles',
		'addresses',
		'layouts',
		'segments',
		'layout_segment',
		'categories',
		'activities',
		'activity_role',
		'category_role',
		'role_user',
		'role_team_user',
		'teams'
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		//Schema::disableForeignKeyConstraints();
		//
		//foreach ($this->toTruncate as $truncate) {
		//	DB::table($truncate)->truncate();
		//}
		//
		//Schema::enableForeignKeyConstraints();
		//
		//$this->call(CategoriesTableSeeder::class);
		//
		//$this->call(ActivitiesTableSeeder::class);
		//$this->call(UsersTableSeeder::class);
		//$this->call(RolesTableSeeder::class);
		//$this->call(TeamsTableSeeder::class);
		//
		//$this->call(SegmentsTableSeeder::class);
		//$this->call(LayoutsTableSeeder::class);

	}
}
