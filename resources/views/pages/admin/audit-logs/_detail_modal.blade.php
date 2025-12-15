<div class="mb-4">
    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
        Detail Aktivitas
    </h3>
    <div class="mt-2 text-sm text-gray-500">
        <p><strong>Aksi:</strong> {{ ucfirst($auditLog->action) }}</p>
        <p><strong>Waktu:</strong> {{ $auditLog->created_at->format('d M Y H:i:s') }}</p>
        <p><strong>User Agent:</strong> {{ $auditLog->user_agent }}</p>
    </div>
</div>

<div class="border-t border-gray-200 mt-4 pt-4">
    @if($auditLog->action === 'update' && !empty($auditLog->old_data) && !empty($auditLog->new_data))
        <h4 class="text-sm font-bold mb-2">Perubahan Data:</h4>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase">Kolom</th>
                        <th class="px-3 py-2 text-left font-medium text-red-500 uppercase">Sebelum</th>
                        <th class="px-3 py-2 text-left font-medium text-green-500 uppercase">Sesudah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($auditLog->new_data as $key => $newVal)
                        @php 
                            $oldVal = $auditLog->old_data[$key] ?? '-';
                            // Normalize output for arrays or objects
                            if(is_array($newVal)) $newVal = json_encode($newVal);
                            if(is_array($oldVal)) $oldVal = json_encode($oldVal);
                        @endphp
                        <tr>
                            <td class="px-3 py-2 font-mono text-gray-700">{{ $key }}</td>
                            <td class="px-3 py-2 text-red-600 bg-red-50/50">{{ $oldVal }}</td>
                            <td class="px-3 py-2 text-green-600 bg-green-50/50">{{ $newVal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($auditLog->action === 'create' && !empty($auditLog->new_data))
        <h4 class="text-sm font-bold mb-2">Data Baru:</h4>
        <pre class="bg-gray-50 p-3 rounded text-xs overflow-auto max-h-60">{{ json_encode($auditLog->new_data, JSON_PRETTY_PRINT) }}</pre>
    @elseif($auditLog->action === 'delete' && !empty($auditLog->old_data))
        <h4 class="text-sm font-bold mb-2">Data Dihapus:</h4>
        <pre class="bg-red-50 p-3 rounded text-xs overflow-auto max-h-60">{{ json_encode($auditLog->old_data, JSON_PRETTY_PRINT) }}</pre>
    @else
        <p class="text-sm text-gray-500 italic">Tidak ada detail data yang terekam.</p>
    @endif
</div>
