<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadioStation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type'
    ];

    /**
     * ラジオ番組用リレーション
     * 
     * @return HasMany
     */
    public function radioPrograms(): HasMany
    {
        return $this->hasMany(RadioProgram::class);
    }

    /**
     * 絞り込み・キーワード検索
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|string|null $search_params 検索パラメーター
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, array|string|null $search_params): Builder
    {
        if (!empty($search_params['type'])) $query->where('type', $search_params['type']);
        if (!empty($search_params['keyword'])) $query->where('name', 'LIKE', '%' . $search_params['keyword'] . '%');

        return $query;
    }
}
