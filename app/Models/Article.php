<?php

namespace App\Models;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(["tenant_id", "user_id", "title", "content"])]
class Article extends Model {
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, BelongsToTenant;

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
    #[Scope]
    public function myTenant(Builder $query) {
        return $query->where('tenant_id', auth()->user()->tenant_id);
    }
}
