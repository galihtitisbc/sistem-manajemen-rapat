<?php

namespace Modules\Rapat\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Core\User;
use Carbon\Carbon;

class RapatAgendaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Rapat\Entities\RapatAgenda::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'           => User::inRandomOrder()->first()->id,
            'pimpinan_id'       => User::inRandomOrder()->first()->id,
            'notulis_id'        => User::inRandomOrder()->first()->id,
            'judul_rapat'       => $this->faker->sentence,
            'slug'              => $this->faker->slug,
            'waktu_mulai'       => Carbon::now('Asia/Jakarta'),
            'waktu_selesai'     => Carbon::now('Asia/Jakarta')->addHours(2),
            'deskripsi'         => $this->faker->text,
            'tempat'            => $this->faker->address,
            'status'            => $this->faker->randomElement(['CANCELED', 'SCHEDULED', 'COMPLETED', 'STARTED']),
            'lampiran'          => $this->faker->word,
            'zoom_link'         => $this->faker->url,
            'calendar_link'     => $this->faker->url
        ];
    }
}
