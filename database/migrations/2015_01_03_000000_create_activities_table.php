<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

class CreateActivitiesTable extends Migration {

	//'§ACTIVITIES'=>['Control','Editorial']
	private $cols = ['name','label','category_id'];
	private $data = [
		['ACCESS_CONTROL','Eligible to reach access control',0],
		['USER_ACCESS','User management access',0],
		['USER_MODIFY','User modification',0],
		['USER_SELF_MODIFY','Own Profile Editing',0],
		['USER_SHOW','User show details',0],
		['EDIT_CONFIG','Editorial configuration access',1],
	];


	public function up() {
		Schema::create('activities', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('label')->nullable();
			$table->text('comment')->nullable();
			$table->integer('category_id')->unsigned()->nullable();
			$table->foreign('category_id')->references('id')->on('categories')
				->onDelete('restrict');

			$table->timestamps();
		});

		$cats=[];
		array_push($cats,Category::reference('Control','ACTIVITIES')->first()->id);
		array_push($cats,Category::reference('Editorial','ACTIVITIES')->first()->id);
		$records = [];
		foreach ($this->data as $record) {
			$record[2] = $cats[$record[2]];
			array_push($records, array_combine($this->cols, $record));
		}
		DB::table('activities')->insert($records);

	}

	/**
	 * Reverse the migrations.
	 */
	public function down() {

		Schema::table('activities', function (Blueprint $table) {
			$table->dropForeign('activities_category_id_foreign');
			$table->dropColumn(['category_id']);
		});

		Schema::dropIfExists('activities');
	}
}
