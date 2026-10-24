<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';

    protected $guarded = [];

    public function getJamPelajaranAttribute($value)
    {
        if (!$value) {
            return [
                1 => ['mulai' => '07:00', 'selesai' => '07:45'],
                2 => ['mulai' => '07:45', 'selesai' => '08:30'],
                3 => ['mulai' => '08:30', 'selesai' => '09:15'],
                4 => ['mulai' => '09:15', 'selesai' => '10:00'],
                5 => ['mulai' => '10:00', 'selesai' => '10:45'],
                6 => ['mulai' => '10:45', 'selesai' => '11:30'],
                7 => ['mulai' => '11:30', 'selesai' => '12:15'],
                8 => ['mulai' => '12:15', 'selesai' => '13:00'],
                9 => ['mulai' => '13:00', 'selesai' => '13:45'],
                10 => ['mulai' => '13:45', 'selesai' => '14:30'],
            ];
        }
        return json_decode($value, true);
    }

    public function getSpSettingsAttribute($value)
    {
        if (!$value) {
            return [
                'jumlah_sp' => 3,
                'sp_rules' => [
                    1 => 25,
                    2 => 50,
                    3 => 75
                ]
            ];
        }
        return json_decode($value, true);
    }

    public function getSpStatus($score, $defaultUnderThreshold = 'Aman')
    {
        $spSettings = $this->sp_settings;
        $rules = $spSettings['sp_rules'] ?? [];
        
        // Urutkan key SP secara terbalik (cth: SP 3, SP 2, SP 1)
        krsort($rules);
        $maxLevel = !empty($rules) ? max(array_keys($rules)) : 3;
        
        foreach ($rules as $spLevel => $threshold) {
            if ($score >= (int) $threshold) {
                if ($spLevel == $maxLevel) {
                    return "SP {$spLevel} (Orang tua)";
                }
                return "SP {$spLevel}";
            }
        }
        
        return $defaultUnderThreshold;
    }

    public function getSpBadgeClass($score)
    {
        $spSettings = $this->sp_settings;
        $rules = $spSettings['sp_rules'] ?? [];
        krsort($rules);
        $maxLevel = !empty($rules) ? max(array_keys($rules)) : 3;

        foreach ($rules as $spLevel => $threshold) {
            if ($score >= (int) $threshold) {
                if ($spLevel == $maxLevel) {
                    return 'sp-danger';
                }
                if ($spLevel == 1) {
                    return 'sp-amber';
                }
                return 'sp-warning';
            }
        }

        return 'sp-info';
    }

    public function getRekapWaSettingsAttribute($value)
    {
        $default = [
            'walas' => [
                'is_active' => true,
                'frequency' => 'weekly', // 'weekly' or 'monthly'
                'day' => 'friday',       // 'monday'..'sunday' or 'last_day' / '1'..'28'
                'time' => '16:00',
                'session_id' => 'auto',  // 'auto' or specific session_id
            ],
            'orangtua' => [
                'is_active' => true,
                'frequency' => 'monthly', // 'monthly' or 'weekly'
                'day' => 'last_day',      // 'last_day' or '1'..'28' or 'monday'..'sunday'
                'time' => '17:00',
            ]
        ];

        if (!$value) {
            return $default;
        }

        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return $default;
        }

        return array_replace_recursive($default, $decoded);
    }

    public function getLevelPklAttribute($value)
    {
        if (!$value) {
            return ['XI', 'XII'];
        }
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : ['XI', 'XII'];
    }

    public function isLevelPkl($kodeLevel): bool
    {
        if (empty($kodeLevel)) {
            return false;
        }
        $allowed = $this->level_pkl;
        return in_array(strtoupper(trim($kodeLevel)), array_map('strtoupper', $allowed));
    }

    public function getKejuruanNomorSettingsAttribute($value): array
    {
        $default = [
            'tkk' => [
                'format' => 'TKK/{JURUSAN}/{KLASTER}/{ROMAWI}/{TAHUN}/{NO}',
                'padding' => 4,
                'reset_periode' => 'tahun', // 'tahun', 'tahun_ajaran', 'tidak_pernah'
                'counter' => 0,
                'last_reset_year' => date('Y'),
                'keterangan' => 'Format nomor seri resmi Piagam Tanda Kecakapan Khusus (TKK)',
            ],
            'sertifikat' => [
                'format' => 'SERT-UKK/{JURUSAN}/{ROMAWI}/{TAHUN}/{NO}',
                'padding' => 4,
                'reset_periode' => 'tahun',
                'counter' => 0,
                'last_reset_year' => date('Y'),
                'keterangan' => 'Format nomor sertifikat Uji Kompetensi Keahlian (UKK) & Portofolio',
            ],
        ];

        if (!$value) {
            return $default;
        }

        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return $default;
        }

        return array_replace_recursive($default, $decoded);
    }

    public static function getRomanMonth($month = null): string
    {
        $m = intval($month ?: date('n'));
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$m] ?? 'I';
    }

    public function generateNomorTkk($tkk, $siswa = null, bool $increment = true): string
    {
        $settings = $this->kejuruan_nomor_settings;
        $tkkCfg = $settings['tkk'] ?? [];

        $currentYear = date('Y');
        $resetPeriode = $tkkCfg['reset_periode'] ?? 'tahun';
        $lastResetYear = $tkkCfg['last_reset_year'] ?? $currentYear;
        $counter = intval($tkkCfg['counter'] ?? 0);

        if ($resetPeriode === 'tahun' && $lastResetYear != $currentYear) {
            $counter = 0;
            $tkkCfg['last_reset_year'] = $currentYear;
        }

        $targetCounter = $counter + 1;
        $padding = intval($tkkCfg['padding'] ?? 4);
        $paddedNo = str_pad($targetCounter, $padding, '0', STR_PAD_LEFT);

        $kodeJurusan = 'KEJ';
        if ($tkk && isset($tkk->jurusan) && $tkk->jurusan) {
            $kodeJurusan = $tkk->jurusan->kode_jurusan ?? $kodeJurusan;
        }

        $klasterStr = 'GEN';
        if ($tkk && !empty($tkk->nomor_klaster)) {
            $klasterStr = 'KL' . $tkk->nomor_klaster;
        }

        $kodeTkk = ($tkk && !empty($tkk->kode_tkk)) ? $tkk->kode_tkk : 'TKK';

        $format = $tkkCfg['format'] ?? 'TKK/{JURUSAN}/{KLASTER}/{ROMAWI}/{TAHUN}/{NO}';
        $replacements = [
            '{NO}' => $paddedNo,
            '{JURUSAN}' => $kodeJurusan,
            '{KLASTER}' => $klasterStr,
            '{KODE_TKK}' => $kodeTkk,
            '{BULAN}' => date('m'),
            '{ROMAWI}' => self::getRomanMonth(),
            '{TAHUN}' => $currentYear,
            '{TA}' => str_replace('/', '-', $this->tahun_ajaran ?? date('Y')),
        ];

        $nomorFinal = str_replace(array_keys($replacements), array_values($replacements), $format);

        if ($increment) {
            $tkkCfg['counter'] = $targetCounter;
            $settings['tkk'] = $tkkCfg;
            $this->kejuruan_nomor_settings = json_encode($settings);
            $this->save();
        }

        return $nomorFinal;
    }

    public function generateNomorSertifikat($jurusan = null, $siswa = null, bool $increment = true): string
    {
        $settings = $this->kejuruan_nomor_settings;
        $sertCfg = $settings['sertifikat'] ?? [];

        $currentYear = date('Y');
        $resetPeriode = $sertCfg['reset_periode'] ?? 'tahun';
        $lastResetYear = $sertCfg['last_reset_year'] ?? $currentYear;
        $counter = intval($sertCfg['counter'] ?? 0);

        if ($resetPeriode === 'tahun' && $lastResetYear != $currentYear) {
            $counter = 0;
            $sertCfg['last_reset_year'] = $currentYear;
        }

        $targetCounter = $counter + 1;
        $padding = intval($sertCfg['padding'] ?? 4);
        $paddedNo = str_pad($targetCounter, $padding, '0', STR_PAD_LEFT);

        $kodeJurusan = is_object($jurusan) ? ($jurusan->kode_jurusan ?? 'KEJ') : (is_string($jurusan) && !empty($jurusan) ? $jurusan : 'KEJ');

        $format = $sertCfg['format'] ?? 'SERT-UKK/{JURUSAN}/{ROMAWI}/{TAHUN}/{NO}';
        $replacements = [
            '{NO}' => $paddedNo,
            '{JURUSAN}' => $kodeJurusan,
            '{BULAN}' => date('m'),
            '{ROMAWI}' => self::getRomanMonth(),
            '{TAHUN}' => $currentYear,
            '{TA}' => str_replace('/', '-', $this->tahun_ajaran ?? date('Y')),
        ];

        $nomorFinal = str_replace(array_keys($replacements), array_values($replacements), $format);

        if ($increment) {
            $sertCfg['counter'] = $targetCounter;
            $settings['sertifikat'] = $sertCfg;
            $this->kejuruan_nomor_settings = json_encode($settings);
            $this->save();
        }

        return $nomorFinal;
    }
}
