<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutgoingLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Ambil semua user_id dari tabel users
         $userIds = \App\Models\User::pluck('id')->toArray();

         for ($i = 1; $i <= 15; $i++) {
             $subject = "Surat Penting Ke-$i";
             $recipient = "Penerima $i";
             $letterNumber = "OUT/2025/0$i";
             $date = Carbon::now()->subDays(rand(1, 30))->format('Y-m-d');
             $slug = Str::slug($letterNumber . '-' . Str::random(5));
             $filePath = "surat/outgoing_$i.pdf";
 
             DB::table('outgoing_letters')->insert([
                 'recipient'     => $recipient,
                 'slug'          => $slug,
                 'letter_number' => $letterNumber,
                 'letter_date'   => $date,
                 'subject'       => $subject,
                 'file_path'     => $filePath,
                 'user_id'       => $userIds[array_rand($userIds)],
                 'created_at'    => now(),
                 'updated_at'    => now(),
             ]);
         }
    }
}
