<?php

use App\Models\RequestInfo;
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
		Schema::create('request_infos', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('admin_id')->nullable()->comment('id Исполнителя');
			$table->boolean('from_pc');
			$table->string('mac', 17)->nullable()->comment('MAC Address');
			$table->string('name', 128)->comment('Имя заявителя');
			$table->string('email', 128)->nullable()->comment('Почта заявителя');
			$table->unsignedBigInteger('user_id')->nullable()->comment('id Пользователя');
			$table->string('session_id', 128)->nullable()->comment('id Сессии, если пользователь не зарегистрирован');
			$table->string('ip_address', 15)->comment('IP Address Пользователя');
			$table->string('topic', 128)->comment('Тема заявки');
			$table->string('inventory_number', 128)->nullable()->comment('Инвентарный номер');
			$table->string('location', 255)->comment('Местонахождение');
			$table->string('phone_call_number', 32)->nullable()->comment('Телефонный номер');
			$table->boolean('solution_with_me')->nullable()->comment('Присутствие заявителя во время выполнения');
			$table->boolean('problem_with_my_pc')->comment('Проблема с ПК заявителя');
			$table->json('work_time')->nullable()->comment('Рабочее время');
			$table->string('user_password', 128)->nullable()->comment('Пароль ПК заявителя');
			$table->string('text', 4096)->comment('Текст заявки');
			$table->string('status', 128)->comment('[В обработке, В работе, Завершено, Отменено]');
			$table->string('photo')->nullable()->comment('Фотография');
			$table->json('comments')->nullable()->comment('Комментарии/События');
			$table->unsignedBigInteger('job_id')->nullable()->comment('id job');
			$table->integer('time_remaining')->nullable()->comment('Время на выполнение заявки (Часы)');
			$table->timestamp('accepted_at')->nullable();
			$table->timestamp('closed_at')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
			$table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
			$table->foreign('job_id')->references('id')->on('jobs')->nullOnDelete();
		});

		//ТЕСТ
		RequestInfo::factory()->count(50)->create();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('request_infos');
	}
};
