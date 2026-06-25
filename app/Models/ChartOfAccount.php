<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Supplier;
use App\Models\Client;

class ChartOfAccount extends Model
{
    protected $fillable = [
        'code',
        'name',
        'parent_id',
        'account_type',
        'is_parent',
        'is_active',
        'opening_balance',
        'shed_id',
        'linkable_type',
        'linkable_id',
    ];

    protected $casts = [
        'is_parent' => 'boolean',
        'is_active' => 'boolean',
        'opening_balance' => 'decimal:2',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function linkable()
    {
        $model = in_array($this->linkable_type, [Supplier::class, class_basename(Supplier::class)])
            ? Supplier::class
            : Client::class;
        return $this->belongsTo($model, 'linkable_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id')->orderBy('code');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('account_type', $type);
    }

    public function getTreeAttribute(): array
    {
        return $this->buildTree($this->roots()->get());
    }

    private function buildTree($nodes, $parentId = null): array
    {
        $result = [];
        foreach ($nodes as $node) {
            if ($node->parent_id == $parentId) {
                $children = $this->buildTree($nodes, $node->id);
                $node->setRelation('children', collect($children));
                $result[] = $node;
            }
        }
        return $result;
    }

    public function getFullCodeAttribute(): string
    {
        $ancestors = [];
        $current = $this->parent;
        while ($current) {
            array_unshift($ancestors, $current->code);
            $current = $current->parent;
        }
        return implode('.', array_merge($ancestors, [$this->code]));
    }

    public function getLevelAttribute(): int
    {
        $level = 0;
        $current = $this->parent;
        while ($current) {
            $level++;
            $current = $current->parent;
        }
        return $level;
    }

    public function getCurrentBalanceAttribute(): float
    {
        $base = (float) ($this->opening_balance ?? 0);

        $line = \App\Models\JournalEntryLine::where('account_type', 'chart_of_account')
            ->where('account_id', $this->id)
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();

        $debit = (float) ($line->total_debit ?? 0);
        $credit = (float) ($line->total_credit ?? 0);

        return $base + $debit - $credit;
    }
}