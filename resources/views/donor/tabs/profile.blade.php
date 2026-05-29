<div class="space-y-4">
    {{-- Donor info --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-user-circle text-[#1D9E75]"></i>
            <h3 class="font-semibold text-gray-800 text-sm">Informasi Donatur</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Nama Donatur</p>
                <p class="text-sm font-semibold text-gray-800">{{ $sacrifice->donor_name }}</p>
            </div>
            @if($sacrifice->donor_email)
            <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->donor_email }}</p>
            </div>
            @endif
            @if($sacrifice->donor_phone)
            <div>
                <p class="text-xs text-gray-500 mb-1">Telepon</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->donor_phone }}</p>
            </div>
            @endif
            {{-- <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Pendaftaran</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->created_at->translatedFormat('d F Y') }}</p>
            </div> --}}
        </div>
    </div>

    {{-- Animal info --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-paw text-[#1D9E75]"></i>
            <h3 class="font-semibold text-gray-800 text-sm">Informasi Hewan Kurban</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Jenis Hewan</p>
                <span class="inline-flex items-center gap-1.5 bg-[#1D9E75]/10 text-[#1D9E75] text-sm font-semibold px-3 py-1 rounded-full">
                    <i class="ti ti-circle-filled text-[8px]"></i>
                    {{ $sacrifice->getAnimalTypeLabel() }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Program</p>
                <p class="text-sm font-semibold text-gray-800">{{ $sacrifice->getSacrificeTypeLabel() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Jenis Berbagi</p>
                <p class="text-sm font-semibold text-gray-800">{{ $sacrifice->getShareLabel() }}</p>
            </div>
            @if($sacrifice->purchase_date)
            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Pembelian</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->purchase_date->translatedFormat('d F Y') }}</p>
            </div>
            @endif
            @if($sacrifice->purchase_location)
            <div>
                <p class="text-xs text-gray-500 mb-1">Lokasi Pembelian</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->purchase_location }}</p>
            </div>
            @endif
            @if($sacrifice->slaughter_location)
            <div class="sm:col-span-2">
                <p class="text-xs text-gray-500 mb-1">Lokasi Penyembelihan</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->slaughter_location }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Beneficiary info --}}
    @if($sacrifice->beneficiary_name || $sacrifice->beneficiary_address)
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <i class="ti ti-heart-handshake text-[#1D9E75]"></i>
            <h3 class="font-semibold text-gray-800 text-sm">Penerima Manfaat</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($sacrifice->beneficiary_name)
            <div>
                <p class="text-xs text-gray-500 mb-1">Nama Penerima</p>
                <p class="text-sm font-semibold text-gray-800">{{ $sacrifice->beneficiary_name }}</p>
            </div>
            @endif
            @if($sacrifice->beneficiary_type)
            <div>
                <p class="text-xs text-gray-500 mb-1">Jenis Penerima</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->beneficiary_type }}</p>
            </div>
            @endif
            @if($sacrifice->beneficiary_address)
            <div class="sm:col-span-2">
                <p class="text-xs text-gray-500 mb-1">Alamat Penerima</p>
                <p class="text-sm text-gray-800">{{ $sacrifice->beneficiary_address }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Notes --}}
    @if($sacrifice->notes)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
        <i class="ti ti-notes text-amber-600 flex-shrink-0 mt-0.5"></i>
        <div>
            <p class="text-xs font-semibold text-amber-700 mb-1">Catatan Panitia</p>
            <p class="text-sm text-amber-800">{{ $sacrifice->notes }}</p>
        </div>
    </div>
    @endif
</div>
