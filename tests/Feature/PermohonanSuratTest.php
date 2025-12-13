<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\JenisSurat;
use App\Models\SuratTemplate;
use App\Models\PermohonanSurat;

class PermohonanSuratTest extends TestCase
{
    // Gunakan RefreshDatabase agar database dirisat bersih setiap kali test jalan
    // TAPI hati-hati kalau di local development biasa, data asli bisa hilang.
    // LEBIH AMAN: Pakai DatabaseTransactions agar data test di-rollback setelah selesai.
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * Test Alur Lengkap: Warga Request -> RT Approve
     */
    public function test_alur_lengkap_permohonan_surat()
    {
        // 1. SETUP DATA (Warga, RT, Jenis Surat)
        $rw = Rw::firstOrCreate(['nomor_rw' => '01']);
        $rt = Rt::firstOrCreate(['nomor_rt' => '01', 'rw_id' => $rw->id]);
        
        // Pastikan Role ada
        $roleWarga = Role::where('name', 'masyarakat')->first();
        if(!$roleWarga) $roleWarga = Role::create(['name' => 'masyarakat', 'description' => 'Warga']);

        $roleRT = Role::where('name', 'rt')->first();
        if(!$roleRT) $roleRT = Role::create(['name' => 'rt', 'description' => 'Ketua RT']);

        // Buat User Warga
        $warga = User::factory()->create([
            'name' => 'Warga Test',
            'email' => 'warga_test_'.time().'@example.com',
            'role_id' => $roleWarga->id,
            'rt_id' => $rt->id,
            'nik' => '123456' . time(), // Ensure unique digits
            'alamat' => 'Jl. Test No. 1',
            'status' => 'active'
        ]);

        // Buat User RT
        $pakRT = User::factory()->create([
            'name' => 'Pak RT Test',
            'email' => 'rt_test_'.time().'@example.com',
            'role_id' => $roleRT->id,
            'rt_id' => $rt->id,
            'nik' => '999999' . time(), // RT juga butuh NIK
            'alamat' => 'Rumah Pak RT',
            'status' => 'active'
        ]);

        // Buat Jenis Surat Test
        $jenisSurat = JenisSurat::create([
            'kode_surat' => 'SKTM_TEST', // Changed from code to kode_surat based on error
            'name' => 'Surat Keterangan Tidak Mampu Test',
            'name' => 'Surat Keterangan Tidak Mampu Test',
            'description' => 'Test Only', // Restored
            'bidang' => 'kesra', // Added missing bidang
            'persyaratan' => '- KTP\n- KK', // Added missing persyaratan
            'is_active' => true
        ]);
        
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
        
        
        // Buat Template Surat Pengantar RT (Wajib ada biar gak error di controller)
        SuratTemplate::create([
            'type' => 'pengantar_rt',
            'template_content' => '<p>Surat Pengantar RT</p>',
            'is_active' => true
        ]);


        // ============================================
        // STEP 1: Warga Login & Ajukan Surat
        // ============================================
        
        // $this->withoutExceptionHandling(); // DEBUG MODE: Remove later

        $response = $this->actingAs($warga)
            ->post(route('masyarakat.permohonan.store.dinamis', $jenisSurat->id), [
                'data' => [
                    'nik' => $warga->nik,
                    'keperluan' => 'Untuk Beasiswa'
                ],
                'keterangan_tambahan' => 'Mohon dibantu pak'
            ]);

        // Pastikan redirect sukses (biasanya ke detail)
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        // Cek Database: Harusnya ada surat status MENUNGGU_RT
        $this->assertDatabaseHas('permohonan_surats', [
            'user_id' => $warga->id,
            'jenis_surat_id' => $jenisSurat->id,
            'status' => PermohonanSurat::MENUNGGU_RT
        ]);

        // Ambil ID Permohonan yang baru dibuat
        $permohonan = PermohonanSurat::where('user_id', $warga->id)->latest()->first();


        // ============================================
        // STEP 2: RT Login & Lakukan Approval
        // ============================================

        $responseApproval = $this->actingAs($pakRT)
            ->post(route('rt.permohonan.process', $permohonan->id), [
                'action' => 'approve',
                'nomor_surat_pengantar' => '001/RT-TEST/XII/2025',
                'isi_surat' => '<p>Ok saya setujui</p>',
                'catatan' => 'Lanjut ke kelurahan'
            ]);

        // Pastikan redirect sukses
        $responseApproval->assertStatus(302);
        $responseApproval->assertSessionHas('success');

        // Cek Database: Status harus berubah jadi MENUNGGU_KASI
        $this->assertDatabaseHas('permohonan_surats', [
            'id' => $permohonan->id,
            'status' => PermohonanSurat::MENUNGGU_KASI,
            'nomor_surat_pengantar_rt' => '001/RT-TEST/XII/2025'
        ]);
    }
}
