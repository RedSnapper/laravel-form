<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('user_profiles', function (Blueprint $table) {
			$table->integer('user_id')->unsigned()->nullable();
			$table->string('telephone');
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('user_profiles');
		Schema::enableForeignKeyConstraints();
	}
}
