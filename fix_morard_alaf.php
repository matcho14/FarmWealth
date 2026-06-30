<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;

$chart = ChartOfAccount::where('name', 'مورد علف')->first();

if ($chart) {
    echo "Found Chart: ID={$chart->id}, LinkID={$chart->linkable_id}\n";
    
    // Check if it has any transactions
    $count = JournalEntryLine::where('account_type', 'chart_of_account')->where('account_id', $chart->id)->count();
    echo "Transactions directly linked: {$count}\n";
    
    if ($count == 0) {
        echo "Deleting orphaned ChartOfAccount 'مورد علف'...\n";
        $chart->delete();
        echo "Deleted successfully.\n";
    } else {
        echo "Cannot delete because it has transactions. Clearing linkable_id instead...\n";
        $chart->linkable_type = null;
        $chart->linkable_id = null;
        $chart->save();
        echo "Cleared linkable successfully.\n";
    }
} else {
    echo "ChartOfAccount 'مورد علف' not found.\n";
}
