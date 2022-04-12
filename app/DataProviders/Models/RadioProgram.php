<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadioProgram extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'radio_station_id', 'name', 'email'
    ];

    /**
     * ラジオ局用リレーション
     * 
     * @return BelongsTo
     */
    public function radioStation(): BelongsTo
    {
        return $this->belongsTo(RadioStation::class);
    }
}
