<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ShedController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\AnnualReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TreasuryController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\SaleInvoiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/dashboard', [ShedController::class, 'dashboard'])->name('dashboard');

Route::get('chart-of-accounts', [ChartOfAccountController::class, 'index'])->name('chart-of-accounts.index');
Route::get('chart-of-accounts/create', [ChartOfAccountController::class, 'create'])->name('chart-of-accounts.create');
Route::post('chart-of-accounts', [ChartOfAccountController::class, 'store'])->name('chart-of-accounts.store');
Route::get('chart-of-accounts/{chartOfAccount}/edit', [ChartOfAccountController::class, 'edit'])->name('chart-of-accounts.edit');
Route::put('chart-of-accounts/{chartOfAccount}', [ChartOfAccountController::class, 'update'])->name('chart-of-accounts.update');
Route::delete('chart-of-accounts/{chartOfAccount}', [ChartOfAccountController::class, 'destroy'])->name('chart-of-accounts.destroy');
Route::get('chart-of-accounts/{chartOfAccount}/transactions', [ChartOfAccountController::class, 'showTransactions'])->name('chart-of-accounts.transactions');
Route::get('chart-of-accounts/accounts', [ChartOfAccountController::class, 'getAccounts'])->name('chart-of-accounts.accounts');

Route::get('/annual-report', [AnnualReportController::class, 'index'])->name('annual-report');

Route::get('/expense-report', [FinancialRecordController::class, 'expenseReport'])->name('expense-report');

Route::get('sheds/{shed}/cycles/create', [CycleController::class, 'create'])->name('cycles.create');
Route::post('sheds/{shed}/cycles', [CycleController::class, 'store'])->name('cycles.store');
Route::resource('cycles', CycleController::class)->except(['create', 'store']);
Route::get('cycles/{cycle}/mortality/edit', [CycleController::class, 'editMortality'])->name('cycles.editMortality');
Route::patch('cycles/{cycle}/mortality', [CycleController::class, 'updateMortality'])->name('cycles.updateMortality');
Route::get('cycles/{cycle}/close', [CycleController::class, 'closeCycleForm'])->name('cycles.close');
Route::get('cycles/{cycle}/close-cycle', [CycleController::class, 'closeCycleForm'])->name('cycles.closeCycleForm');
Route::patch('cycles/{cycle}/close', [CycleController::class, 'closeCycle'])->name('cycles.closeStore');
Route::get('cycles/{cycle}/create-record', [FinancialRecordController::class, 'create'])->name('cycles.createRecord');
Route::get('cycles/{cycle}/financial-records/create', [FinancialRecordController::class, 'create'])->name('financial-records.create');
Route::post('cycles/{cycle}/financial-records', [FinancialRecordController::class, 'store'])->name('financial-records.store');
Route::get('financial-records/{financialRecord}', [FinancialRecordController::class, 'show'])->name('financial-records.show');
Route::get('financial-records/{financialRecord}/edit', [FinancialRecordController::class, 'edit'])->name('financial-records.edit');
Route::delete('financial-records/{financialRecord}', [FinancialRecordController::class, 'destroy'])->name('financial-records.destroy');
Route::get('financial-records/cycles-by-shed', [FinancialRecordController::class, 'getCyclesByShed'])->name('financial-records.cycles-by-shed');

// Sales routes for cycles
Route::get('cycles/{cycle}/sales/create', [CycleController::class, 'createSales'])->name('cycles.createSales');
Route::post('cycles/{cycle}/sales', [CycleController::class, 'storeSales'])->name('cycles.storeSales');
Route::get('cycles/{cycle}/sales/{financialRecord}', [CycleController::class, 'showSale'])->name('cycles.sales.show');
Route::get('cycles/{cycle}/sales/{financialRecord}/edit', [CycleController::class, 'editSale'])->name('cycles.editSale');
Route::patch('cycles/{cycle}/sales/{financialRecord}', [CycleController::class, 'updateSale'])->name('cycles.updateSale');
Route::delete('cycles/{cycle}/sales/{financialRecord}', [CycleController::class, 'destroySale'])->name('cycles.destroySale');
Route::get('cycles/{cycle}/sales/{financialRecord}/payment', [CycleController::class, 'createPayment'])->name('cycles.sales.payment.create');
Route::post('cycles/{cycle}/sales/{financialRecord}/payment', [CycleController::class, 'storePayment'])->name('cycles.sales.payment.store');

// Mortality records routes
Route::get('cycles/{cycle}/mortality/{mortalityRecord}', [CycleController::class, 'showMortality'])->name('cycles.mortality.show');
Route::get('cycles/{cycle}/mortality/{mortalityRecord}/edit', [CycleController::class, 'editMortalityRecord'])->name('cycles.editMortalityRecord');
Route::patch('cycles/{cycle}/mortality/{mortalityRecord}', [CycleController::class, 'updateMortalityRecord'])->name('cycles.updateMortalityRecord');
Route::delete('cycles/{cycle}/mortality/{mortalityRecord}', [CycleController::class, 'destroyMortalityRecord'])->name('cycles.destroyMortalityRecord');

// Export cycle statement
Route::get('cycles/{cycle}/export-statement', [CycleController::class, 'exportStatement'])->name('cycles.export_statement');

Route::resource('suppliers', SupplierController::class);
Route::get('suppliers/{supplier}/export', [SupplierController::class, 'exportStatement'])->name('suppliers.export');
Route::resource('clients', ClientController::class);
Route::get('clients/{client}/export', [ClientController::class, 'exportStatement'])->name('clients.export');
Route::resource('items', ItemController::class);
Route::resource('sheds', ShedController::class);

Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

Route::resource('treasuries', TreasuryController::class);
Route::get('treasuries/{treasury}/export', [TreasuryController::class, 'exportStatement'])->name('treasuries.export');

Route::resource('journal-entries', JournalEntryController::class);

Route::resource('purchase-invoices', PurchaseInvoiceController::class);

Route::resource('sale-invoices', SaleInvoiceController::class);

Route::resource('units', UnitController::class)->only(['index', 'store', 'update', 'destroy']);

// Inventory routes with custom methods
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/inventory/transfer/create', [InventoryController::class, 'createTransfer'])->name('inventory.transfer.create');
Route::post('/inventory/transfer', [InventoryController::class, 'storeTransfer'])->name('inventory.transfer.store');
Route::get('/inventory/dispense/{cycle}/create', [InventoryController::class, 'createDispense'])->name('inventory.dispense.create');
Route::post('/inventory/dispense/{cycle}', [InventoryController::class, 'storeDispense'])->name('inventory.dispense.store');
Route::get('/inventory/export', [InventoryController::class, 'exportInventory'])->name('inventory.export');