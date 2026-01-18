<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->latest('waktu')
            ->paginate(20);
        return view('admin.log.index', compact('logs'));
    }

    public function show(LogAktivitas $log)
    {
        $log->load('user');
        return view('admin.log.show', compact('log'));
    }
}
