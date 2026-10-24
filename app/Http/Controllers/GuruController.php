<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use DB;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $guru = Guru::orderBy('nama_guru', 'asc')->get();
        return view('dataGuru.dataGuru', compact('layout','guru','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $jurusans = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        return view('dataGuru.tambah_guru', compact('layout','setting','user','jurusans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $selectedRoles = $request->input('roles', []);
        if (empty($selectedRoles) && $request->filled('role')) {
            $selectedRoles = [$request->role];
        }
        if (empty($selectedRoles)) {
            $selectedRoles = ['guru'];
        }

        $rules = [
            'username' => 'required|unique:guru,username',
            'nama_guru' => 'required',
            'roles' => 'nullable|array',
            'roles.*' => 'string',
            'role' => 'nullable|string',
            'no_hp' => 'nullable|string',
        ];

        if (in_array('kaprog', $selectedRoles)) {
            $rules['id_jurusan_kaprog'] = 'required|exists:jurusan,id_jurusan';
        }

        $messages = [
            'id_jurusan_kaprog.required' => 'Program keahlian wajib dipilih jika peran Kaprog dicentang.',
            'id_jurusan_kaprog.exists' => 'Program keahlian yang dipilih tidak valid.',
        ];

        $request->validate($rules, $messages);

        $id_face = '0';
        $changPass = 'admin123';
        $password = Hash::make($changPass);

        $primaryRole = $request->input('role') ?: $selectedRoles[0];

        $guru = Guru::create([
            'id_face' => $id_face,
            'username' => $request->username,
            'password' => $password,
            'nama_guru' => $request->nama_guru,
            'role' => $primaryRole,
            'roles' => $selectedRoles,
            'no_hp' => $request->no_hp,
        ]);

        if (in_array('kaprog', $selectedRoles) && $request->filled('id_jurusan_kaprog')) {
            DB::table('guru_jurusan')->insert([
                'id_guru' => $guru->id_guru,
                'id_jurusan' => $request->id_jurusan_kaprog,
                'role' => 'kaprog',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (in_array('guru_jurusan', $selectedRoles) && $request->filled('id_jurusan_guru')) {
            DB::table('guru_jurusan')->insert([
                'id_guru' => $guru->id_guru,
                'id_jurusan' => $request->id_jurusan_guru,
                'role' => 'guru_jurusan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect('/admin/guru')->with('success', 'Data Guru Berhasil Ditambah<br>password default adalah <b>admin123</b>');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_guru)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Guru::find($id_guru);
        $jurusans = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $kaprogJurusanId = DB::table('guru_jurusan')
            ->where('id_guru', $id_guru)
            ->where('role', 'kaprog')
            ->value('id_jurusan');
        $guruJurusanId = DB::table('guru_jurusan')
            ->where('id_guru', $id_guru)
            ->where('role', 'guru_jurusan')
            ->value('id_jurusan');

        return view('dataGuru.edit_guru', compact('layout','edit','setting','user','jurusans','kaprogJurusanId','guruJurusanId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_guru)
    {
        $selectedRoles = $request->input('roles', []);
        if (empty($selectedRoles) && $request->filled('role')) {
            $selectedRoles = [$request->role];
        }
        if (empty($selectedRoles)) {
            $selectedRoles = ['guru'];
        }

        $rules = [
            'username' => 'required',
            'nama_guru' => 'required',
            'id_face' => 'nullable',
            'roles' => 'nullable|array',
            'roles.*' => 'string',
            'role' => 'nullable|string',
            'no_hp' => 'nullable|string',
        ];

        if (in_array('kaprog', $selectedRoles)) {
            $rules['id_jurusan_kaprog'] = 'required|exists:jurusan,id_jurusan';
        }

        $messages = [
            'id_jurusan_kaprog.required' => 'Program keahlian wajib dipilih jika peran Kaprog dicentang.',
            'id_jurusan_kaprog.exists' => 'Program keahlian yang dipilih tidak valid.',
        ];

        $request->validate($rules, $messages);

        $primaryRole = $request->input('role') ?: $selectedRoles[0];

        $guru = Guru::findOrFail($id_guru);
        $guru->username = $request->username;
        $guru->nama_guru = $request->nama_guru;
        $guru->role = $primaryRole;
        $guru->roles = $selectedRoles;
        $guru->no_hp = $request->no_hp;
        if ($request->filled('id_face')) {
            $guru->id_face = $request->id_face;
        }
        $guru->save();

        // Sinkronisasi pivot penugasan Kaprog
        DB::table('guru_jurusan')
            ->where('id_guru', $id_guru)
            ->where('role', 'kaprog')
            ->delete();

        if (in_array('kaprog', $selectedRoles) && $request->filled('id_jurusan_kaprog')) {
            DB::table('guru_jurusan')->insert([
                'id_guru' => $id_guru,
                'id_jurusan' => $request->id_jurusan_kaprog,
                'role' => 'kaprog',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Sinkronisasi pivot penugasan Guru Jurusan
        DB::table('guru_jurusan')
            ->where('id_guru', $id_guru)
            ->where('role', 'guru_jurusan')
            ->delete();

        if (in_array('guru_jurusan', $selectedRoles) && $request->filled('id_jurusan_guru')) {
            DB::table('guru_jurusan')->insert([
                'id_guru' => $id_guru,
                'id_jurusan' => $request->id_jurusan_guru,
                'role' => 'guru_jurusan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect('/admin/guru')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_guru)
    {
        DB::transaction(function () use ($id_guru) {
            // Lepas status wali kelas dari kelas terkait
            Kelas::where('id_guru', $id_guru)->update(['id_guru' => null]);
            
            // Hapus data guru
            Guru::destroy($id_guru);
        });

        return redirect('/admin/guru')->with('success', 'Data guru berhasil dihapus.');
    }

    public function reset(string $id_guru)
    {
        $layout = 'layout.app'; // Misalnya, layout default Anda adalah 'layouts.app'
        $setting = Setting::find('1');
        $user = Auth::user();
        $guru = Guru::find($id_guru);
        $changPass = 'admin123';
        $guru->password = Hash::make($changPass);
        $guru->save();
        return redirect('/admin/guru')->with('success', 'Password berhasil direset<br>password default adalah <b>admin123</b>');
    }
}
