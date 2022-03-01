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
			$table->string('key', 12)->nullable()->comment('MAC Addres');
			//Если с сайта, то по зарегистрировавшемуся пользователю
			$table->unsignedBigInteger('user_id')->nullable()->comment('id Пользователя');
			$table->string('topic', 128);
			$table->string('inventory_number', 128)->nullable();
			$table->date('date_create');
			$table->date('date_closing')->nullable();
			$table->string('location', 255);
			$table->string('phone_call_number', 32)->nullable();
			$table->string('email', 128);
			$table->boolean('solution_with_me');
			$table->boolean('problem_with_my_pc');
			$table->string('work_time', 255);
			$table->string('text', 4096);
			$table->string('status', 128);
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