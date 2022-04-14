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
			$table->string('welcome_text', 2001)->comment('Текст на главной странице сайта');
			$table->string('welcome_text_app', 1001)->comment('Текст на странице приложения');
			$table->integer('time_to_work')->comment('Часы на выполнение заявки');
			$table->integer('time_to_accept_distributed')->comment('Часы на принятие распределённой заявки');
			$table->integer('check_interval')->comment('Интервал проверки для звуковых уведомлений');
			$table->timestamps();
		});
		//Одна строка на всю таблицу.
		$opt = new Option();
		$opt->company_name = 'Easy Service';
		$opt->distributed_requests = true;
		$opt->welcome_text = 'Для отправки заявки на обслуживание оборудования, заполните соответствующую форму на сайте. Если проблема связанна с вашим компьютером или оборудованием, подключённым к нему, крайне необходимо заполнить и отправить заявку в приложении, установленном на вашем компьютере. Если такой возможности нет, или проблема связанна с иным оборудованием, форму можно заполнить на сайте.'
			. PHP_EOL . 'Для оперативной связи с администратором для помощи, воспользуйтесь контактным справочником на сайте.';
		$opt->welcome_text_app = 'Если проблема связанна с вашим компьютером или оборудованием, подключённым к нему, заполните и отправьте заявку в данном приложении.' 
		. PHP_EOL . 'На веб-сайте можно просмотреть контакты, документы и также создать заявку. Для перехода на веб-сайт воспользуйтесь кнопкой в правом верхнем углу.';
		$opt->time_to_work = 168; //часы
		$opt->time_to_accept_distributed = 72; //часы
		$opt->check_interval = 10000; //миллисекунды
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
