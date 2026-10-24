<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarisLabExport implements FromView, ShouldAutoSize
{
    protected $asets;
    protected $lab;
    protected $setting;
    protected $user;

    public function __construct($asets, $lab, $setting, $user)
    {
        $this->asets = $asets;
        $this->lab = $lab;
        $this->setting = $setting;
        $this->user = $user;
    }

    public function view(): View
    {
        return view('kejuruan.inventaris_lab.excel', [
            'asets' => $this->asets,
            'lab' => $this->lab,
            'setting' => $this->setting,
            'user' => $this->user,
        ]);
    }
}
