<?php

namespace App\Models;

use App\Concerns\HasTenant;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(TenantScope::class)]
#[Fillable(["tenant_id", "user_id", "title", "content"])]
class Article extends Model {
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, HasTenant;

    public static function booted() {
        static::creating(function ($model) {
            if (!$model->tenant_id && auth()->check()) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
