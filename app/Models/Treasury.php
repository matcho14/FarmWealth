<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treasury extends Model
{
    protected $fillable = ['name','opening_balance','notes'];

    public function purchaseInvoices(): HasMany { return $this->hasMany(PurchaseInvoice::class); }

    public function journalLines() {
        return JournalEntryLine::where('account_type','treasury')->where('account_id',$this->id);
    }

    // رصيد الخزنة = رصيد افتتاحي + مجموع المدين (وارد) - مجموع الدائن (صادر)
    public function getBalanceAttribute(): float {
        $debit  = $this->journalLines()->sum('debit');
        $credit = $this->journalLines()->sum('credit');
        return $this->opening_balance + $debit - $credit;
    }
}
