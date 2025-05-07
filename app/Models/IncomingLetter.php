<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IncomingLetter extends Model
{
    use HasFactory;

    protected $table = 'incoming_letter';

    protected $fillable = [
        'sender', 'slug', 'letter_number', 'letter_date',
        'subject', 'disposition', 'file_path', 'read', 'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($letter) {
            $letter->slug = Str::slug(strtolower($letter->sender . now()->timestamp . Str::random(2)));
        });

        static::updating(function ($letter) {
            $letter->slug = Str::slug(strtolower($letter->sender . now()->timestamp . Str::random(2)));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
