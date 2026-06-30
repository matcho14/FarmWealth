<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Supplier;
use App\Models\ChartOfAccount;

$suppliers = Supplier::all();
foreach ($suppliers as $sup) {
    $acc = ChartOfAccount::where('name', $sup->name)
        ->whereIn('linkable_type', ['App\\Models\\Supplier', 'Supplier'])
        ->first();
        
    if ($acc && $acc->linkable_id != $sup->id) {
        echo "Fixing {$sup->name}: linkable_id from {$acc->linkable_id} to {$sup->id}\n";
        $acc->linkable_id = $sup->id;
        $acc->save();
    }
}
echo "Done.\n";
