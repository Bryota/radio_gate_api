<?php

namespace App\DataProviders\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
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

    /**
     * 絞り込み検索
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|string|null $search_params 検索パラメーター
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, array|string|null $search_params): Builder
    {
        if (!empty($search_params['day'])) $query->where('day', $search_params['day']);

        return $query;
    }
}
