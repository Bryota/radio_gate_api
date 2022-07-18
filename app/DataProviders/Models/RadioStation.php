<?php

namespace App\DataProviders\Models;

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
}
