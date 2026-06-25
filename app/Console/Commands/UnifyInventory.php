<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicine;
use App\Models\MedicineEntry;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class UnifyInventory extends Command
{
    protected $signature = 'inventory:unify';
    protected $description = 'Migrate data from old medicine system to new items system';

    public function handle()
    {
        $this->info('Starting inventory unification...');

        DB::transaction(function () {
            $medicines = Medicine::all();

            foreach ($medicines as $med) {
                // Check if item already exists
                $item = Item::where('name', $med->name)->first();

                if (!$item) {
                    $totalStock = MedicineEntry::where('medicine_id', $med->id)->sum('remaining_quantity');
                    $lastPrice = MedicineEntry::where('medicine_id', $med->id)->latest('entry_date')->value('price') ?? 0;

                    Item::create([
                        'name' => $med->name,
                        'unit' => $med->unit ?? 'وحدة',
                        'quantity_in_stock' => $totalStock,
                        'last_purchase_price' => $lastPrice,
                        'notes' => $med->description . ' (تم النقل من النظام القديم)',
                    ]);

                    $this->info("Migrated: {$med->name} with stock: {$totalStock}");
                } else {
                    $this->warn("Item already exists: {$med->name}. Skipping.");
                }
            }
        });

        $this->info('Inventory unification completed.');
    }
}
