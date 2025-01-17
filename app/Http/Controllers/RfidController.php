<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class RfidController extends Controller
{
    // Method untuk menampilkan halaman input RFID
    public function index()
    {
        return view('rfid.rfid'); // Pastikan Anda punya view 'rfid.rfid'
    }

    // Method untuk menyimpan data RFID yang diterima
    public function store(Request $request)
    {
        $rfidData = $request->input('rfid');
        \Log::info('Data RFID diterima: ' . $rfidData);

        // Kirim response JSON ke frontend
        return response()->json(['message' => 'Data berhasil diterima', 'rfid' => $rfidData]);
    }
}