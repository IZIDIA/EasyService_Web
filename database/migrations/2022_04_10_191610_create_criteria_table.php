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
			$table->boolean('windows_key');
			$table->boolean('ethernet');
			$table->boolean('gpu_install');
			$table->integer('max_temp_cpu');
			$table->integer('max_temp_gpu');
			$table->integer('max_load_cpu');
			$table->integer('max_load_gpu');
			$table->integer('min_cores_count');
			$table->integer('min_ram_size');
			$table->json('required_active_processes')->nullable();
			$table->json('required_installed_programs')->nullable();
			$table->json('required_autoload_programs')->nullable();
			$table->timestamps();
		});
		//Одна строка на всю таблицу.
		$criterion = new Criterion();
		$criterion->windows_key = true;
		$criterion->ethernet = true;
		$criterion->gpu_install = true;
		$criterion->max_temp_cpu = 60;
		$criterion->max_temp_gpu = 60;
		$criterion->max_load_cpu = 80;
		$criterion->max_load_gpu = 80;
		$criterion->min_cores_count = 4; //Logical
		$criterion->min_ram_size = 4; //GB
		$criterion->required_active_processes = json_encode(['GoogleUpdate']);
		$criterion->required_installed_programs = json_encode(['Kaspersky']);
		$criterion->required_autoload_programs = json_encode(['tvncontrol']);
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
