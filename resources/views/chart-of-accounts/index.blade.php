@extends('components.master')
@section('title', 'شجرة الحسابات')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1><i class="fa-solid fa-sitemap me-2"></i>شجرة الحسابات</h1>
        <p>إدارة الهيكل المحاسبي الكامل للحسابات</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>إضافة حساب جديد
        </a>
        <button class="btn btn-outline-secondary" onclick="expandAll()">
            <i class="fa-solid fa-expand me-1"></i>توسيع الكل
        </button>
        <button class="btn btn-outline-secondary" onclick="collapseAll()">
            <i class="fa-solid fa-compress me-1"></i>طي الكل
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center" id="chartTable">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th style="width: 150px">الرمز</th>
                        <th>اسم الحساب</th>
                        <th style="width: 150px">النوع</th>
                        <th style="width: 130px">الرصيد</th>
                        <th style="width: 100px">الحالة</th>
                        <th style="width: 240px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                        $typeLabels = [
                            'asset_current' => 'أصول متداولة',
                            'asset_fixed' => 'أصول ثابتة',
                            'liability_current' => 'خصوم متداولة',
                            'liability_long' => 'خصوم طويلة الأجل',
                            'equity' => 'حقوق ملكية',
                            'revenue' => 'إيرادات',
                            'expense' => 'مصاريف',
                        ];
                        $colors = [
                            'asset_current' => ['bg' => '#e8f5e9', 'text' => '#2e7d32'],
                            'asset_fixed' => ['bg' => '#e8f5e9', 'text' => '#2e7d32'],
                            'liability_current' => ['bg' => '#ffebee', 'text' => '#c62828'],
                            'liability_long' => ['bg' => '#ffebee', 'text' => '#c62828'],
                            'equity' => ['bg' => '#fff3e0', 'text' => '#ef6c00'],
                            'revenue' => ['bg' => '#e3f2fd', 'text' => '#1565c0'],
                            'expense' => ['bg' => '#fce4ec', 'text' => '#ad1457'],
                        ];
                    @endphp

                    @foreach($rows as $row)
                        @php
                            $rowCounter = $counter++;
                            $acc = $row['account'];
                            $level = $row['level'];
                            $rowType = $row['type'];
                            $indent = $level * 28;

                            if ($rowType === 'account') {
                                $typeKey = $acc->account_type ?? 'expense';
                                $color = $colors[$typeKey] ?? ['bg' => '#f5f5f5', 'text' => '#424242'];
                                $bgColor = $color['bg'];
                                $textColor = $color['text'];
                                $code = $acc->code;
                                $name = $acc->name ?? 'غير مسمى';
                                $typeName = $typeLabels[$typeKey] ?? $typeKey;
                                $balance = $acc->current_balance ?? ($acc->opening_balance ?? 0);
                                $isActive = $acc->is_active ?? true;
                                $hasChildren = $row['hasChildren'] ?? false;
                                $shed = $acc->shed ?? null;
                                $isDynamic = $row['isDynamic'] ?? false;
                                $rowClass = 'tree-row';
                                $borderWidth = '3px';
                            } elseif ($rowType === 'client') {
                                $bgColor = '#e8f5e9';
                                $textColor = '#2e7d32';
                                $code = optional($acc->chartAccount)->code ?? 'عميل';
                                $name = $acc->name ?? 'غير مسمى';
                                $typeName = 'عميل';
                                $balance = $acc->balance ?? 0;
                                $isActive = true;
                                $hasChildren = false;
                                $shed = null;
                                $isDynamic = false;
                                $rowClass = 'tree-child';
                                $borderWidth = '2px';
                            } elseif ($rowType === 'supplier') {
                                $bgColor = '#ffebee';
                                $textColor = '#c62828';
                                $code = optional($acc->chartAccount)->code ?? 'مورد';
                                $name = $acc->name ?? 'غير مسمى';
                                $typeName = 'مورد';
                                $balance = $acc->balance ?? 0;
                                $isActive = true;
                                $hasChildren = false;
                                $shed = null;
                                $isDynamic = false;
                                $rowClass = 'tree-child';
                                $borderWidth = '2px';
                            }
                        @endphp

                        <tr class="{{ $rowClass }}" style="background-color: {{ $bgColor }}20; border-right: {{ $borderWidth }} solid {{ $textColor }};">
                            <td>{{ $rowCounter }}</td>
                            <td class="fw-bold" style="color: {{ $textColor }};">{{ $code }}</td>
                            <td class="text-start">
                                <div style="padding-right: {{ $indent }}px; display: flex; align-items: center; gap: 8px;">
                                    @if($hasChildren)
                                        <button type="button" class="btn btn-sm btn-link p-0 toggle-tree" onclick="toggleTree(this)">
                                            <i class="toggle-icon fa-solid fa-chevron-down text-primary"></i>
                                        </button>
                                    @else
                                        <span style="width: 24px;"></span>
                                    @endif
                                    <span class="fw-bold">{{ $name }}</span>
                                    @if($isDynamic)
                                        <span class="badge bg-info">ديناميكي</span>
                                    @endif
                                    @if($shed)
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-house me-1"></i>{{ $shed->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td><span class="badge" style="background-color: {{ $textColor }}; color: white;">{{ $typeName }}</span></td>
                            <td class="fw-bold" style="color: {{ $textColor }};">{{ format_number($balance, 2) }}</td>
                            <td>
                                @if($isActive)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">معطل</span>
                                @endif
                            </td>
                            <td>
                                @if($rowType === 'account')
                                    @if(in_array($acc->code, ['feed_purchase', 'medicine_purchase', 'other_purchase']))
                                        @php
                                            $cat = '';
                                            if($acc->code === 'feed_purchase') $cat = 'feed';
                                            elseif($acc->code === 'medicine_purchase') $cat = 'medicine';
                                            elseif($acc->code === 'other_purchase') $cat = 'other';
                                        @endphp
                                        <a href="{{ route('purchase-invoices.index', ['category' => $cat]) }}" class="btn btn-sm btn-success" style="margin-left: 8px;"><i class="fa-solid fa-file-invoice"></i> تفاصيل الشراء</a>
                                    @endif
                                    <a href="{{ route('chart-of-accounts.transactions', $acc->id) }}" class="btn btn-sm btn-info" style="margin-left: 8px;"><i class="fa-solid fa-chart-bar"></i> عرض العمليات</a>
                                    <a href="{{ route('chart-of-accounts.edit', $acc) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-edit"></i> تعديل
                                    </a>
                                    @if(!$hasChildren)
                                        <form action="{{ route('chart-of-accounts.destroy', $acc) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fa-solid fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    @endif
                                @elseif($rowType === 'client')
                                    <a href="{{ route('clients.show', $acc) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fa-solid fa-file-alt"></i> كشف
                                    </a>
                                    <a href="{{ route('clients.edit', $acc) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                @elseif($rowType === 'supplier')
                                    <a href="{{ route('suppliers.show', $acc) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fa-solid fa-file-alt"></i> كشف
                                    </a>
                                    <a href="{{ route('suppliers.edit', $acc) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleTree(btn) {
    const row = btn.closest('tr');
    const icon = btn.querySelector('.toggle-icon');
    const isHidden = icon.classList.contains('fa-chevron-down');

    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-right');

    let sibling = row.nextElementSibling;
    while (sibling && (sibling.classList.contains('tree-child') || sibling.classList.contains('tree-nested'))) {
        sibling.hidden = isHidden;
        const nextIcon = sibling.querySelector('.toggle-icon');
        if (nextIcon) {
            nextIcon.classList.toggle('fa-chevron-down');
            nextIcon.classList.toggle('fa-chevron-right');
        }
        sibling = sibling.nextElementSibling;
    }
}

function expandAll() {
    document.querySelectorAll('.tree-child').forEach(el => el.hidden = false);
    document.querySelectorAll('.toggle-icon.fa-chevron-right').forEach(el => {
        el.classList.remove('fa-chevron-right');
        el.classList.add('fa-chevron-down');
    });
}

function collapseAll() {
    document.querySelectorAll('.tree-child').forEach(el => el.hidden = true);
    document.querySelectorAll('.toggle-icon.fa-chevron-down').forEach(el => {
        el.classList.remove('fa-chevron-down');
        el.classList.add('fa-chevron-right');
    });
}
</script>
@endsection
