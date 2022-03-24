<?php

use App\Models\User;
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
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->boolean('is_admin')->default(false)->comment('Администратор');
			$table->string('name');
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->rememberToken();
			$table->timestamps();
		});
		$admin = new User();
		$admin->is_admin = true;
		$admin->name = 'Главный Администратор';
		$admin->email = 'admin@mail.com';
		$admin->password = '$2y$10$0ZnubMvEkECnQprz3mgzKOJ7PgVELXdrlOlvMf0LLEEJhBvos01iW'; // Xdq59svn
		$admin->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
};
