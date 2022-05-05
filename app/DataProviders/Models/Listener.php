<?php

namespace App\DataProviders\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listener extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'radio_name',
        'post_code',
        'prefecture',
        'city',
        'house_number',
        'building',
        'room_number',
        'tel',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 投稿テンプレート用リレーション
     * 
     * @return HasMany
     */
    public function messageTemplates(): HasMany
    {
        return $this->hasMany(MessageTemplate::class);
    }

    /**
     * マイ番組用リレーション
     * 
     * @return HasMany
     */
    public function ListenerMyPrograms(): HasMany
    {
        return $this->hasMany(ListenerMyProgram::class);
    }

    /**
     * リクエスト機能リレーション
     * 
     * @return HasMany
     */
    public function RequestFunctions(): HasMany
    {
        return $this->hasMany(RequestFunction::class);
    }

    /**
     * リスナーリクエスト機能投稿用リレーション
     * 
     * @return hasMany
     */
    public function RequestFunctionListenerSubmits(): hasMany
    {
        return $this->hasMany(RequestFunctionListenerSubmit::class);
    }

    /**
     * リスナーID一致クエリのスコープ
     * 
     * @param Builder $query クエリビルダ
     * @param int $listener_id リスナーID
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeListenerIdEqual($query, int $listener_id): Builder
    {
        return $query->where('id', $listener_id);
    }
}
