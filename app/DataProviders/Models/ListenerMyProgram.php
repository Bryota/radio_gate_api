<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListenerMyProgram extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_name',
        'email',
        'listener_id'
    ];

    /**
     * マイ番組コーナー用リレーション
     * 
     * @return HasMany
     */
    public function MyProgramCorners(): HasMany
    {
        return $this->hasMany(MyProgramCorner::class);
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
     * リスナー用リレーション
     * 
     * @return BelongsTo
     */
    public function listener(): BelongsTo
    {
        return $this->belongsTo(Listener::class);
    }
}
