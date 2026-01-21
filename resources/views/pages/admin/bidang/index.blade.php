@extends('components.layout')

@section('title', 'Data Bidang (Kasi)')
@section('page-title', 'Master Data Bidang')
@section('page-description', 'Kelola daftar bidang (seksi) yang tersedia di kelurahan.')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Search (Dummy for UI consistency) -->
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400"></i>
            </div>
            <input type="text" placeholder="Cari bidang..." 
                class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 text-sm transition-all shadow-sm">
        </div>

        <!-- Add Button -->
        <button onclick="openCreateModal()" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-md shadow-blue-200 flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i>
            Tambah Bidang
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase text-xs tracking-wider">
                <tr>
                    <th class="pl-4 pr-3 py-3 font-bold">Kode & Nama</th>
                    <th class="px-3 py-3 font-bold text-center w-px whitespace-nowrap">Status</th>
                    <th class="px-3 py-3 font-bold text-center w-px whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bidangs as $bidang)
                <tr class="hover:bg-slate-50 group transition-colors">
                    <td class="pl-4 pr-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                                <i class="fas fa-briefcase text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-700">{{ $bidang->name }}</div>
                                <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $bidang->code }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-3 text-center w-px whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5"></span>
                            Aktif
                        </span>
                    </td>
                    <td class="px-3 py-3 text-center w-px whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Edit Button -->
                            <button type="button" 
                                onclick="openEditModal('{{ $bidang->id }}', '{{ $bidang->name }}', '{{ $bidang->code }}')"
                                class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" 
                                title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.bidang.destroy', $bidang->id) }}" method="POST" onsubmit="return confirm('Hapus bidang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="h-12 w-12 bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                <i class="fas fa-folder-open text-xl"></i>
                            </div>
                            <p>Belum ada data bidang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- FORM MODAL (Create & Edit) -->
<div id="formModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                
                <div class="bg-white">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fas fa-layer-group text-lg"></i>
                            </div>
                            <h3 class="font-bold text-lg text-slate-800" id="modalTitle">Tambah Bidang Baru</h3>
                        </div>
                        <button onclick="closeFormModal()" class="text-slate-400 hover:text-slate-600 transition-colors bg-white rounded-full p-1 hover:bg-slate-100">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <form id="dynamicForm" method="POST" class="p-6">
                        @csrf
                        <div id="methodField"></div> 

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Nama Bidang <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="formName" required placeholder="Contoh: Kesejahteraan Rakyat" 
                                    class="w-full h-12 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:ring-0 text-sm shadow-sm transition-all hover:border-slate-300 px-4 placeholder-slate-400 font-medium text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kode Bidang <span class="text-red-500">*</span></label>
                                <input type="text" name="code" id="formCode" required placeholder="Contoh: kesra" 
                                    class="w-full h-12 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:ring-0 text-sm shadow-sm font-mono bg-slate-50 transition-all hover:border-slate-300 px-4 text-slate-600">
                                <p class="text-[10px] text-slate-400 mt-1.5 font-medium ml-1">Gunakan huruf kecil, tanpa spasi (Unique).</p>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-4">
                            <button type="button" onclick="closeFormModal()" class="w-full h-12 justify-center rounded-lg bg-white border-2 border-slate-200 px-4 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-800 hover:border-slate-300 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="w-full h-12 justify-center rounded-lg bg-blue-600 px-4 text-sm font-bold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition-all transform hover:-translate-y-0.5">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('formModal');
    const backdrop = document.getElementById('modalBackdrop');
    const panel = document.getElementById('modalPanel');
    const form = document.getElementById('dynamicForm');
    const title = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    
    // Inputs
    const inputName = document.getElementById('formName');
    const inputCode = document.getElementById('formCode');

    function openCreateModal() {
        // Reset Form
        form.reset();
        form.action = "{{ route('admin.bidang.store') }}";
        methodField.innerHTML = ''; // No PUT method needed
        title.innerText = 'Tambah Bidang Baru';
        inputCode.readOnly = false;
        inputCode.classList.remove('text-slate-500');

        showModal();
    }

    function openEditModal(id, name, code) {
        // Set Values
        inputName.value = name;
        inputCode.value = code;
        
        // Setup Form for Edit
        form.action = `/admin/bidang/${id}`;
        methodField.innerHTML = '@method("PUT")';
        title.innerText = 'Edit Data Bidang';
        
        // Optional: Make code readonly if unique constraint is strict/risky to change
        // inputCode.readOnly = true; 
        
        showModal();
    }

    function showModal() {
        modal.classList.remove('hidden');
        // Animation delay
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        }, 10);
    }

    function closeFormModal() {
        // Hide animation
        backdrop.classList.add('opacity-0');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endpush

@endsection
