<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Supplier;
use App\Models\ChartOfAccount;

echo "Checking for orphaned ChartOfAccount records...\n";

$charts = ChartOfAccount::where('linkable_type', 'like', '%Supplier%')->get();

foreach ($charts as $chart) {
    $supplier = Supplier::find($chart->linkable_id);
    if (!$supplier) {
        echo "Found orphaned ChartOfAccount: ID={$chart->id}, Name={$chart->name}, LinkID={$chart->linkable_id}\n";
        echo "Clearing linkable_type and linkable_id...\n";
        $chart->linkable_type = null;
        $chart->linkable_id = null;
        $chart->save();
        echo "Done.\n";
    }
}
echo "Script finished.\n";
