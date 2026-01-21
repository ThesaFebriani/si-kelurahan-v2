<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Lampiran;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Serve secure document
     */
    public function show($filename)
    {
        // 1. Check if user is authenticated
        if (!Auth::check()) {
            abort(403, 'Unauthorized access.');
        }

        $user = Auth::user();
        
        // 2. Find the file record in DB to check ownership (Optional but better for security)
        // Trying to find by filename in Lampiran. 
        // Note: filename might be stored with directory prefix in DB, or just filename.
        // Let's rely on string matching.
        $lampiran = Lampiran::where('file_path', 'like', '%' . $filename . '%')->first();

        // 3. Authorization Logic
        $isAuthorized = false;
        
        // Admin/Officials Access
        $role = $user->role->name ?? '';
        if (in_array($role, ['admin', 'lurah', 'kasi', 'rt'])) {
            $isAuthorized = true;
        }

        // Owner Access Logic
        if (!$isAuthorized) {
            // A. Check Lampiran (Uploads)
            $lampiran = Lampiran::where('file_path', 'like', '%' . $filename . '%')->first();
            if ($lampiran && $lampiran->permohonan && $lampiran->permohonan->user_id == $user->id) {
                $isAuthorized = true;
            }

            // B. Check Surat Pengantar RT
            if (!$isAuthorized) {
                $permohonan = \App\Models\PermohonanSurat::where('file_surat_pengantar_rt', 'like', '%' . $filename . '%')->first();
                if ($permohonan && $permohonan->user_id == $user->id) {
                    $isAuthorized = true;
                }
            }

            // C. Check Surat Final (Surat Kelurahan)
            if (!$isAuthorized) {
                $surat = \App\Models\Surat::where('file_path', 'like', '%' . $filename . '%')->first();
                // Validasi owner via permohonan relationship
                if ($surat && $surat->permohonanSurat && $surat->permohonanSurat->user_id == $user->id) {
                     $isAuthorized = true;
                }
            }
        }

        if (!$isAuthorized) {
             abort(403, 'Anda tidak memiliki hak akses ke dokumen ini.');
        }

        // 4. Check physical file existence (Search in all private folders)
        $folders = ['dokumen-pendukung', 'form-files', 'surat-pengantar', 'surat-final'];
        $path = null;

        // Cek direct path first (jika filename sudah mengandung folder)
        if (Storage::disk('local')->exists($filename)) {
            $path = $filename;
        } else {
             // Cek subfolders
            foreach ($folders as $folder) {
                $candidates = [
                    $folder . '/' . $filename,
                    $folder . '/' . basename($filename)
                ];
                foreach($candidates as $candidate) {
                    if (Storage::disk('local')->exists($candidate)) {
                        $path = $candidate;
                        break 2;
                    }
                }
            }
        }

        if (!$path) {
            abort(404, 'File fisik tidak ditemukan.');
        }

        // 5. Serve File for Preview (Inline)
        return response()->file(Storage::disk('local')->path($path));
    }
}
