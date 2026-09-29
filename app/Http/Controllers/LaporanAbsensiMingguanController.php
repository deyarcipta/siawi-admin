<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanAbsensiMingguanController extends Controller
{
    protected $bulananController;

    public function __construct(LaporanAbsensiBulananController $bulananController)
    {
        $this->bulananController = $bulananController;
    }

    public function index(Request $request)
    {
        return redirect()->route('admin.laporanBulananWa.index', $request->query());
    }

    public function kirimOrangTua(Request $request)
    {
        return $this->bulananController->kirimOrangTua($request);
    }

    public function kirimWaliKelas(Request $request)
    {
        return $this->bulananController->kirimWaliKelas($request);
    }

    public function preview(Request $request)
    {
        return $this->bulananController->preview($request);
    }
}
