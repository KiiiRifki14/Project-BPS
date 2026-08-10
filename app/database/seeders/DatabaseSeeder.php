<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Component;
use App\Models\Document;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\Output;
use App\Models\Program;
use App\Models\SubComponent;
use App\Models\SubOutput;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. DEFAULT USERS
        // ─────────────────────────────────────────────
        $users = [
            ['nip_username' => 'admin',      'name' => 'Administrator BPS',        'role' => 'ADMIN',      'password' => Hash::make('admin123')],
            ['nip_username' => 'supervisor',  'name' => 'Supervisor / PJ Kegiatan', 'role' => 'SUPERVISOR', 'password' => Hash::make('super123')],
            ['nip_username' => 'operator',    'name' => 'Operator Input (Pak Didin)','role' => 'OPERATOR',   'password' => Hash::make('oper123')],
            ['nip_username' => 'bendahara',   'name' => 'Bendahara Pengeluaran (Pak Ahmad)', 'role' => 'BENDAHARA', 'password' => Hash::make('bend123')],
        ];
        foreach ($users as $u) {
            User::updateOrCreate(['nip_username' => $u['nip_username']], $u);
        }

        // ─────────────────────────────────────────────
        // 2. FISCAL YEAR 2026
        // ─────────────────────────────────────────────
        $fy = FiscalYear::updateOrCreate(['year' => 2026], ['is_active' => true]);

        // ─────────────────────────────────────────────
        // 3. PROGRAM GG.2902
        // ─────────────────────────────────────────────
        $prog = Program::updateOrCreate(
            ['fiscal_year_id' => $fy->id, 'code' => 'GG.2902'],
            ['name' => 'Penyediaan dan Pengembangan Statistik Distribusi']
        );

        // ─────────────────────────────────────────────
        // 4. OUTPUT BMA
        // ─────────────────────────────────────────────
        $outBma = Output::updateOrCreate(
            ['program_id' => $prog->id, 'code' => 'BMA'],
            ['name' => 'Data dan Informasi Publik']
        );

        // ─────────────────────────────────────────────
        // 5a. SUB-OUTPUT BMA.004
        // ─────────────────────────────────────────────
        $so004 = SubOutput::updateOrCreate(
            ['output_id' => $outBma->id, 'code' => 'BMA.004'],
            ['name' => 'PUBLIKASI/LAPORAN STATISTIK DISTRIBUSI']
        );

        // BMA.004 → Komponen 005
        $comp005_004 = Component::updateOrCreate(
            ['sub_output_id' => $so004->id, 'code' => '005'],
            ['name' => 'Dukungan Penyelenggaraan Tugas dan Fungsi Unit']
        );
        $sc005_0A_004 = SubComponent::updateOrCreate(
            ['component_id' => $comp005_004->id, 'code' => '005.0A'],
            ['name' => 'TANPA SUB KOMPONEN']
        );
        // Akun 521213 – BMA.004/005/005.0A
        $acc521213_a = Account::updateOrCreate(
            ['sub_component_id' => $sc005_0A_004->id, 'code' => '521213'],
            ['name' => 'Belanja Honor Output Kegiatan']
        );
        Item::updateOrCreate(['account_id' => $acc521213_a->id, 'code' => '000733'],
            ['name' => 'Honor petugas pendataan lapangan Survei Jasa BPS Kab/Kota', 'pagu' => 15600000]);
        Item::updateOrCreate(['account_id' => $acc521213_a->id, 'code' => '001204'],
            ['name' => 'Honor pelaksanaan SPUNP', 'pagu' => 12400000]);
        // Akun 524113 – BMA.004/005/005.0A
        $acc524113_a = Account::updateOrCreate(
            ['sub_component_id' => $sc005_0A_004->id, 'code' => '524113'],
            ['name' => 'Belanja Perjalanan Dinas Dalam Kota']
        );
        Item::updateOrCreate(['account_id' => $acc524113_a->id, 'code' => '000734'],
            ['name' => 'Transport lokal petugas pemeriksaan lapangan Survei Jasa BPS Kab/Kota', 'pagu' => 8000000]);
        Item::updateOrCreate(['account_id' => $acc524113_a->id, 'code' => '001359'],
            ['name' => 'Transport lokal petugas pendataan lapangan SPUNP', 'pagu' => 6000000]);

        // BMA.004 → Komponen 051 PERSIAPAN
        $comp051_004 = Component::updateOrCreate(
            ['sub_output_id' => $so004->id, 'code' => '051'],
            ['name' => 'PERSIAPAN']
        );
        $sc051_0A_004 = SubComponent::updateOrCreate(
            ['component_id' => $comp051_004->id, 'code' => '051.0A'],
            ['name' => 'TANPA SUB KOMPONEN']
        );
        $acc521211_051 = Account::updateOrCreate(
            ['sub_component_id' => $sc051_0A_004->id, 'code' => '521211'],
            ['name' => 'Belanja Bahan']
        );
        Item::updateOrCreate(['account_id' => $acc521211_051->id, 'code' => '001363'],
            ['name' => 'Bahan habis pakai kegiatan statistik distribusi', 'pagu' => 5000000]);
        Item::updateOrCreate(['account_id' => $acc521211_051->id, 'code' => '001364'],
            ['name' => 'Bahan habis pakai persiapan pendataan', 'pagu' => 4500000]);
        $acc524113_051 = Account::updateOrCreate(
            ['sub_component_id' => $sc051_0A_004->id, 'code' => '524113'],
            ['name' => 'Belanja Perjalanan Dinas Dalam Kota']
        );
        Item::updateOrCreate(['account_id' => $acc524113_051->id, 'code' => '001365'],
            ['name' => 'Transport lokal petugas persiapan pendataan statistik distribusi', 'pagu' => 3600000]);

        // BMA.004 → Komponen 052 PENGUMPULAN DATA
        $comp052_004 = Component::updateOrCreate(
            ['sub_output_id' => $so004->id, 'code' => '052'],
            ['name' => 'PENGUMPULAN DATA']
        );
        $sc052_0A_004 = SubComponent::updateOrCreate(
            ['component_id' => $comp052_004->id, 'code' => '052.0A'],
            ['name' => 'TANPA SUB KOMPONEN']
        );
        $acc521211_052 = Account::updateOrCreate(
            ['sub_component_id' => $sc052_0A_004->id, 'code' => '521211'],
            ['name' => 'Belanja Bahan']
        );
        Item::updateOrCreate(['account_id' => $acc521211_052->id, 'code' => '000742'],
            ['name' => 'Bahan habis pakai pengumpulan data statistik distribusi', 'pagu' => 7200000]);
        Item::updateOrCreate(['account_id' => $acc521211_052->id, 'code' => '001361'],
            ['name' => 'Bahan pendataan survei konsumen', 'pagu' => 6100000]);
        Item::updateOrCreate(['account_id' => $acc521211_052->id, 'code' => '001362'],
            ['name' => 'Bahan pendataan survei harga produsen', 'pagu' => 5800000]);

        // ─────────────────────────────────────────────
        // 5b. SUB-OUTPUT BMA.006 — MVP CORE FOCUS
        // ─────────────────────────────────────────────
        $so006 = SubOutput::updateOrCreate(
            ['output_id' => $outBma->id, 'code' => 'BMA.006'],
            ['name' => 'PUBLIKASI/LAPORAN SENSUS EKONOMI']
        );

        // ── BMA.006 → [005] Dukungan Penyelenggaraan Tugas dan Fungsi Unit ──
        $comp005_006 = Component::updateOrCreate(
            ['sub_output_id' => $so006->id, 'code' => '005'],
            ['name' => 'Dukungan Penyelenggaraan Tugas dan Fungsi Unit']
        );

        // [005.0A] TANPA SUB KOMPONEN
        $sc005_0A_006 = SubComponent::updateOrCreate(
            ['component_id' => $comp005_006->id, 'code' => '005.0A'],
            ['name' => 'TANPA SUB KOMPONEN']
        );
        $acc521213_005_0A = Account::updateOrCreate(
            ['sub_component_id' => $sc005_0A_006->id, 'code' => '521213'],
            ['name' => 'Belanja Honor Output Kegiatan']
        );
        // ★ ITEM FOKUS MVP ★
        Item::updateOrCreate(['account_id' => $acc521213_005_0A->id, 'code' => '001366'],
            ['name' => 'Honor petugas pendataan sensus ekonomi BPS Kab/Kota', 'pagu' => 925600000]);
        Item::updateOrCreate(['account_id' => $acc521213_005_0A->id, 'code' => '001510'],
            ['name' => 'Honor pemeriksa lapangan sensus (UB-organik)', 'pagu' => 312000000]);

        $acc524113_005_0A = Account::updateOrCreate(
            ['sub_component_id' => $sc005_0A_006->id, 'code' => '524113'],
            ['name' => 'Belanja Perjalanan Dinas Dalam Kota']
        );
        Item::updateOrCreate(['account_id' => $acc524113_005_0A->id, 'code' => '000698'],
            ['name' => 'Task force pendataan lengkap dan CAWI BPS Kab/Kota', 'pagu' => 45000000]);

        // [005.0B] SENSUS EKONOMI 2026
        $sc005_0B_006 = SubComponent::updateOrCreate(
            ['component_id' => $comp005_006->id, 'code' => '005.0B'],
            ['name' => 'SENSUS EKONOMI 2026']
        );
        $acc521213_005_0B = Account::updateOrCreate(
            ['sub_component_id' => $sc005_0B_006->id, 'code' => '521213'],
            ['name' => 'Belanja Honor Output Kegiatan']
        );
        // ★ ITEM FOKUS MVP ★
        Item::updateOrCreate(['account_id' => $acc521213_005_0B->id, 'code' => '001211'],
            ['name' => 'Honor petugas lapangan sensus ekonomi', 'pagu' => 756000000]);
        Item::updateOrCreate(['account_id' => $acc521213_005_0B->id, 'code' => '001508'],
            ['name' => 'Honor pemeriksa lapangan sensus (PML)', 'pagu' => 234000000]);

        // ── BMA.006 → [523] Publisitas SE2026 ──
        $comp523 = Component::updateOrCreate(
            ['sub_output_id' => $so006->id, 'code' => '523'],
            ['name' => 'Publisitas SE2026']
        );
        $sc523_0A = SubComponent::updateOrCreate(
            ['component_id' => $comp523->id, 'code' => '523.0A'],
            ['name' => 'TANPA SUB KOMPONEN']
        );
        $acc521211_523 = Account::updateOrCreate(['sub_component_id' => $sc523_0A->id, 'code' => '521211'], ['name' => 'Belanja Bahan']);
        Item::updateOrCreate(['account_id' => $acc521211_523->id, 'code' => '001126'], ['name' => 'Bahan publisitas Sensus Ekonomi 2026', 'pagu' => 18000000]);
        $acc522191_523 = Account::updateOrCreate(['sub_component_id' => $sc523_0A->id, 'code' => '522191'], ['name' => 'Belanja Jasa Lainnya']);
        Item::updateOrCreate(['account_id' => $acc522191_523->id, 'code' => '000699'], ['name' => 'Jasa desain materi publisitas SE2026', 'pagu' => 25000000]);
        Item::updateOrCreate(['account_id' => $acc522191_523->id, 'code' => '000700'], ['name' => 'Jasa percetakan materi publisitas SE2026', 'pagu' => 32000000]);
        $acc524111_523 = Account::updateOrCreate(['sub_component_id' => $sc523_0A->id, 'code' => '524111'], ['name' => 'Belanja Perjalanan Dinas Biasa']);
        Item::updateOrCreate(['account_id' => $acc524111_523->id, 'code' => '001123'], ['name' => 'Perjalanan dinas luar kota kegiatan publisitas SE2026', 'pagu' => 15600000]);

        // ── BMA.006 → [524] Penetapan Kerangka Geospasial dan Muatan Wilkerstat ──
        $comp524 = Component::updateOrCreate(
            ['sub_output_id' => $so006->id, 'code' => '524'],
            ['name' => 'Penetapan Kerangka Geospasial dan Muatan Wilkerstat']
        );
        $sc524_0A = SubComponent::updateOrCreate(['component_id' => $comp524->id, 'code' => '524.0A'], ['name' => 'TANPA SUB KOMPONEN']);
        $acc521211_524 = Account::updateOrCreate(['sub_component_id' => $sc524_0A->id, 'code' => '521211'], ['name' => 'Belanja Bahan']);
        Item::updateOrCreate(['account_id' => $acc521211_524->id, 'code' => '000701'], ['name' => 'Bahan habis pakai kerangka geospasial SE2026', 'pagu' => 8500000]);
        Item::updateOrCreate(['account_id' => $acc521211_524->id, 'code' => '000702'], ['name' => 'Bahan peta Wilkerstat SE2026', 'pagu' => 7200000]);
        $acc521213_524 = Account::updateOrCreate(['sub_component_id' => $sc524_0A->id, 'code' => '521213'], ['name' => 'Belanja Honor Output Kegiatan']);
        Item::updateOrCreate(['account_id' => $acc521213_524->id, 'code' => '001340'], ['name' => 'Honor petugas pengolahan peta Wilkerstat SE2026', 'pagu' => 48000000]);
        $acc521219_524 = Account::updateOrCreate(['sub_component_id' => $sc524_0A->id, 'code' => '521219'], ['name' => 'Belanja Barang Non Operasional Lainnya']);
        Item::updateOrCreate(['account_id' => $acc521219_524->id, 'code' => '000704'], ['name' => 'Barang non operasional kerangka geospasial SE2026', 'pagu' => 12000000]);
        $acc521811_524 = Account::updateOrCreate(['sub_component_id' => $sc524_0A->id, 'code' => '521811'], ['name' => 'Belanja Barang Persediaan Barang Konsumsi']);
        Item::updateOrCreate(['account_id' => $acc521811_524->id, 'code' => '000705'], ['name' => 'ATK kegiatan kerangka geospasial SE2026', 'pagu' => 9600000]);
        Item::updateOrCreate(['account_id' => $acc521811_524->id, 'code' => '000706'], ['name' => 'Konsumsi rapat koordinasi Wilkerstat SE2026', 'pagu' => 7800000]);
        $acc524113_524 = Account::updateOrCreate(['sub_component_id' => $sc524_0A->id, 'code' => '524113'], ['name' => 'Belanja Perjalanan Dinas Dalam Kota']);
        Item::updateOrCreate(['account_id' => $acc524113_524->id, 'code' => '000707'], ['name' => 'Transport lokal petugas kerangka geospasial SE2026', 'pagu' => 18000000]);
        Item::updateOrCreate(['account_id' => $acc524113_524->id, 'code' => '000708'], ['name' => 'Transport lokal supervisi peta Wilkerstat SE2026', 'pagu' => 12000000]);

        // ── BMA.006 → [529] Penerapan Prelist SBR ──
        $comp529 = Component::updateOrCreate(['sub_output_id' => $so006->id, 'code' => '529'], ['name' => 'Penerapan Prelist SBR Untuk SE2026']);
        $sc529_0A = SubComponent::updateOrCreate(['component_id' => $comp529->id, 'code' => '529.0A'], ['name' => 'TANPA SUB KOMPONEN']);
        $acc521811_529 = Account::updateOrCreate(['sub_component_id' => $sc529_0A->id, 'code' => '521811'], ['name' => 'Belanja Barang Persediaan Barang Konsumsi']);
        Item::updateOrCreate(['account_id' => $acc521811_529->id, 'code' => '000709'], ['name' => 'ATK penerapan prelist SBR SE2026', 'pagu' => 6400000]);
        Item::updateOrCreate(['account_id' => $acc521811_529->id, 'code' => '000710'], ['name' => 'Konsumsi kegiatan prelist SBR SE2026', 'pagu' => 5200000]);
        $acc524113_529 = Account::updateOrCreate(['sub_component_id' => $sc529_0A->id, 'code' => '524113'], ['name' => 'Belanja Perjalanan Dinas Dalam Kota']);
        Item::updateOrCreate(['account_id' => $acc524113_529->id, 'code' => '000711'], ['name' => 'Transport lokal petugas prelist SBR SE2026', 'pagu' => 9600000]);
        Item::updateOrCreate(['account_id' => $acc524113_529->id, 'code' => '000712'], ['name' => 'Transport lokal supervisi prelist SBR SE2026', 'pagu' => 7200000]);

        // ── BMA.006 → [530] Pelaksanaan SE2026 ──
        $comp530 = Component::updateOrCreate(['sub_output_id' => $so006->id, 'code' => '530'], ['name' => 'Pelaksanaan SE2026']);
        // [530.0A] TANPA SUB KOMPONEN
        $sc530_0A = SubComponent::updateOrCreate(['component_id' => $comp530->id, 'code' => '530.0A'], ['name' => 'TANPA SUB KOMPONEN']);
        $acc521213_530_0A = Account::updateOrCreate(['sub_component_id' => $sc530_0A->id, 'code' => '521213'], ['name' => 'Belanja Honor Output Kegiatan']);
        Item::updateOrCreate(['account_id' => $acc521213_530_0A->id, 'code' => '001511'], ['name' => 'Honor koordinator wilayah pelaksanaan SE2026', 'pagu' => 96000000]);
        Item::updateOrCreate(['account_id' => $acc521213_530_0A->id, 'code' => '001512'], ['name' => 'Honor pengawas lapangan pelaksanaan SE2026', 'pagu' => 72000000]);
        // [530.0B] PENDATAAN LENGKAP
        $sc530_0B = SubComponent::updateOrCreate(['component_id' => $comp530->id, 'code' => '530.0B'], ['name' => 'PENDATAAN LENGKAP']);
        $acc521211_530B = Account::updateOrCreate(['sub_component_id' => $sc530_0B->id, 'code' => '521211'], ['name' => 'Belanja Bahan']);
        Item::updateOrCreate(['account_id' => $acc521211_530B->id, 'code' => '001344'], ['name' => 'Bahan habis pakai pendataan lengkap SE2026 (kuesioner)', 'pagu' => 45000000]);
        Item::updateOrCreate(['account_id' => $acc521211_530B->id, 'code' => '001345'], ['name' => 'Bahan habis pakai pendataan lengkap SE2026 (ATK)', 'pagu' => 28000000]);
        Item::updateOrCreate(['account_id' => $acc521211_530B->id, 'code' => '001367'], ['name' => 'Bahan habis pakai pendataan lengkap SE2026 (tablet)', 'pagu' => 36000000]);
        Item::updateOrCreate(['account_id' => $acc521211_530B->id, 'code' => '001509'], ['name' => 'Bahan habis pakai pendataan lengkap SE2026 (lainnya)', 'pagu' => 18000000]);
        $acc521213_530B = Account::updateOrCreate(['sub_component_id' => $sc530_0B->id, 'code' => '521213'], ['name' => 'Belanja Honor Output Kegiatan']);
        Item::updateOrCreate(['account_id' => $acc521213_530B->id, 'code' => '001346'], ['name' => 'Honor enumerator pendataan lengkap SE2026 gelombang 1', 'pagu' => 384000000]);
        Item::updateOrCreate(['account_id' => $acc521213_530B->id, 'code' => '001347'], ['name' => 'Honor enumerator pendataan lengkap SE2026 gelombang 2', 'pagu' => 312000000]);
        Item::updateOrCreate(['account_id' => $acc521213_530B->id, 'code' => '001348'], ['name' => 'Honor pengawas lapangan pendataan lengkap SE2026', 'pagu' => 156000000]);
        Item::updateOrCreate(['account_id' => $acc521213_530B->id, 'code' => '001349'], ['name' => 'Honor koordinator kecamatan pendataan lengkap SE2026', 'pagu' => 96000000]);
        $acc521811_530B = Account::updateOrCreate(['sub_component_id' => $sc530_0B->id, 'code' => '521811'], ['name' => 'Belanja Barang Persediaan Barang Konsumsi']);
        Item::updateOrCreate(['account_id' => $acc521811_530B->id, 'code' => '000724'], ['name' => 'ATK pendataan lengkap SE2026 tim BPS kab/kota', 'pagu' => 15600000]);
        Item::updateOrCreate(['account_id' => $acc521811_530B->id, 'code' => '000725'], ['name' => 'Konsumsi rapat koordinasi pendataan lengkap SE2026', 'pagu' => 12000000]);
        $acc522151_530B = Account::updateOrCreate(['sub_component_id' => $sc530_0B->id, 'code' => '522151'], ['name' => 'Belanja Jasa Profesi']);
        Item::updateOrCreate(['account_id' => $acc522151_530B->id, 'code' => '000727'], ['name' => 'Jasa narasumber pelatihan enumerator SE2026 sesi 1', 'pagu' => 24000000]);
        Item::updateOrCreate(['account_id' => $acc522151_530B->id, 'code' => '000728'], ['name' => 'Jasa narasumber pelatihan enumerator SE2026 sesi 2', 'pagu' => 24000000]);
        Item::updateOrCreate(['account_id' => $acc522151_530B->id, 'code' => '001341'], ['name' => 'Jasa konsultan pengolahan data SE2026', 'pagu' => 36000000]);
        $acc524113_530B = Account::updateOrCreate(['sub_component_id' => $sc530_0B->id, 'code' => '524113'], ['name' => 'Belanja Perjalanan Dinas Dalam Kota']);
        Item::updateOrCreate(['account_id' => $acc524113_530B->id, 'code' => '000729'], ['name' => 'Transport lokal tim BPS kab/kota pendataan lengkap SE2026', 'pagu' => 48000000]);
        Item::updateOrCreate(['account_id' => $acc524113_530B->id, 'code' => '001342'], ['name' => 'Transport lokal pengawas lapangan pendataan lengkap SE2026', 'pagu' => 36000000]);
        Item::updateOrCreate(['account_id' => $acc524113_530B->id, 'code' => '001343'], ['name' => 'Transport lokal koordinator kecamatan pendataan lengkap SE2026', 'pagu' => 28800000]);
        Item::updateOrCreate(['account_id' => $acc524113_530B->id, 'code' => '001368'], ['name' => 'Transport lokal supervisi BPS provinsi pendataan lengkap SE2026', 'pagu' => 24000000]);

        // ── BMA.006 → [535] Penyusunan Diseminasi SE2026 ──
        $comp535 = Component::updateOrCreate(['sub_output_id' => $so006->id, 'code' => '535'], ['name' => 'Penyusunan Diseminasi SE2026']);
        $sc535_0A = SubComponent::updateOrCreate(['component_id' => $comp535->id, 'code' => '535.0A'], ['name' => 'TANPA SUB KOMPONEN']);
        $acc521811_535 = Account::updateOrCreate(['sub_component_id' => $sc535_0A->id, 'code' => '521811'], ['name' => 'Belanja Barang Persediaan Barang Konsumsi']);
        Item::updateOrCreate(['account_id' => $acc521811_535->id, 'code' => '000732'], ['name' => 'ATK penyusunan diseminasi hasil SE2026', 'pagu' => 9600000]);

        // ─────────────────────────────────────────────
        // 6. OUTPUT FAN
        // ─────────────────────────────────────────────
        $outFan = Output::updateOrCreate(
            ['program_id' => $prog->id, 'code' => 'FAN'],
            ['name' => 'Pemenuhan Prioritas Direktif Presiden']
        );
        $soFanZZ1 = SubOutput::updateOrCreate(
            ['output_id' => $outFan->id, 'code' => 'FAN.ZZ1'],
            ['name' => 'Pemenuhan Prioritas Direktif Presiden']
        );
        $compFan051 = Component::updateOrCreate(
            ['sub_output_id' => $soFanZZ1->id, 'code' => '051'],
            ['name' => 'SENSUS EKONOMI 2026']
        );
        $scFan051_0A = SubComponent::updateOrCreate(
            ['component_id' => $compFan051->id, 'code' => '051.0A'],
            ['name' => 'TANPA SUB KOMPONEN']
        );

        // Akun 521213 – FAN.ZZ1
        $acc521213_fan = Account::updateOrCreate(['sub_component_id' => $scFan051_0A->id, 'code' => '521213'], ['name' => 'Belanja Honor Output Kegiatan']);
        Item::updateOrCreate(['account_id' => $acc521213_fan->id, 'code' => '001207'], ['name' => 'Honor petugas lapangan sensus ekonomi (FAN)', 'pagu' => 144000000]);
        Item::updateOrCreate(['account_id' => $acc521213_fan->id, 'code' => '001208'], ['name' => 'Honor pengolahan peta Wilkerstat (FAN)', 'pagu' => 96000000]);
        Item::updateOrCreate(['account_id' => $acc521213_fan->id, 'code' => '001350'], ['name' => 'Honor pengolahan peta Wilkerstat lanjutan (FAN)', 'pagu' => 72000000]);

        // Akun 521219 – FAN.ZZ1
        $acc521219_fan = Account::updateOrCreate(['sub_component_id' => $scFan051_0A->id, 'code' => '521219'], ['name' => 'Belanja Barang Non Operasional Lainnya']);
        Item::updateOrCreate(['account_id' => $acc521219_fan->id, 'code' => '001353'], ['name' => 'Asuransi petugas pendataan lapangan SE2026 (gelombang 1)', 'pagu' => 28800000]);
        Item::updateOrCreate(['account_id' => $acc521219_fan->id, 'code' => '001354'], ['name' => 'Asuransi petugas pendataan lapangan SE2026 (gelombang 2)', 'pagu' => 24000000]);

        // Akun 524113 – FAN.ZZ1
        $acc524113_fan = Account::updateOrCreate(['sub_component_id' => $scFan051_0A->id, 'code' => '524113'], ['name' => 'Belanja Perjalanan Dinas Dalam Kota']);
        Item::updateOrCreate(['account_id' => $acc524113_fan->id, 'code' => '001209'], ['name' => 'Task force pendataan lengkap dan CAWI BPS Kab/Kota (FAN)', 'pagu' => 36000000]);

        // ★ Akun 524114 — BATAS AKHIR AKUN MVP ★
        $acc524114_fan = Account::updateOrCreate(['sub_component_id' => $scFan051_0A->id, 'code' => '524114'], ['name' => 'Belanja Perjalanan Dinas Paket Meeting Dalam Kota']);
        Item::updateOrCreate(['account_id' => $acc524114_fan->id, 'code' => '001351'],
            ['name' => 'Paket Meeting Fullboard pelatihan petugas pendataan lapangan SE2026', 'pagu' => 185760000]);
        Item::updateOrCreate(['account_id' => $acc524114_fan->id, 'code' => '001352'],
            ['name' => 'Perjalanan Fullboard pelatihan petugas pendataan lapangan SE2026', 'pagu' => 142560000]);

        $this->command->info('✅ Database seeded: ' . User::count() . ' users, ' . Item::count() . ' items dalam hirarki POK GG.2902.');
    }
}
