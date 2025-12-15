<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\TemplateField;
use App\Models\RequiredDocument;
use Illuminate\Database\Seeder;

class TemplateFieldSeeder extends Seeder
{
    public function run()
    {
        echo "Memulai TemplateFieldSeeder (Fixed)...\n";

        // 1. SKTM
        $sktm = JenisSurat::where('kode_surat', 'SKTM')->first();
        if ($sktm) $this->createSKTMFields($sktm);

        // 2. Domisili
        $domisili = JenisSurat::where('kode_surat', 'DOM')->first();
        if ($domisili) $this->createDomisiliFields($domisili);

        // 3. SKU
        $usaha = JenisSurat::where('kode_surat', 'SKU')->first();
        if ($usaha) $this->createUsahaFields($usaha);

        // 4. N1
        $nikah = JenisSurat::where('kode_surat', 'N1')->first();
        if ($nikah) $this->createNikahFields($nikah);

        echo "Seeder TemplateField selesai! 🎉\n";
    }

    private function createSKTMFields($jenisSurat)
    {
        // Removed validation_rules and order as they don't exist in DB schema
        $fields = [
            ['field_key' => 'nama_lengkap', 'field_label' => 'Nama Lengkap', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'nik', 'field_label' => 'NIK', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tempat_lahir', 'field_label' => 'Tempat Lahir', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tanggal_lahir', 'field_label' => 'Tanggal Lahir', 'field_type' => 'date', 'is_required' => true],
            ['field_key' => 'jenis_kelamin', 'field_label' => 'Jenis Kelamin', 'field_type' => 'dropdown', 'options' => json_encode(['Laki-laki', 'Perempuan']), 'is_required' => true],
            ['field_key' => 'alamat', 'field_label' => 'Alamat Lengkap', 'field_type' => 'textarea', 'is_required' => true],
            ['field_key' => 'jumlah_tanggungan', 'field_label' => 'Jumlah Tanggungan Keluarga', 'field_type' => 'number', 'is_required' => true],
            ['field_key' => 'penghasilan', 'field_label' => 'Penghasilan per Bulan', 'field_type' => 'number', 'is_required' => true],
            ['field_key' => 'pekerjaan', 'field_label' => 'Pekerjaan', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tujuan', 'field_label' => 'Tujuan Pembuatan SKTM', 'field_type' => 'textarea', 'is_required' => true]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'field_key' => $field['field_key']],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        $documents = [
            ['document_name' => 'ktp', 'document_label' => 'KTP Pemohon', 'required' => true],
            ['document_name' => 'kk', 'document_label' => 'Kartu Keluarga', 'required' => true],
            ['document_name' => 'slip_gaji', 'document_label' => 'Slip Gaji/Surat Keterangan Penghasilan', 'required' => false],
            ['document_name' => 'foto_rumah', 'document_label' => 'Foto Kondisi Rumah', 'required' => false],
        ];

        foreach ($documents as $doc) {
            RequiredDocument::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'document_name' => $doc['document_name']],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }

    private function createDomisiliFields($jenisSurat)
    {
        $fields = [
            ['field_key' => 'nama_lengkap', 'field_label' => 'Nama Lengkap', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'nik', 'field_label' => 'NIK', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tempat_lahir', 'field_label' => 'Tempat Lahir', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tanggal_lahir', 'field_label' => 'Tanggal Lahir', 'field_type' => 'date', 'is_required' => true],
            ['field_key' => 'alamat_sebelumnya', 'field_label' => 'Alamat Sebelumnya', 'field_type' => 'textarea', 'is_required' => true],
            ['field_key' => 'alamat_sekarang', 'field_label' => 'Alamat Sekarang (Domisili)', 'field_type' => 'textarea', 'is_required' => true],
            ['field_key' => 'lama_tinggal', 'field_label' => 'Lama Tinggal (bulan)', 'field_type' => 'number', 'is_required' => true],
            ['field_key' => 'tujuan', 'field_label' => 'Tujuan Pembuatan Surat Domisili', 'field_type' => 'textarea', 'is_required' => true]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'field_key' => $field['field_key']],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        $documents = [
            ['document_name' => 'ktp', 'document_label' => 'KTP Pemohon', 'required' => true],
            ['document_name' => 'kk', 'document_label' => 'Kartu Keluarga', 'required' => true],
            ['document_name' => 'bukti_tinggal', 'document_label' => 'Bukti Tempat Tinggal (kontrak/listrik)', 'required' => true],
        ];

        foreach ($documents as $doc) {
            RequiredDocument::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'document_name' => $doc['document_name']],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }

    private function createUsahaFields($jenisSurat)
    {
        $fields = [
            ['field_key' => 'nama_lengkap', 'field_label' => 'Nama Lengkap Pemilik Usaha', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'nik', 'field_label' => 'NIK', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'nama_usaha', 'field_label' => 'Nama Usaha', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'jenis_usaha', 'field_label' => 'Jenis Usaha', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'alamat_usaha', 'field_label' => 'Alamat Usaha', 'field_type' => 'textarea', 'is_required' => true],
            ['field_key' => 'modal_usaha', 'field_label' => 'Modal Usaha (Rp)', 'field_type' => 'number', 'is_required' => true],
            ['field_key' => 'jumlah_karyawan', 'field_label' => 'Jumlah Karyawan', 'field_type' => 'number', 'is_required' => true],
            ['field_key' => 'lama_usaha', 'field_label' => 'Lama Usaha (tahun)', 'field_type' => 'number', 'is_required' => true],
            ['field_key' => 'tujuan', 'field_label' => 'Tujuan Pembuatan Surat Keterangan Usaha', 'field_type' => 'textarea', 'is_required' => true]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'field_key' => $field['field_key']],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        $documents = [
            ['document_name' => 'ktp', 'document_label' => 'KTP Pemilik Usaha', 'required' => true],
            ['document_name' => 'foto_usaha', 'document_label' => 'Foto Tempat Usaha', 'required' => true],
            ['document_name' => 'bukti_usaha', 'document_label' => 'Bukti Usaha (jika ada)', 'required' => false],
        ];

        foreach ($documents as $doc) {
            RequiredDocument::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'document_name' => $doc['document_name']],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }

    private function createNikahFields($jenisSurat)
    {
        $fields = [
            ['field_key' => 'nama_calon_suami', 'field_label' => 'Nama Calon Suami', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'nik_calon_suami', 'field_label' => 'NIK Calon Suami', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tempat_lahir_suami', 'field_label' => 'Tempat Lahir Calon Suami', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tanggal_lahir_suami', 'field_label' => 'Tanggal Lahir Calon Suami', 'field_type' => 'date', 'is_required' => true],
            ['field_key' => 'agama_suami', 'field_label' => 'Agama Calon Suami', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'nama_calon_istri', 'field_label' => 'Nama Calon Istri', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'nik_calon_istri', 'field_label' => 'NIK Calon Istri', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tempat_lahir_istri', 'field_label' => 'Tempat Lahir Calon Istri', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'tanggal_lahir_istri', 'field_label' => 'Tanggal Lahir Calon Istri', 'field_type' => 'date', 'is_required' => true],
            ['field_key' => 'agama_istri', 'field_label' => 'Agama Calon Istri', 'field_type' => 'text', 'is_required' => true],
            ['field_key' => 'alamat_calon_suami', 'field_label' => 'Alamat Calon Suami', 'field_type' => 'textarea', 'is_required' => true],
            ['field_key' => 'alamat_calon_istri', 'field_label' => 'Alamat Calon Istri', 'field_type' => 'textarea', 'is_required' => true],
            ['field_key' => 'status_perkawinan_sebelumnya', 'field_label' => 'Status Perkawinan Sebelumnya', 'field_type' => 'dropdown', 'options' => json_encode(['Belum Menikah', 'Cerai Hidup', 'Cerai Mati']), 'is_required' => true]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'field_key' => $field['field_key']],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        $documents = [
            ['document_name' => 'ktp_suami', 'document_label' => 'KTP Calon Suami', 'required' => true],
            ['document_name' => 'ktp_istri', 'document_label' => 'KTP Calon Istri', 'required' => true],
            ['document_name' => 'kk_suami', 'document_label' => 'Kartu Keluarga Calon Suami', 'required' => true],
            ['document_name' => 'kk_istri', 'document_label' => 'Kartu Keluarga Calon Istri', 'required' => true],
            ['document_name' => 'akta_cerai', 'document_label' => 'Akta Cerai (jika pernah menikah)', 'required' => false],
            ['document_name' => 'foto_bersama', 'document_label' => 'Foto Berdua 4x6', 'required' => true],
        ];

        foreach ($documents as $doc) {
            RequiredDocument::updateOrCreate(
                ['jenis_surat_id' => $jenisSurat->id, 'document_name' => $doc['document_name']],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }
}
