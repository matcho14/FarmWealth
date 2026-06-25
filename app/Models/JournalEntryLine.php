<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryLine extends Model
{
    protected $fillable = ['journal_entry_id','account_type','account_id','debit','credit','description'];

    public function journalEntry(): BelongsTo { return $this->belongsTo(JournalEntry::class); }

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    // الحصول على اسم الحساب
    public function getAccountNameAttribute(): string {
        if ($this->account_type === 'chart_of_account') {
            return \App\Models\ChartOfAccount::find($this->account_id)?->name ?? 'محذوف';
        }
        if ($this->account_type === 'supplier') {
            return Supplier::find($this->account_id)?->name ?? 'محذوف';
        }
        if ($this->account_type === 'client') {
            return Client::find($this->account_id)?->name ?? 'محذوف';
        }
        if ($this->account_type === 'treasury') {
            return Treasury::find($this->account_id)?->name ?? 'محذوف';
        }
        if ($this->account_type === 'cycle') {
            return Cycle::find($this->account_id)?->name ?? 'محذوف';
        }
        if ($this->account_type === 'sales') {
            return 'حساب المبيعات';
        }
        return '-';
    }
}
