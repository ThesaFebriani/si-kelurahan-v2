<footer class="bg-white border-t border-gray-200 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center space-x-2">
                        <div class="bg-blue-600 text-white p-1.5 rounded">
                        <i class="fas fa-building text-sm"></i>
                    </div>
                    <span class="font-bold text-gray-900 text-lg">SIP Kelurahan</span>
                </div>
                <div class="mt-4 flex space-x-4 text-sm text-gray-500">
                    <p>© {{ date('Y') }} Sistem Informasi Pelayanan Kelurahan. All rights reserved.</p>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('privacy.policy') }}" class="hover:text-blue-600 transition-colors">Kebijakan Privasi</a>
                </div>
            </div>
            
            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </div>
</footer>
