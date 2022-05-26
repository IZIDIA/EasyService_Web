<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestInfo>
 */
class RequestInfoFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		static $counter = 1;
		$json_comment = json_encode(array(
			0 =>
			array(
				'Time' => Carbon::now()->format('d.m.y H:i'),
				'Name' => 'Система',
				'Message' => 'Заявка создана',
			),
		));
		if (count(User::all()) == 1) {
			return [
				'from_pc' => false,
				'name' => User::find(1)->name,
				'email' => $this->faker->unique()->safeEmail(),
				'user_id' => 1,
				'ip_address' => $this->faker->ipv4(),
				'location' => 'Кабинет ' . rand(1, 400),
				'phone_call_number' =>  $this->faker->phoneNumber(),
				'problem_with_my_pc' => false,
				'text' => $this->faker->realText(300),
				'topic' => $this->faker->realText(50),
				'status' => 'В обработке',
				'comments' => $json_comment,
			];
		} else {
			return [
				'from_pc' => false,
				'name' => User::find($counter)->name,
				'email' => $this->faker->unique()->safeEmail(),
				'user_id' => $counter++,
				'ip_address' => $this->faker->ipv4(),
				'location' => 'Кабинет ' . rand(1, 400),
				'phone_call_number' =>  $this->faker->phoneNumber(),
				'problem_with_my_pc' => false,
				'text' => $this->faker->realText(300),
				'topic' => $this->faker->realText(50),
				'status' => 'В обработке',
				'comments' => $json_comment,
			];
		}
	}
}
