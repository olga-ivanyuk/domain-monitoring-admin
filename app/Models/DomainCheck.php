<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class DomainCheck extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'domain_id',
        'checked_at',
        'status',
        'status_code',
        'response_time',
        'error',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'domain_id' => 'integer',
            'checked_at' => 'datetime',
            'status' => 'boolean',
            'status_code' => 'integer',
            'response_time' => 'integer',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
