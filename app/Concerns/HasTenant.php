<?php

namespace App\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTenant {
    public function tenant(): BelongsTo {
        return $this->belongsTo(Tenant::class);
    }
    #[Scope]
    public function tenanting(Builder $query) {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }
}
