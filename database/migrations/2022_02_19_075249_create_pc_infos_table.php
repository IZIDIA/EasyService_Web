<?php

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
		Schema::create('pc_infos', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('request_info_id')->comment('id Заявки');
			$table->json('operating_system')->comment('Информация об операционной системе и её настройках');
			$table->json('specs')->comment('Характеристики комплектующих компьютера');
			$table->json('temps')->comment('Датчики температуры');
			$table->json('active_processes')->comment('Действующие процессы WINDOWS');
			$table->json('network')->comment('Сетевая информация');
			$table->json('devices')->comment('Список подключённых устройств');
			$table->json('disks')->comment('Состояние твердотельных накопителей');
			$table->json('installed_programs')->comment('Список установленных программ');
			$table->json('autoload_programs')->comment('Программы в автозагрузке');
			$table->json('performance')->comment('Данные загруженности системы ');
			$table->timestamps();

			$table->foreign('request_info_id')->references('id')->on('request_infos')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('pc_infos');
	}
};
