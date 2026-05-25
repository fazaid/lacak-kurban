<div class="space-y-4">
    {{-- Progress summary --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-800 text-sm">Progress Keseluruhan</h3>
            <span class="text-2xl font-bold text-[#1D9E75]">{{ $sacrifice->getProgressPercentage() }}%</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
            <div class="bg-[#1D9E75] h-full rounded-full transition-all duration-700"
                 style="width: {{ $sacrifice->getProgressPercentage() }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-gray-400 mt-2">
            <span>Mulai</span>
            <span>Selesai</span>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 text-sm mb-5">Tahapan Pelaksanaan</h3>

        <div class="relative">
            @foreach($sacrifice->getProgress() as $index => $stage)
            @php
                $isCompleted = $stage['status'] === 'completed';
                $isLast = $loop->last;
            @endphp
            <div class="flex gap-4 {{ !$isLast ? 'pb-6' : '' }}">
                {{-- Line --}}
                @if(!$isLast)
                <div class="absolute left-5 top-10 bottom-0 w-0.5 {{ $isCompleted ? 'bg-[#1D9E75]' : 'bg-gray-200' }}"
                     style="top: {{ ($index * 96) + 40 }}px; height: 56px;"></div>
                @endif

                {{-- Icon --}}
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center z-10
                    {{ $isCompleted ? 'bg-[#1D9E75] text-white' : 'bg-gray-100 text-gray-400' }}">
                    <i class="ti {{ $stage['icon'] }} text-base"></i>
                </div>

                {{-- Content --}}
                <div class="flex-1 pb-2">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h4 class="font-semibold text-sm {{ $isCompleted ? 'text-gray-800' : 'text-gray-400' }}">
                                {{ $stage['label'] }}
                            </h4>
                            <p class="text-xs {{ $isCompleted ? 'text-gray-500' : 'text-gray-300' }} mt-0.5">
                                {{ $stage['description'] }}
                            </p>
                        </div>
                        @if($isCompleted)
                        <span class="flex-shrink-0 inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">
                            <i class="ti ti-check text-xs"></i>
                            Selesai
                        </span>
                        @else
                        <span class="flex-shrink-0 inline-flex items-center gap-1 bg-gray-100 text-gray-400 text-xs font-medium px-2.5 py-1 rounded-full">
                            <i class="ti ti-clock text-xs"></i>
                            Menunggu
                        </span>
                        @endif
                    </div>
                    @if($isCompleted && $stage['date'])
                    <p class="text-xs text-[#1D9E75] font-medium mt-1.5 flex items-center gap-1">
                        <i class="ti ti-calendar-check text-xs"></i>
                        {{ \Carbon\Carbon::parse($stage['date'])->translatedFormat('d F Y') }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
