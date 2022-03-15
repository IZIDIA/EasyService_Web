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
		Schema::create('request_infos', function (Blueprint $table) {
			$table->id();
			$table->boolean('from_pc');
			//Если с пк, то определяем пользователя по ключу (MAC)
			$table->string('key', 12)->nullable()->comment('MAC Address');
			$table->string('name', 128);
			$table->string('email', 128);
			//Если с сайта, то по зарегистрировавшемуся пользователю
			$table->unsignedBigInteger('user_id')->nullable()->comment('id Пользователя');
			$table->string('session_id', 128)->nullable()->comment('id Сессии, если пользователь не зарегистрирован');	
			$table->string('ip_address', 15)->comment('IP Address Пользователя');
			$table->string('topic', 128);
			$table->string('inventory_number', 128)->nullable();
			$table->string('location', 255);
			$table->string('phone_call_number', 32);
			$table->boolean('solution_with_me')->nullable();
			$table->boolean('problem_with_my_pc');
			$table->string('work_time', 255)->nullable();
			$table->string('user_password', 128)->nullable();
			$table->string('text', 4096);
			$table->string('status', 128);
			$table->string('photo')->comment('Фотография')->nullable();
			$table->timestamp('closed_at')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
		});
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
