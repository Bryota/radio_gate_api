<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestFunction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'listener_id',
        'name',
        'detail',
        'point',
    ];

    /**
     * リスナーリクエスト機能投稿用リレーション
     * 
     * @return HasMany
     */
    public function RequestFunctionListenerSubmits(): HasMany
    {
        return $this->HasMany(RequestFunctionListenerSubmit::class);
    }

    /**
     * リスナー用リレーション
     * 
     * @return BelongsTo
     */
    public function listener(): BelongsTo
    {
        return $this->belongsTo(Listener::class);
    }

    /**
     * リスナーID一致クエリのスコープ
     * 
     * @param Builder $query クエリビルダ
     * @param int $listener_id リスナーID
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeListenerIdEqual($query, $listener_id): Builder
    {
        return $query->where('listener_id', $listener_id);
    }
}
