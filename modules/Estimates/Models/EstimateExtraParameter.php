<?php

namespace Modules\Estimates\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstimateExtraParameter extends Model
{
    public $table = 'estimates_extra_parameters';

    protected $fillable = ['company_id', 'document_id', 'expire_at'];

    protected $casts = [
        'expire_at' => 'datetime'
    ];

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class);
    }

    public function scopeExpire($query, $date)
    {
        return $query->whereDate('expire_at', '=', $date);
    }
}
