<?php

namespace App\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant {
    public function Tenant(): BelongsTo {
        return $this->belongsTo(Tenant::class);
    }
}
