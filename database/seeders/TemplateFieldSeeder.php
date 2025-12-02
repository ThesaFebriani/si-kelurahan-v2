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
        echo "Memulai TemplateFieldSeeder...\n";

        // 1. SKTM (Surat Keterangan Tidak Mampu)
        $sktm = JenisSurat::where('kode_surat', 'SKTM')->first();
        if ($sktm) {
            $this->createSKTMFields($sktm);
            echo "✅ Fields untuk SKTM created/updated\n";
        } else {
            echo "❌ SKTM tidak ditemukan\n";
        }

        // 2. Surat Domisili
        $domisili = JenisSurat::where('kode_surat', 'DOM')->first();
        if ($domisili) {
            $this->createDomisiliFields($domisili);
            echo "✅ Fields untuk Domisili created/updated\n";
        } else {
            echo "❌ DOM tidak ditemukan\n";
        }

        // 3. Surat Keterangan Usaha
        $usaha = JenisSurat::where('kode_surat', 'SKU')->first();
        if ($usaha) {
            $this->createUsahaFields($usaha);
            echo "✅ Fields untuk Keterangan Usaha created/updated\n";
        } else {
            echo "❌ SKU tidak ditemukan\n";
        }

        // 4. Pengantar Nikah
        $nikah = JenisSurat::where('kode_surat', 'N1')->first();
        if ($nikah) {
            $this->createNikahFields($nikah);
            echo "✅ Fields untuk Pengantar Nikah created/updated\n";
        } else {
            echo "❌ N1 tidak ditemukan\n";
        }

        echo "Seeder TemplateField selesai! 🎉\n";
    }

    private function createSKTMFields($jenisSurat)
    {
        $fields = [
            [
                'field_name' => 'nama_lengkap',
                'field_label' => 'Nama Lengkap',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:255',
                'order' => 1
            ],
            [
                'field_name' => 'nik',
                'field_label' => 'NIK',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|size:16',
                'order' => 2
            ],
            [
                'field_name' => 'tempat_lahir',
                'field_label' => 'Tempat Lahir',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:100',
                'order' => 3
            ],
            [
                'field_name' => 'tanggal_lahir',
                'field_label' => 'Tanggal Lahir',
                'field_type' => 'date',
                'required' => true,
                'validation_rules' => 'required|date',
                'order' => 4
            ],
            [
                'field_name' => 'jenis_kelamin',
                'field_label' => 'Jenis Kelamin',
                'field_type' => 'select',
                'options' => json_encode(['Laki-laki', 'Perempuan']),
                'required' => true,
                'validation_rules' => 'required|string',
                'order' => 5
            ],
            [
                'field_name' => 'alamat',
                'field_label' => 'Alamat Lengkap',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 6
            ],
            [
                'field_name' => 'jumlah_tanggungan',
                'field_label' => 'Jumlah Tanggungan Keluarga',
                'field_type' => 'number',
                'required' => true,
                'validation_rules' => 'required|numeric|min:1',
                'order' => 7
            ],
            [
                'field_name' => 'penghasilan',
                'field_label' => 'Penghasilan per Bulan',
                'field_type' => 'number',
                'required' => true,
                'validation_rules' => 'required|numeric|min:0',
                'order' => 8
            ],
            [
                'field_name' => 'pekerjaan',
                'field_label' => 'Pekerjaan',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:100',
                'order' => 9
            ],
            [
                'field_name' => 'tujuan',
                'field_label' => 'Tujuan Pembuatan SKTM',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 10
            ]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'field_name' => $field['field_name']
                ],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        // Required Documents untuk SKTM
        $documents = [
            ['document_name' => 'ktp', 'document_label' => 'KTP Pemohon', 'required' => true],
            ['document_name' => 'kk', 'document_label' => 'Kartu Keluarga', 'required' => true],
            ['document_name' => 'slip_gaji', 'document_label' => 'Slip Gaji/Surat Keterangan Penghasilan', 'required' => false],
            ['document_name' => 'foto_rumah', 'document_label' => 'Foto Kondisi Rumah', 'required' => false],
        ];

        foreach ($documents as $doc) {
            RequiredDocument::updateOrCreate(
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'document_name' => $doc['document_name']
                ],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }

    private function createDomisiliFields($jenisSurat)
    {
        $fields = [
            [
                'field_name' => 'nama_lengkap',
                'field_label' => 'Nama Lengkap',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:255',
                'order' => 1
            ],
            [
                'field_name' => 'nik',
                'field_label' => 'NIK',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|size:16',
                'order' => 2
            ],
            [
                'field_name' => 'tempat_lahir',
                'field_label' => 'Tempat Lahir',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:100',
                'order' => 3
            ],
            [
                'field_name' => 'tanggal_lahir',
                'field_label' => 'Tanggal Lahir',
                'field_type' => 'date',
                'required' => true,
                'validation_rules' => 'required|date',
                'order' => 4
            ],
            [
                'field_name' => 'alamat_sebelumnya',
                'field_label' => 'Alamat Sebelumnya',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 5
            ],
            [
                'field_name' => 'alamat_sekarang',
                'field_label' => 'Alamat Sekarang (Domisili)',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 6
            ],
            [
                'field_name' => 'lama_tinggal',
                'field_label' => 'Lama Tinggal (bulan)',
                'field_type' => 'number',
                'required' => true,
                'validation_rules' => 'required|numeric|min:1',
                'order' => 7
            ],
            [
                'field_name' => 'tujuan',
                'field_label' => 'Tujuan Pembuatan Surat Domisili',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 8
            ]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'field_name' => $field['field_name']
                ],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        // Required Documents untuk Domisili
        $documents = [
            ['document_name' => 'ktp', 'document_label' => 'KTP Pemohon', 'required' => true],
            ['document_name' => 'kk', 'document_label' => 'Kartu Keluarga', 'required' => true],
            ['document_name' => 'bukti_tinggal', 'document_label' => 'Bukti Tempat Tinggal (kontrak/listrik)', 'required' => true],
        ];

        foreach ($documents as $doc) {
            RequiredDocument::updateOrCreate(
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'document_name' => $doc['document_name']
                ],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }

    private function createUsahaFields($jenisSurat)
    {
        $fields = [
            [
                'field_name' => 'nama_lengkap',
                'field_label' => 'Nama Lengkap Pemilik Usaha',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:255',
                'order' => 1
            ],
            [
                'field_name' => 'nik',
                'field_label' => 'NIK',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|size:16',
                'order' => 2
            ],
            [
                'field_name' => 'nama_usaha',
                'field_label' => 'Nama Usaha',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:255',
                'order' => 3
            ],
            [
                'field_name' => 'jenis_usaha',
                'field_label' => 'Jenis Usaha',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:100',
                'order' => 4
            ],
            [
                'field_name' => 'alamat_usaha',
                'field_label' => 'Alamat Usaha',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 5
            ],
            [
                'field_name' => 'modal_usaha',
                'field_label' => 'Modal Usaha (Rp)',
                'field_type' => 'number',
                'required' => true,
                'validation_rules' => 'required|numeric|min:0',
                'order' => 6
            ],
            [
                'field_name' => 'jumlah_karyawan',
                'field_label' => 'Jumlah Karyawan',
                'field_type' => 'number',
                'required' => true,
                'validation_rules' => 'required|numeric|min:0',
                'order' => 7
            ],
            [
                'field_name' => 'lama_usaha',
                'field_label' => 'Lama Usaha (tahun)',
                'field_type' => 'number',
                'required' => true,
                'validation_rules' => 'required|numeric|min:0',
                'order' => 8
            ],
            [
                'field_name' => 'tujuan',
                'field_label' => 'Tujuan Pembuatan Surat Keterangan Usaha',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 9
            ]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'field_name' => $field['field_name']
                ],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        // Required Documents untuk Surat Usaha
        $documents = [
            ['document_name' => 'ktp', 'document_label' => 'KTP Pemilik Usaha', 'required' => true],
            ['document_name' => 'foto_usaha', 'document_label' => 'Foto Tempat Usaha', 'required' => true],
            ['document_name' => 'bukti_usaha', 'document_label' => 'Bukti Usaha (jika ada)', 'required' => false],
        ];

        foreach ($documents as $doc) {
            RequiredDocument::updateOrCreate(
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'document_name' => $doc['document_name']
                ],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }

    private function createNikahFields($jenisSurat)
    {
        $fields = [
            // Data Calon Suami
            [
                'field_name' => 'nama_calon_suami',
                'field_label' => 'Nama Calon Suami',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:255',
                'order' => 1
            ],
            [
                'field_name' => 'nik_calon_suami',
                'field_label' => 'NIK Calon Suami',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|size:16',
                'order' => 2
            ],
            [
                'field_name' => 'tempat_lahir_suami',
                'field_label' => 'Tempat Lahir Calon Suami',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:100',
                'order' => 3
            ],
            [
                'field_name' => 'tanggal_lahir_suami',
                'field_label' => 'Tanggal Lahir Calon Suami',
                'field_type' => 'date',
                'required' => true,
                'validation_rules' => 'required|date',
                'order' => 4
            ],
            [
                'field_name' => 'agama_suami',
                'field_label' => 'Agama Calon Suami',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:50',
                'order' => 5
            ],

            // Data Calon Istri
            [
                'field_name' => 'nama_calon_istri',
                'field_label' => 'Nama Calon Istri',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:255',
                'order' => 6
            ],
            [
                'field_name' => 'nik_calon_istri',
                'field_label' => 'NIK Calon Istri',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|size:16',
                'order' => 7
            ],
            [
                'field_name' => 'tempat_lahir_istri',
                'field_label' => 'Tempat Lahir Calon Istri',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:100',
                'order' => 8
            ],
            [
                'field_name' => 'tanggal_lahir_istri',
                'field_label' => 'Tanggal Lahir Calon Istri',
                'field_type' => 'date',
                'required' => true,
                'validation_rules' => 'required|date',
                'order' => 9
            ],
            [
                'field_name' => 'agama_istri',
                'field_label' => 'Agama Calon Istri',
                'field_type' => 'text',
                'required' => true,
                'validation_rules' => 'required|string|max:50',
                'order' => 10
            ],

            // Data Tambahan
            [
                'field_name' => 'alamat_calon_suami',
                'field_label' => 'Alamat Calon Suami',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 11
            ],
            [
                'field_name' => 'alamat_calon_istri',
                'field_label' => 'Alamat Calon Istri',
                'field_type' => 'textarea',
                'required' => true,
                'validation_rules' => 'required|string|max:500',
                'order' => 12
            ],
            [
                'field_name' => 'status_perkawinan_sebelumnya',
                'field_label' => 'Status Perkawinan Sebelumnya',
                'field_type' => 'select',
                'options' => json_encode(['Belum Menikah', 'Cerai Hidup', 'Cerai Mati']),
                'required' => true,
                'validation_rules' => 'required|string',
                'order' => 13
            ]
        ];

        foreach ($fields as $field) {
            TemplateField::updateOrCreate(
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'field_name' => $field['field_name']
                ],
                array_merge($field, ['jenis_surat_id' => $jenisSurat->id])
            );
        }

        // Required Documents untuk Pengantar Nikah
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
                [
                    'jenis_surat_id' => $jenisSurat->id,
                    'document_name' => $doc['document_name']
                ],
                array_merge($doc, ['jenis_surat_id' => $jenisSurat->id])
            );
        }
    }
}
