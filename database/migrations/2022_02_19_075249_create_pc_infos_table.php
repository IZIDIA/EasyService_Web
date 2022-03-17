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
			$table->text('operating_system')->comment('Информация об операционной системе и её настройках');
			$table->text('specs')->comment('Характеристики комплектующих');
			$table->text('temp')->comment('Температуры');
			$table->text('active_processes')->comment('Активный процессы Windows');
			$table->text('network')->comment('Информация о сети');
			$table->text('devices')->comment('Список подключенной периферии');
			$table->text('disk')->comment('Состояние дисков');
			$table->text('performance')->comment('Оценка производительности');
			$table->text('autoload')->comment('Программы в автозагрузке');
			$table->text('installed_programs')->comment('Список установленных программ');
			$table->timestamps();

			$table->foreign('request_info_id')->references('id')->on('request_infos');
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
