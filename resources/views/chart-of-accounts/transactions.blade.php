@extends('components.master')
@section('title', 'عمليات الحساب')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1><i class="fa-solid fa-chart-bar me-2"></i>عمليات الحساب</h1>
        <p>عرض العمليات المالية لحساب: <strong>{{ $chartOfAccount->name }}</strong> ({{ $chartOfAccount->code }})</p>
    </div>
    <div>
        <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-right me-1"></i>عودة لشجرة الحسابات
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>رقم القيد</th>
                        <th>التاريخ</th>
                        <th>نوع العملية</th>
                        <th>رقم الفاتورة / المرجع</th>
                        <th>المورد / العميل</th>
                        <th>الأصناف</th>
                        <th>العنبر</th>
                        <th>الدورة</th>
                        <th>الدور</th>
                        <th>البيان</th>
                        <th>مدين</th>
                        <th>دائن</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                    @endphp
                    @forelse($transactions as $transaction)
                        @php
                            $refType = $transaction->reference_type ?? '';
                            $refId   = $transaction->reference_id ?? null;

                            // --- فاتورة شراء أو مبيعات ---
                            $invoice  = null;
                            $itemNames = [];
                            $shedNames = [];
                            $cycleLabels = [];
                            $floorNumbers = [];

                            if ($refType === 'purchase_invoice' && $refId) {
                                $invoice = $purchaseInvoices[$refId] ?? null;
                                if ($invoice) {
                                    foreach ($invoice->items as $invItem) {
                                        $catIcon = match($invItem->item->category ?? '') {
                                            'feed'     => '🌾',
                                            'medicine' => '💊',
                                            'other'    => '📦',
                                            default    => '•'
                                        };
                                        $itemNames[] = $catIcon . ' ' . ($invItem->item->name ?? '-')
                                            . ' (' . number_format($invItem->quantity, 2) . ')';

                                        // جلب الدورات من cycle_dispensations لهذا الصنف
                                        $disps = $dispensationsByItem[$invItem->item_id] ?? [];
                                        foreach ($disps as $d) {
                                            if ($d->cycle && $d->cycle->shed) {
                                                $shedNames[]    = $d->cycle->shed->name;
                                                $cycleLabels[]  = 'دورة #' . $d->cycle_id
                                                    . ($d->cycle->start_date
                                                        ? ' (' . $d->cycle->start_date->format('Y-m-d') . ')'
                                                        : '');
                                                $floorNumbers[] = 'دور ' . $d->floor_number;
                                            } elseif ($d->shed) {
                                                $shedNames[] = $d->shed->name;
                                            }
                                        }
                                        $shedNames   = array_unique($shedNames);
                                        $cycleLabels = array_unique($cycleLabels);
                                        $floorNumbers = array_unique($floorNumbers);
                                    }
                                }
                            } elseif ($refType === 'sale_invoice' && $refId) {
                                $invoice = $saleInvoices[$refId] ?? null;
                                if ($invoice) {
                                    foreach ($invoice->items as $invItem) {
                                        $itemNames[] = '• ' . ($invItem->item->name ?? '-')
                                            . ' (' . number_format($invItem->quantity, 2) . ')';
                                    }
                                    if ($invoice->shed) {
                                        $shedNames[] = $invoice->shed->name;
                                    }
                                    if ($invoice->cycle) {
                                        $cycleLabels[] = 'دورة #' . $invoice->cycle_id
                                            . ($invoice->cycle->start_date
                                                ? ' (' . $invoice->cycle->start_date->format('Y-m-d') . ')'
                                                : '');
                                    }
                                }
                            }

                            // --- سجل مالي (صرف علف/دواء على دورة) ---
                            $financialRecord = null;
                            if ($refType === 'financial_record' && $refId) {
                                $financialRecord = $financialRecords[$refId] ?? null;
                                if ($financialRecord) {
                                    // الصنف من البيان
                                    $itemNames = [$transaction->entry_description ?? $transaction->description ?? '-'];
                                    // العنبر
                                    if ($financialRecord->cycle && $financialRecord->cycle->shed) {
                                        $shedNames   = [$financialRecord->cycle->shed->name];
                                        $cycleLabels = ['دورة #' . $financialRecord->cycle_id
                                            . ($financialRecord->cycle->start_date
                                                ? ' (' . $financialRecord->cycle->start_date->format('Y-m-d') . ')'
                                                : '')];
                                    } elseif ($financialRecord->shed) {
                                        $shedNames = [$financialRecord->shed->name];
                                    }
                                    if ($financialRecord->floor_number) {
                                        $floorNumbers = ['دور ' . $financialRecord->floor_number];
                                    }
                                }
                            }

                            // label نوع العملية
                            $opLabel = match($refType) {
                                'purchase_invoice' => ['text' => 'شراء', 'class' => 'bg-primary'],
                                'sale_invoice'     => ['text' => 'مبيعات', 'class' => 'bg-info text-dark'],
                                'financial_record' => ['text' => 'صرف/مالي', 'class' => 'bg-success'],
                                'manual'           => ['text' => 'يدوي', 'class' => 'bg-secondary'],
                                default            => ['text' => $refType ?: 'قيد', 'class' => 'bg-secondary'],
                            };
                        @endphp
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td><small>{{ $transaction->entry_number ?? '-' }}</small></td>
                            <td>{{ \Carbon\Carbon::parse($transaction->entry_date)->format('Y-m-d') }}</td>

                            {{-- نوع العملية --}}
                            <td>
                                <span class="badge {{ $opLabel['class'] }}">{{ $opLabel['text'] }}</span>
                            </td>

                            {{-- رقم الفاتورة / المرجع --}}
                            <td>
                                @if($invoice)
                                    <span class="badge" style="background:#7c3aed;">{{ $invoice->invoice_number }}</span>
                                    @php
                                        $st = match($invoice->payment_status) {
                                            'paid'    => ['مدفوع', 'bg-success'],
                                            'partial' => ['جزئي', 'bg-warning text-dark'],
                                            'unpaid'  => ['غير مدفوع', 'bg-danger'],
                                            default   => [$invoice->payment_status, 'bg-secondary'],
                                        };
                                    @endphp
                                    <span class="badge {{ $st[1] }} d-block mt-1">{{ $st[0] }}</span>
                                @elseif($financialRecord)
                                    <span class="text-muted" style="font-size:0.8rem;">سجل #{{ $refId }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- المورد / العميل --}}
                            <td class="text-start">
                                @if($refType === 'purchase_invoice' && $invoice && $invoice->supplier)
                                    <span class="text-primary"><i class="fas fa-truck me-1"></i>{{ $invoice->supplier->name }}</span>
                                @elseif($refType === 'sale_invoice' && $invoice && $invoice->client)
                                    <span class="text-info"><i class="fas fa-user me-1"></i>{{ $invoice->client->name }}</span>
                                @elseif($financialRecord && $financialRecord->client)
                                    <span class="text-info"><i class="fas fa-user me-1"></i>{{ $financialRecord->client->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- الأصناف --}}
                            <td class="text-start">
                                @if(count($itemNames) > 0)
                                    @foreach($itemNames as $itm)
                                        <div style="font-size:0.82rem; white-space:nowrap;">{{ $itm }}</div>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- العنبر --}}
                            <td>
                                @if(count($shedNames) > 0)
                                    @foreach(array_unique($shedNames) as $sn)
                                        <span class="badge bg-warning text-dark d-block mb-1" style="white-space:nowrap;">
                                            <i class="fa-solid fa-house me-1"></i>{{ $sn }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- الدورة --}}
                            <td>
                                @if(count($cycleLabels) > 0)
                                    @foreach(array_unique($cycleLabels) as $cl)
                                        <span class="badge bg-info text-dark d-block mb-1" style="white-space:nowrap;">
                                            <i class="fa-solid fa-rotate me-1"></i>{{ $cl }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- الدور --}}
                            <td>
                                @if(count($floorNumbers) > 0)
                                    @foreach(array_unique($floorNumbers) as $fl)
                                        <span class="badge bg-secondary d-block mb-1">{{ $fl }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- البيان --}}
                            <td class="text-start" style="max-width:180px; font-size:0.85rem;">
                                {{ $transaction->entry_description ?? $transaction->description ?? 'بدون بيان' }}
                            </td>

                            {{-- مدين --}}
                            <td class="text-success fw-bold">
                                {{ $transaction->debit > 0 ? format_number($transaction->debit, 2) : '-' }}
                            </td>

                            {{-- دائن --}}
                            <td class="text-danger fw-bold">
                                {{ $transaction->credit > 0 ? format_number($transaction->credit, 2) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center py-4 text-muted">لا توجد عمليات مسجلة لهذا الحساب</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
