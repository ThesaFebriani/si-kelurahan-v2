@extends('components.layout')

@section('title', 'Detail Kartu Keluarga')
@section('page-title', 'Detail Kartu Keluarga')

@section('content')
<div class="space-y-6">
    <!-- Info KK -->
    <!-- Info KK -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-4">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Kartu Keluarga</p>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $keluarga->no_kk }}</h2>
                <p class="text-slate-500 text-sm">Kepala Keluarga: <span class="font-semibold text-slate-700">{{ $keluarga->kepala_keluarga }}</span></p>
            </div>
            <a href="{{ route('admin.kependudukan.keluarga.edit', $keluarga->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-edit mr-1"></i> Edit Informasi KK
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
            <div class="col-span-1 md:col-span-2 lg:col-span-4">
                <h4 class="font-bold text-slate-800 mb-2 border-b border-slate-100 pb-1">Data Alamat</h4>
            </div>
            <div class="col-span-1 md:col-span-2">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Alamat Lengkap</p>
                <p class="font-medium text-slate-800">{{ $keluarga->alamat_lengkap }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Wilayah RT/RW</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    RT {{ $keluarga->rt->nomor_rt }} / RW {{ $keluarga->rt->rw->nomor_rw }}
                </span>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Kode Pos</p>
                <p class="font-medium text-slate-800">{{ $keluarga->kodepos }}</p>
            </div>
            
            <!-- Detail Wilayah Baru -->
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Desa/Kelurahan</p>
                <p class="font-medium text-slate-800">{{ $keluarga->desa_kelurahan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Kecamatan</p>
                <p class="font-medium text-slate-800">{{ $keluarga->kecamatan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Kabupaten/Kota</p>
                <p class="font-medium text-slate-800">{{ $keluarga->kabupaten_kota ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Provinsi</p>
                <p class="font-medium text-slate-800">{{ $keluarga->provinsi ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Anggota Keluarga -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Daftar Anggota Keluarga</h3>
            <button onclick="document.getElementById('addMemberModal').showModal()" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Tambah Anggota
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">NIK</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Nama Lengkap</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">L/P</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Hubungan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">TTL</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($keluarga->anggotaKeluarga as $member)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-mono text-slate-600 font-medium">{{ $member->nik }}</td>
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $member->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm">{{ $member->jk }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $member->status_hubungan == 'kepala_keluarga' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucwords(str_replace('_', ' ', $member->status_hubungan)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $member->tempat_lahir }}, {{ date('d-m-Y', strtotime($member->tanggal_lahir)) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- Tombol Detail -->
                                <button type="button" 
                                    onclick="showMemberDetail({{ json_encode($member) }})"
                                    class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded transition-colors"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.kependudukan.penduduk.edit', $member->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 bg-yellow-50 hover:bg-yellow-100 p-2 rounded transition-colors"
                                   title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.kependudukan.penduduk.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus anggota keluarga ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition-colors" title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 italic">
                            Belum ada anggota keluarga yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Anggota -->
<dialog id="detailMemberModal" class="modal rounded-xl shadow-2xl p-0 w-full max-w-2xl backdrop:bg-slate-900/50">
    <div class="bg-white">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-lg text-slate-800">Detail Anggota Keluarga</h3>
            <button onclick="document.getElementById('detailMemberModal').close()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6 max-h-[60vh] overflow-y-auto pr-2">
                <!-- Data Pribadi -->
                <div class="col-span-2">
                    <h4 class="text-xs font-bold text-blue-600 uppercase mb-3 border-b border-blue-100 pb-1">Identitas & Data Pribadi</h4>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">NIK</p>
                    <p class="font-mono text-lg font-medium text-slate-800" id="detail-nik">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                    <p class="text-lg font-bold text-slate-800" id="detail-nama">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</p>
                    <p class="text-slate-700 font-medium" id="detail-jk">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Hubungan</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" id="detail-hubungan">-</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</p>
                    <p class="text-slate-700" id="detail-ttl">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Agama</p>
                    <p class="text-slate-700" id="detail-agama">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pendidikan</p>
                    <p class="text-slate-700" id="detail-pendidikan">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pekerjaan</p>
                    <p class="text-slate-700" id="detail-pekerjaan">-</p>
                </div>

                <!-- Status & Dokumen -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-xs font-bold text-blue-600 uppercase mb-3 border-b border-blue-100 pb-1">Status Sipil & Dokumen</h4>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Perkawinan</p>
                    <p class="text-slate-700" id="detail-kawin">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Perkawinan</p>
                    <p class="text-slate-700" id="detail-tgl-kawin">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No. Paspor</p>
                    <p class="text-slate-700 font-mono" id="detail-paspor">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No. KITAP</p>
                    <p class="text-slate-700 font-mono" id="detail-kitap">-</p>
                </div>

                <!-- Orang Tua -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-xs font-bold text-blue-600 uppercase mb-3 border-b border-blue-100 pb-1">Data Orang Tua</h4>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Ayah</p>
                    <p class="text-slate-700" id="detail-ayah">-</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Ibu</p>
                    <p class="text-slate-700" id="detail-ibu">-</p>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button onclick="document.getElementById('detailMemberModal').close()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 font-medium text-sm transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</dialog>

<script>
    function showMemberDetail(member) {
        document.getElementById('detail-nik').textContent = member.nik;
        document.getElementById('detail-nama').textContent = member.nama_lengkap;
        document.getElementById('detail-jk').textContent = member.jk === 'L' ? 'Laki-laki' : 'Perempuan';
        document.getElementById('detail-hubungan').textContent = member.status_hubungan.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        
        const date = new Date(member.tanggal_lahir);
        const formattedDate = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        document.getElementById('detail-ttl').textContent = `${member.tempat_lahir}, ${formattedDate}`;
        
        document.getElementById('detail-agama').textContent = member.agama;
        document.getElementById('detail-pendidikan').textContent = member.pendidikan;
        document.getElementById('detail-pekerjaan').textContent = member.pekerjaan;
        document.getElementById('detail-kawin').textContent = member.status_perkawinan.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

        document.getElementById('detailMemberModal').showModal();
        
        // Handle Tanggal Perkawinan
        if (member.tanggal_perkawinan) {
            const dateKawin = new Date(member.tanggal_perkawinan);
            document.getElementById('detail-tgl-kawin').textContent = dateKawin.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } else {
            document.getElementById('detail-tgl-kawin').textContent = '-';
        }

        document.getElementById('detail-paspor').textContent = member.no_paspor || '-';
        document.getElementById('detail-kitap').textContent = member.no_kitap || '-';
        document.getElementById('detail-ayah').textContent = member.nama_ayah || '-';
        document.getElementById('detail-ibu').textContent = member.nama_ibu || '-';
    }
</script>

<!-- Modal Tambah Anggota -->
<dialog id="addMemberModal" class="modal rounded-xl shadow-2xl p-0 w-full max-w-2xl backdrop:bg-slate-900/50">
    <div class="bg-white">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Tambah Anggota Keluarga</h3>
            <button onclick="document.getElementById('addMemberModal').close()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.kependudukan.penduduk.store') }}" method="POST" class="p-6 bg-slate-50">
            @csrf
            <input type="hidden" name="keluarga_id" value="{{ $keluarga->id }}">
            
            <div class="space-y-4">
                <!-- Data Utama -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
                    <h4 class="text-sm font-bold text-blue-600 mb-3 border-b border-blue-100 pb-2">Identitas Utama</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" required maxlength="16" minlength="16" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan 16 digit NIK">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" required 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Sesuai KTP">
                        </div>
                    </div>
                </div>

                <!-- Data Personal -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
                    <h4 class="text-sm font-bold text-blue-600 mb-3 border-b border-blue-100 pb-2">Data Pribadi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jk" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Hubungan <span class="text-red-500">*</span></label>
                            <select name="status_hubungan" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih...</option>
                                <option value="kepala_keluarga">Kepala Keluarga</option>
                                <option value="istri">Istri</option>
                                <option value="anak">Anak</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" required 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Kota Kelahiran">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" required 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Agama <span class="text-red-500">*</span></label>
                            <select name="agama" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih...</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pendidikan <span class="text-red-500">*</span></label>
                            <input type="text" name="pendidikan" required 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Terakhir">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan" required 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pekerjaan saat ini">
                        </div>
                    </div>
                </div>

                <!-- Status Perkawinan & Imigrasi -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
                    <h4 class="text-sm font-bold text-blue-600 mb-3 border-b border-blue-100 pb-2">Status & Dokumen</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Perkawinan <span class="text-red-500">*</span></label>
                            <select name="status_perkawinan" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih...</option>
                                <option value="belum_kawin">Belum Kawin</option>
                                <option value="kawin">Kawin</option>
                                <option value="cerai_hidup">Cerai Hidup</option>
                                <option value="cerai_mati">Cerai Mati</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Perkawinan</label>
                            <input type="date" name="tanggal_perkawinan" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-500 mt-0.5">* Isi jika status Kawin/Cerai</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. Paspor</label>
                            <input type="text" name="no_paspor" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Nomor Paspor (Jika ada)">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. KITAP</label>
                            <input type="text" name="no_kitap" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Nomor KITAP (Jika ada)">
                        </div>
                    </div>
                </div>

                <!-- Orang Tua -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
                    <h4 class="text-sm font-bold text-blue-600 mb-3 border-b border-blue-100 pb-2">Nama Orang Tua</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Ayah</label>
                            <input type="text" name="nama_ayah" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Nama Ayah Kandung">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Ibu</label>
                            <input type="text" name="nama_ibu" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Nama Ibu Kandung">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="button" onclick="document.getElementById('addMemberModal').close()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-bold shadow-sm transition-colors">Simpan Data</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
