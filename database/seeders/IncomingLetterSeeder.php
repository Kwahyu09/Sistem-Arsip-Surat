<?php

namespace Database\Seeders;

use App\Models\IncomingLetter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class IncomingLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user dengan role 'staff_bidang'
        $staffBidangUsers = User::where('role', 'staff_bidang')->get();

        if ($staffBidangUsers->isEmpty()) {
            $this->command->warn('Tidak ada user dengan role staff_bidang, seeder IncomingLetter dilewati.');
            return;
        }

        // Buat 10 surat masuk contoh
        for ($i = 0; $i < 10; $i++) {
            $sender = fake()->company();
            $subject = fake()->sentence(3);

            IncomingLetter::create([
                'sender' => $sender,
                'slug' => Str::slug($sender) . '-' . Str::random(5),
                'letter_number' => fake()->bothify('SM-####/ABC'),
                'letter_date' => fake()->date(),
                'subject' => $subject,
                'disposition' => fake()->randomElement(['known', 'actioned', 'archived']),
                'file_path' => null, // atau kalau mau isi contoh file bisa pakai fake()->word().'.pdf'
                'read' => fake()->boolean(),
                'user_id' => $staffBidangUsers->random()->id,
            ]);
        }
    }
}
