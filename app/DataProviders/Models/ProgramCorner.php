<?php

namespace App\DataProviders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'name'
    ];

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
