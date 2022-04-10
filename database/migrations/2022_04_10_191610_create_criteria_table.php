<?php

use App\Models\Criterion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('criteria', function (Blueprint $table) {
			$table->id();
			$table->integer('max_temp_cpu');
			$table->integer('max_temp_gpu');
			$table->timestamps();
		});
		//Одна строка на всю таблицу.
		$criterion = new Criterion();
		$criterion->max_temp_cpu = 60;
		$criterion->max_temp_gpu = 60;
		$criterion->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('criteria');
	}
};
