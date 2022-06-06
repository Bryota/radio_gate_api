<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListenerMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'radio_program_id',
        'program_corner_id',
        'listener_my_program_id',
        'my_program_corner_id',
        'listener_id',
        'subject',
        'content',
        'radio_name',
        'listener_info_flag',
        'tel_flag',
        'posted_at'
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
     * ラジオ番組コーナー用リレーション
     * 
     * @return BelongsTo
     */
    public function ProgramCorner(): BelongsTo
    {
        return $this->belongsTo(ProgramCorner::class);
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
     * マイ番組コーナー用リレーション
     * 
     * @return BelongsTo
     */
    public function MyProgramCorner(): BelongsTo
    {
        return $this->belongsTo(MyProgramCorner::class);
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
