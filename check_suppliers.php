<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Supplier;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;

echo "=== قائمة الموردين ===\n";
$suppliers = Supplier::all(['id', 'name']);
foreach ($suppliers as $s) {
    echo "ID: {$s->id} | الاسم: {$s->name}\n";
}

echo "\n=== حسابات الشجرة المرتبطة بموردين ===\n";
$linked = ChartOfAccount::whereNotNull('linkable_type')->get(['id','code','name','linkable_type','linkable_id']);
foreach ($linked as $a) {
    echo "ID: {$a->id} | Code: {$a->code} | Name: {$a->name} | Type: {$a->linkable_type} | LinkID: {$a->linkable_id}\n";
}

echo "\n=== قيود المورد 'اسلام هايدا' في journal_entry_lines ===\n";
// ابحث عن مورد باسم اسلام
$islam = Supplier::where('name', 'like', '%هايدا%')->orWhere('name', 'like', '%اسلام%')->first();
if ($islam) {
    echo "وجدنا المورد: ID={$islam->id} Name={$islam->name}\n";
    $lines = JournalEntryLine::where('account_type', 'supplier')->where('account_id', $islam->id)->get(['id','journal_entry_id','debit','credit','description']);
    echo "عدد القيود: " . $lines->count() . "\n";
    foreach ($lines as $l) {
        echo "  Line ID:{$l->id} JE:{$l->journal_entry_id} Debit:{$l->debit} Credit:{$l->credit} Desc:{$l->description}\n";
    }
} else {
    echo "لم يُعثر على مورد باسم اسلام هايدا!\n";
    echo "جميع أسماء الموردين:\n";
    foreach ($suppliers as $s) {
        echo "  - ID:{$s->id} Name:{$s->name}\n";
    }
}

echo "\n=== حساب 'مورد علف' في الشجرة ===\n";
$morardAlaf = Supplier::where('name', 'like', '%علف%')->first();
if ($morardAlaf) {
    echo "مورد علف: ID={$morardAlaf->id} Name={$morardAlaf->name}\n";
    $chart = ChartOfAccount::where('linkable_id', $morardAlaf->id)
        ->whereIn('linkable_type', ['App\\Models\\Supplier', 'Supplier'])
        ->first();
    if ($chart) {
        echo "حساب الشجرة: ID={$chart->id} Code={$chart->code} Name={$chart->name}\n";
    } else {
        echo "لا يوجد حساب شجرة مرتبط بمورد علف\n";
    }
    
    $lines = JournalEntryLine::where('account_type', 'supplier')->where('account_id', $morardAlaf->id)->get();
    echo "قيوده في journal_entry_lines: " . $lines->count() . "\n";
}
