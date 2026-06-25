<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Abort;

trait ShedScoped
{
    protected function applyShedScope($query)
    {
        $user = auth()->user();
        if ($user && $user->isShedScoped()) {
            return $query->where('shed_id', $user->assigned_shed_id);
        }
        return $query;
    }

    protected function assertShedAccess($shed): void
    {
        $user = auth()->user();
        if ($user && $user->isShedScoped() && $shed->id !== $user->assigned_shed_id) {
            abort(403, 'لا تملك صلاحية الوصول إلى هذا العنبر.');
        }
    }
}
