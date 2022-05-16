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
			$table->boolean('ethernet');
			$table->boolean('gpu_install');
			$table->boolean('disk_status');
			$table->boolean('check_max_temp_cpu');
			$table->integer('max_temp_cpu');
			$table->boolean('check_max_temp_gpu');
			$table->integer('max_temp_gpu');
			$table->boolean('check_max_load_cpu');
			$table->integer('max_load_cpu');
			$table->boolean('check_max_load_gpu');
			$table->integer('max_load_gpu');
			$table->boolean('check_max_load_ram');
			$table->integer('max_load_ram');
			$table->boolean('check_min_cores_count');
			$table->integer('min_cores_count');
			$table->boolean('check_min_ram_size');
			$table->integer('min_ram_size');
			$table->boolean('check_active_processes');
			$table->json('required_active_processes')->nullable();
			$table->boolean('check_installed_programs');
			$table->json('required_installed_programs')->nullable();
			$table->boolean('check_autoload_programs');
			$table->json('required_autoload_programs')->nullable();
			$table->timestamps();
		});
		$criterion = new Criterion();
		$criterion->ethernet = true;
		$criterion->gpu_install = true;
		$criterion->disk_status = true;
		$criterion->check_max_temp_cpu = true;
		$criterion->max_temp_cpu = 60;
		$criterion->check_max_temp_gpu = true;
		$criterion->max_temp_gpu = 60;
		$criterion->check_max_load_cpu = true;
		$criterion->max_load_cpu = 80;
		$criterion->check_max_load_gpu = true;
		$criterion->max_load_gpu = 80;
		$criterion->check_max_load_ram = true;
		$criterion->max_load_ram = 80;
		$criterion->check_min_cores_count = true;
		$criterion->min_cores_count = 4; //Logical
		$criterion->check_min_ram_size = true;
		$criterion->min_ram_size = 4; //GB
		$criterion->check_active_processes = true;
		$criterion->check_installed_programs = true;
		$criterion->check_autoload_programs = true;
		$criterion->required_active_processes = json_encode(['GoogleUpdate', 'svchost']);
		$criterion->required_installed_programs = json_encode(['Kaspersky', 'Notepad++']);
		$criterion->required_autoload_programs = json_encode(['tvncontrol', 'OneDrive']);
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
