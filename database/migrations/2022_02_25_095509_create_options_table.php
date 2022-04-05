<?php

use App\Models\Option;
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
		Schema::create('options', function (Blueprint $table) {
			$table->id();
			$table->string('company_name', 64)->comment('Название компании');
			$table->boolean('distributed_requests')->comment('Работа распределённых заявок');
			$table->string('welcome_text', 4096)->comment('Текст на главной странице сайта');
			$table->integer('time_to_work')->comment('Часы на выполнение заявки');
			$table->integer('time_to_accept_distributed')->comment('Часы на принятие распределённой заявки');

			$table->timestamps();
		});
		//Одна строка на всю таблицу.
		$opt = new Option();
		$opt->company_name = 'Easy Service';
		$opt->distributed_requests = true;
		$opt->welcome_text = 'Для отправки заявки на обслуживание оборудования, заполните соответствующую форму на сайте. Если проблема связанна с вашим компьютером или оборудованием, подключённым к нему, крайне необходимо заполнить и отправить заявку в приложении, установленном на вашем компьютере. Если такой возможности нет, или проблема связанна с иным оборудованием, форма заполняется на сайте.'
			. PHP_EOL . 'Для оперативной связи с администратором для помощи, воспользуйтесь контактным справочником на сайте.';
		$opt->time_to_work = 168; //часы
		$opt->time_to_accept_distributed = 72; //часы
		$opt->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('options');
	}
};
