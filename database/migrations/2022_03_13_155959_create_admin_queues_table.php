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
		Schema::create('admin_queues', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('admin_id')->nullable()->comment('id Администратора');
			$table->unsignedBigInteger('request_id')->nullable()->comment('id Заявки');
			$table->timestamps();

			$table->foreign('admin_id')->references('id')->on('users');
			$table->foreign('request_id')->references('id')->on('request_infos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('admin_queues');
	}
};
