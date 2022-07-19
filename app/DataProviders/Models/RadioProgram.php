<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadioProgram extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'radio_station_id', 'name', 'email', 'day'
    ];

    /**
     * 番組内コーナー用リレーション
     * 
     * @return HasMany
     */
    public function ProgramCorners(): HasMany
    {
        return $this->hasMany(ProgramCorner::class);
    }

    /**
     * 投稿メッセージ用リレーション
     * 
     * @return HasMany
     */
    public function ListenerMessages(): HasMany
    {
        return $this->hasMany(ListenerMessage::class);
    }

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
