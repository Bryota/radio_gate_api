<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMessageCount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'radio_program_id',
        'listener_my_program_id',
        'listener_id',
        'post_counts'
    ];

    /**
     * ラジオ番組用リレーション
     * 
     * @return BelongsTo
     */
    public function RadioProgram(): BelongsTo
    {
        return $this->belongsTo(RadioProgram::class);
    }

    /**
     * マイ番組用リレーション
     * 
     * @return BelongsTo
     */
    public function ListenerMyProgram(): BelongsTo
    {
        return $this->belongsTo(ListenerMyProgram::class);
    }

    /**
     * リスナー用リレーション
     * 
     * @return BelongsTo
     */
    public function Listener(): BelongsTo
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
    public function scopeListenerIdEqual(Builder $query, int $listener_id): Builder
    {
        return $query->where('listener_id', $listener_id);
    }
}
