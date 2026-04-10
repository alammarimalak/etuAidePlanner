<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAlert;

class AdminAlertController extends Controller
{
    public function resolve(AdminAlert $alert)
    {
        $this->authorizeAlert($alert);

        $alert->forceFill([
            'status' => AdminAlert::STATUS_RESOLVED,
            'resolved_at' => now(),
        ])->save();

        return redirect()->back()->with('status', 'Alert marked as resolved.');
    }

    public function dismiss(AdminAlert $alert)
    {
        $this->authorizeAlert($alert);

        $alert->forceFill([
            'status' => AdminAlert::STATUS_DISMISSED,
            'resolved_at' => null,
        ])->save();

        return redirect()->back()->with('status', 'Alert dismissed.');
    }

    protected function authorizeAlert(AdminAlert $alert): void
    {
        if ($alert->admin_id !== $this->currentUser()->id) {
            abort(404);
        }
    }
}
