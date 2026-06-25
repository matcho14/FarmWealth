<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * قائمة بجميع الصلاحيات المتاحة في النظام (مرتبطة بالقوائم)
     */
    public static $availablePermissions = [
        'sheds'             => 'إدارة العنابر',
        'cycles'            => 'إدارة الدورات',
        'financial-prices'  => 'رؤية الأسعار والتكاليف (داخل الدورات والعنابر)',
        'suppliers'         => 'إدارة الموردين',
        'items'             => 'إدارة الأصناف والمخزن الرئيسي',
        'inventory'         => 'إدارة مخازن العنابر (الفرعية)',
        'purchase-invoices' => 'فواتير الشراء',
        'treasuries'        => 'إدارة الخزائن',
        'journal-entries'   => 'القيود اليومية والتحويلات',
        'annual-report'     => 'التقارير السنوية',
    ];

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $permissions = self::$availablePermissions;
        $sheds = Shed::orderBy('name')->get();
        return view('users.create', compact('permissions', 'sheds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'password'    => ['required', 'confirmed', Password::defaults()],
            'role'        => 'required|in:admin,user',
            'permissions' => 'nullable|array',
            'assigned_shed_id' => 'nullable|exists:sheds,id',
        ]);

        $permissions = $request->role === 'admin' ? null : ($request->permissions ?? []);
        $assignedShedId = $request->input('assigned_shed_id');

        if ($request->role === 'admin' || !in_array('sheds', $permissions ?? [])) {
            $assignedShedId = null;
        }

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'permissions' => $permissions,
            'assigned_shed_id' => $assignedShedId,
        ]);

        return redirect()->route('users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        if ($user->id === auth()->id() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'لا تملك صلاحية تعديل بياناتك كأدمن');
        }

        $permissions = self::$availablePermissions;
        $sheds = Shed::orderBy('name')->get();
        return view('users.edit', compact('user', 'permissions', 'sheds'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'        => 'required|in:admin,user',
            'permissions' => 'nullable|array',
            'assigned_shed_id' => 'nullable|exists:sheds,id',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::defaults()];
        }

        $request->validate($rules);

        $permissions = $request->role === 'admin' ? null : ($request->permissions ?? []);
        $assignedShedId = $request->input('assigned_shed_id');

        if ($request->role === 'admin' || !in_array('sheds', $permissions ?? [])) {
            $assignedShedId = null;
        }

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'role'        => $request->role,
            'permissions' => $permissions,
            'assigned_shed_id' => $assignedShedId,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي');
        }

        if (User::where('role', 'admin')->count() <= 1 && $user->role === 'admin') {
            return back()->with('error', 'لا يمكن حذف آخر مسؤول (Admin) في النظام');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}