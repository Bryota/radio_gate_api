<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramCorner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'radio_program_id'
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
     * ラジオ番組用リレーション
     * 
     * @return BelongsTo
     */
    public function radioProgram(): BelongsTo
    {
        return $this->belongsTo(RadioProgram::class);
    }
}
