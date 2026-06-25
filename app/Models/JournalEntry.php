<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    protected $fillable = ['entry_number','entry_date','description','reference_type','reference_id','notes'];
    protected $casts = ['entry_date' => 'date'];

    public function lines(): HasMany { return $this->hasMany(JournalEntryLine::class); }

    public static function generateNumber(): string {
        $year  = now()->format('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'JE-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
