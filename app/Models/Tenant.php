<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/*
 - this model will be the primary tenant model for this single database system
 - this model will be referenced in each other model so we could use the tenant_id on other tables to achieve data isolation
 */

#[Fillable(['name'])]
class Tenant extends Model {
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;
    public function users(): HasMany {
        return $this->hasMany(User::class);
    }
    public function articles(): HasMany {
        return $this->hasMany(Article::class);
    }
}
