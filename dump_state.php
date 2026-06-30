<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Supplier;
use App\Models\ChartOfAccount;

echo "=== Suppliers ===\n";
foreach(Supplier::all() as $s) {
    echo "ID: {$s->id}, Name: {$s->name}\n";
}

echo "\n=== Chart Of Accounts (Suppliers) ===\n";
foreach(ChartOfAccount::where('linkable_type', 'like', '%Supplier%')->get() as $c) {
    echo "Chart ID: {$c->id}, Code: {$c->code}, Name: {$c->name}, LinkID: {$c->linkable_id}\n";
}
