<?php

use App\Models\Admin;
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
		Schema::create('admins', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id')->comment('id Пользователя');
			$table->boolean('is_master')->default(false)->comment('Главный Администратор');
			$table->boolean('get_recommendation')->default(false)->comment('Получение распределённых заявок');
			$table->boolean('sound_notification')->default(false)->comment('Получение звуковых уведомлений');
			$table->boolean('free')->default(true)->comment('Показатель того, есть ли у администратора заявки "В работе"');
			$table->integer('week_time')->default(0)->comment('Суммарное время выполнения заявок за неделю (Минуты)');
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
		});
		$admin = new Admin();
		$admin->user_id = 1;
		$admin->is_master = true;
		$admin->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('admins');
	}
};
