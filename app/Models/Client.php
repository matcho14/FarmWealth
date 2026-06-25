<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = ['name','phone','email','address','opening_balance','notes'];

    public function saleInvoices(): HasMany { return $this->hasMany(SaleInvoice::class); }

    public function journalLines() {
        return JournalEntryLine::where('account_type','client')->where('account_id',$this->id);
    }

    // رصيد العميل = رصيد افتتاحي + مجموع المدين (مبيعات) - مجموع الدائن (مقبوضات)
    public function getBalanceAttribute(): float {
        $debit  = $this->journalLines()->sum('debit');
        $credit = $this->journalLines()->sum('credit');
        return $this->opening_balance + $debit - $credit;
    }

    public function hasChartAccount(): bool {
        return \App\Models\ChartOfAccount::whereIn('linkable_type', [self::class, class_basename(self::class)])
            ->where('linkable_id', $this->id)
            ->exists();
    }

    public function chartAccount()
    {
        return $this->hasOne(\App\Models\ChartOfAccount::class, 'linkable_id', 'id')
            ->whereIn('linkable_type', [self::class, class_basename(self::class)]);
    }
}