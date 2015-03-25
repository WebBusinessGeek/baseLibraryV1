<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMockBaseModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mockBaseModels', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('attribute1');
			$table->string('attribute2');
			$table->string('attribute3');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mockBaseModels');
	}

}
