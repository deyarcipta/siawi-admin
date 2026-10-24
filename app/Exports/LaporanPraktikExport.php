<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanPraktikExport implements FromView, ShouldAutoSize
{
    protected $jurnals;
    protected $setting;
    protected $periode;
    protected $user;

    public function __construct($jurnals, $setting, $periode, $user)
    {
        $this->jurnals = $jurnals;
        $this->setting = $setting;
        $this->periode = $periode;
        $this->user = $user;
    }

    public function view(): View
    {
        return view('kejuruan.laporan_praktik.excel', [
            'jurnals' => $this->jurnals,
            'setting' => $this->setting,
            'periode' => $this->periode,
            'user' => $this->user,
        ]);
    }
}
