<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermohonanController extends Controller
{
    /** ============================================================
     *  LIST PERMOHONAN
     *  ============================================================ */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PermohonanSurat::with(['jenisSurat', 'timeline'])
            ->where('user_id', $user->id);

        // Filter Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        // Filter Tab Values
        $tab = $request->get('tab', 'semua');
        if ($tab === 'proses') {
            $query->whereIn('status', [
                PermohonanSurat::MENUNGGU_RT,
                PermohonanSurat::DISETUJUI_RT,
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::MENUNGGU_LURAH
            ]);
        } elseif ($tab === 'selesai') {
            $query->whereIn('status', [
                PermohonanSurat::SELESAI,
                PermohonanSurat::DITOLAK_RT,
                PermohonanSurat::DITOLAK_KASI
            ]);
        }

        $permohonan = $query->latest()->paginate(10);

        // Check for Pending Survey (Selesai tapi belum review)
        $pendingSurvey = PermohonanSurat::where('user_id', $user->id)
            ->where('status', PermohonanSurat::SELESAI)
            ->doesntHave('survei')
            ->latest()
            ->first();

        return view('pages.masyarakat.permohonan.index', compact('permohonan', 'pendingSurvey'));
    }

    /** ============================================================
     *  PILIH JENIS SURAT
     *  ============================================================ */
    public function create()
    {
        $jenis_surats = JenisSurat::with(['templateFields', 'requiredDocuments'])
            ->aktif()
            ->get();

        return view('pages.masyarakat.pilih-jenis-surat', compact('jenis_surats'));
    }

    /** ============================================================
     *  TAMPILKAN FORM DINAMIS BERDASARKAN ID JENIS SURAT
     *  ============================================================ */
    public function createForm($jenis_surat_id)
    {
        $jenisSurat = JenisSurat::with([
            'templateFields',
            'requiredDocuments'
        ])->findOrFail($jenis_surat_id);

        return view('pages.masyarakat.form-dinamis', compact('jenisSurat'));
    }

    /** ============================================================
     *  STORE FORM DINAMIS
     *  ============================================================ */
    public function storeDinamis(Request $request, $jenis_surat_id)
    {
        $jenisSurat = JenisSurat::with(['templateFields', 'requiredDocuments'])
            ->findOrFail($jenis_surat_id);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */
        $validationRules = [
            "keterangan_tambahan" => "nullable|string|max:500",
        ];

        // --- VALIDASI FIELD DINAMIS ---
        foreach ($jenisSurat->templateFields as $field) {

            // Pastikan rule default selalu string
            $rule = $field->validation_rules ?? '';

            // Jika select → tambahkan in:option1,option2
            if ($field->field_type === "select" && !empty($field->options_array)) {
                $allowed = implode(',', $field->options_array);
                $rule .= "|in:$allowed";
            }

            // Jika number/currency → tambahkan min:0
            if (in_array($field->field_type, ["number", "currency"])) {
                if (strpos($rule, 'min:') === false) {
                    $rule .= "|min:0";
                }
            }

            // Jika file → pakai aturan khusus
            if ($field->field_type === "file") {
                $rule = ($field->required ? "required" : "nullable")
                    . "|file|mimes:jpg,jpeg,png,pdf|max:2048";
            }

            // Pastikan field required jadi rule Laravel
            if ($field->required && !str_contains($rule, 'required')) {
                $rule = "required|" . $rule;
            }

            $validationRules["data.{$field->field_name}"] = $rule;
        }

        // --- VALIDASI DOKUMEN WAJIB ---
        foreach ($jenisSurat->requiredDocuments as $doc) {
            $validationRules["documents.{$doc->document_name}"] =
                ($doc->required ? "required" : "nullable")
                . "|file|mimes:jpg,jpeg,png,pdf|max:2048";
        }

        // RULE KHUSUS NIK → ubah size menjadi digits
        if (isset($validationRules["data.nik"])) {
            $validationRules["data.nik"] = str_replace("size:16", "digits:16", $validationRules["data.nik"]);
        }

        // CUSTOM MESSAGE
        $messages = [
            "data.*.required" => "Field :attribute wajib diisi.",
            "documents.*.required" => "Dokumen :attribute wajib diunggah.",
            "documents.*.mimes" => "Format dokumen harus JPG, PNG, atau PDF.",
            "documents.*.max" => "Ukuran dokumen maksimal 2MB.",
        ];

        $validated = $request->validate($validationRules, $messages);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PERMOHONAN
        |--------------------------------------------------------------------------
        */
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $dataPemohon = $request->data ?? [];

            // Tambahkan info otomatis
            $dataPemohon["user_id"] = $user->id;
            $dataPemohon["user_name"] = $user->name;
            $dataPemohon["user_email"] = $user->email;
            $dataPemohon["user_telepon"] = $user->telepon;

            if ($user->rt) {
                $dataPemohon["rt"] = $user->rt->nomor_rt;
                if ($user->rt->rw) {
                    $dataPemohon["rw"] = $user->rt->rw->nomor_rw;
                }
            }

            if ($request->keterangan_tambahan) {
                $dataPemohon["keterangan_tambahan"] = $request->keterangan_tambahan;
            }

            // CREATE PERMOHONAN
            $permohonan = PermohonanSurat::create([
                "user_id" => $user->id,
                "jenis_surat_id" => $jenis_surat_id,
                "nomor_tiket" => "TKT-" . Str::upper(Str::random(6)) . "-" . date("Ymd"),
                "status" => PermohonanSurat::MENUNGGU_RT,
                "data_pemohon" => $dataPemohon,
                "tanggal_pengajuan" => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | SIMPAN FILE - DOKUMEN WAJIB
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile("documents")) {
                foreach ($request->file("documents") as $docName => $file) {
                    if ($file && $file->isValid()) {

                        $original = $file->getClientOriginalName();
                        $ext = $file->getClientOriginalExtension();
                        $fileName = Str::slug($docName) . "_" . time() . "." . $ext;

                        $path = $file->storeAs("dokumen-pendukung", $fileName, "local");

                        $permohonan->lampirans()->create([
                            "nama_file" => $original,
                            "file_path" => $path,
                            "file_size" => $file->getSize(),
                            "file_type" => $file->getMimeType(),
                            "keterangan" => "Dokumen wajib: " . $docName,
                        ]);

                        $dataPemohon[$docName] = [
                            "path" => $path,
                            "original_name" => $original,
                            "size" => $file->getSize(),
                            "type" => $file->getMimeType(),
                        ];
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN FILE FIELD DINAMIS
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile("data")) {
                foreach ($jenisSurat->templateFields as $field) {

                    if (
                        $field->field_type === "file" &&
                        $request->hasFile("data.{$field->field_name}")
                    ) {

                        $file = $request->file("data.{$field->field_name}");

                        $original = $file->getClientOriginalName();
                        $ext = $file->getClientOriginalExtension();
                        $fileName = Str::slug($field->field_name) . "_" . time() . "." . $ext;

                        $path = $file->storeAs("form-files", $fileName, "local");

                        $permohonan->lampirans()->create([
                            "nama_file" => $original,
                            "file_path" => $path,
                            "file_size" => $file->getSize(),
                            "file_type" => $file->getMimeType(),
                            "keterangan" => "Lampiran field: " . $field->field_label,
                        ]);

                        $dataPemohon[$field->field_name] = [
                            "path" => $path,
                            "original_name" => $original,
                            "size" => $file->getSize(),
                            "type" => $file->getMimeType(),
                        ];
                    }
                }
            }

            // UPDATE DATA PEMOHON
            $permohonan->update(["data_pemohon" => $dataPemohon]);

            /*
            |--------------------------------------------------------------------------
            | TIMELINE
            |--------------------------------------------------------------------------
            */
            $permohonan->timeline()->create([
                "status" => PermohonanSurat::MENUNGGU_RT,
                "keterangan" => "Permohonan diajukan. Jenis surat: " . $jenisSurat->name,
                "updated_by" => $user->id,
            ]);

            // --- NOTIFIKASI WHATSAPP KE RT ---
            if ($user->rt && $user->rt->ketuaRt && $user->rt->ketuaRt->telepon) {
                $rtName = $user->rt->ketuaRt->name;
                $rtPhone = $user->rt->ketuaRt->telepon;
                $msg = "Halo Bapak/Ibu Ketau RT {$user->rt->nomor_rt} ({$rtName}),\n\n" .
                       "Terdapat permohonan surat baru dari warga Anda:\n" .
                       "Nama: *{$user->name}*\n" .
                       "Jenis Surat: *{$jenisSurat->name}*\n" .
                       "Tanggal: " . now()->format('d-m-Y H:i') . "\n\n" .
                       "Mohon segera login ke aplikasi SI-KELURAHAN untuk melakukan verifikasi.";
                
                \App\Services\WhatsAppService::sendMessage($rtPhone, $msg);
            }

            DB::commit();

            return redirect()
                ->route('masyarakat.permohonan.detail', $permohonan->id)
                ->with("success", [
                    "title" => "Permohonan Berhasil Diajukan!",
                    "message" => "Nomor tiket: " . $permohonan->nomor_tiket,
                ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("PERMOHONAN ERROR: " . $e->getMessage(), [
                "trace" => $e->getTraceAsString(),
                "request" => $request->all(),
            ]);

            return back()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage())
                ->withInput();
        }
    }

    /** ============================================================
     *  DETAIL
     *  ============================================================ */
    public function show($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with([
            "jenisSurat",
            "timeline" => fn($q) => $q->latest(),
            "lampirans",
            "survei"
        ])
            ->where("user_id", $user->id)
            ->findOrFail($id);

        return view('pages.masyarakat.permohonan.detail', compact("permohonan"));
    }
}
