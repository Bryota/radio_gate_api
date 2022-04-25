<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestFunctionListenerSubmit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'listener_id',
        'request_function_id',
        'point',
    ];

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
     * リクエスト機能用リレーション
     * 
     * @return BelongsTo
     */
    public function RequestFunction(): BelongsTo
    {
        return $this->belongsTo(RequestFunction::class);
    }
}
