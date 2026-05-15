<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(["created_by", 'owner_id', "name", "slug", "subdomain", "database"])]
class Tenant extends Model {
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
