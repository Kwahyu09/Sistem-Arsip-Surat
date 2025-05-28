<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OutgoingLetter extends Model
{
    use HasFactory;

    protected $table = 'outgoing_letters';

    protected $fillable = [
        'recipient',
        'slug',
        'letter_number',
        'letter_date',
        'subject',
        'file_path',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($letter) {
            $letter->slug = Str::slug(strtolower($letter->recipient . now()->timestamp . Str::random(2)));
        });

        static::updating(function ($letter) {
            $letter->slug = Str::slug(strtolower($letter->recipient . now()->timestamp . Str::random(2)));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
