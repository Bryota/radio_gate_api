<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyProgramCorner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'listener_my_program_id'
    ];

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
     * マイ番組用リレーション
     * 
     * @return BelongsTo
     */
    public function ListenerMyProgram(): BelongsTo
    {
        return $this->belongsTo(ListenerMyProgram::class);
    }
}
