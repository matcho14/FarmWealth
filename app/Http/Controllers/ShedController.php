<?php

namespace App\Http\Controllers;

use App\Models\Shed;
use App\Traits\ShedScoped;
use Illuminate\Http\Request;

class ShedController extends Controller
{
    use ShedScoped;
    /**
     * عرض لوحة التحكم
     */
    public function dashboard()
    {
        $sheds = $this->applyShedScope(Shed::query())->get();
        $shedIds = $sheds->pluck('id');
        $totalSheds = $sheds->count();
        $activeCycles = \App\Models\Cycle::whereIn('shed_id', $shedIds)->where('status', 'active')->count();
        $closedCycles = \App\Models\Cycle::whereIn('shed_id', $shedIds)->where('status', 'closed')->get();
        $completedCycles = $closedCycles->count();
        $totalChicks = \App\Models\Cycle::whereIn('shed_id', $shedIds)->sum('initial_chicks');
        $totalMortality = \App\Models\Cycle::whereIn('shed_id', $shedIds)->sum('mortality_count');

        // Calculate total profit from closed cycles
        $totalProfit = $closedCycles->sum(function ($cycle) {
            return $cycle->net_profit;
        });

        // Get recent cycles
        $recentCycles = \App\Models\Cycle::whereIn('shed_id', $shedIds)->with('shed')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('components.dashboard', compact(
            'sheds',
            'totalSheds',
            'activeCycles',
            'completedCycles',
            'totalChicks',
            'totalMortality',
            'totalProfit',
            'recentCycles'
        ));
    }

    /**
     * عرض قائمة العنابر
     */
    public function index()
    {
        $sheds = $this->applyShedScope(Shed::with(['cycles' => function ($query) {
            $query->where('status', 'active');
        }]))->get();

        return view('sheds.index', compact('sheds'));
    }

    /**
     * عرض نموذج إنشاء عنبر جديد
     */
    public function create()
    {
        return view('sheds.create');
    }

    /**
     * حفظ عنبر جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'floors'      => 'required|integer|min:1|max:10',
        ], [
            'name.required'    => 'اسم العنبر مطلوب',
            'name.max'         => 'اسم العنبر لا يجب أن يزيد عن 255 حرف',
            'floors.required'  => 'عدد الأدوار مطلوب',
            'floors.min'       => 'يجب أن يكون عنبر واحد على الأقل',
            'floors.max'       => 'الحد الأقصى للأدوار هو 10',
        ]);

        Shed::create($validated);

        return redirect()->route('sheds.index')->with('success', 'تم إنشاء العنبر بنجاح');
    }

    /**
     * عرض تفاصيل العنبر
     */
    public function show(Shed $shed)
    {
        $this->assertShedAccess($shed);
        $activeCycles = $shed->activeCycles();
        $completedCycles = $shed->completedCycles();

        return view('sheds.show', compact('shed', 'activeCycles', 'completedCycles'));
    }

    /**
     * عرض نموذج تعديل العنبر
     */
    public function edit(Shed $shed)
    {
        return view('sheds.edit', compact('shed'));
    }

    /**
     * تحديث بيانات العنبر
     */
    public function update(Request $request, Shed $shed)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'floors'      => 'required|integer|min:1|max:10',
            'status'      => 'required|in:active,inactive',
        ]);

        $shed->update($validated);

        return redirect()->route('sheds.show', $shed)->with('success', 'تم تحديث العنبر بنجاح');
    }

    /**
     * حذف العنبر
     */
    public function destroy(Shed $shed)
    {
        $shed->delete();

        return redirect()->route('sheds.index')->with('success', 'تم حذف العنبر بنجاح');
    }
}
