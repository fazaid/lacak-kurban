@extends('layouts.admin')

@section('title', 'Statistik')
@section('page-title', 'Statistik Kurban')
@section('breadcrumb', 'Admin / Statistik')

@section('content')
<div class="space-y-6 pt-2">

    {{-- ── Summary cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Kurban</p>
                <div class="w-9 h-9 bg-[#1D9E75]/10 rounded-lg flex items-center justify-center">
                    <i class="ti ti-moon-stars text-[#1D9E75]"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-800">{{ $total }}</p>
            <p class="text-xs text-gray-400 mt-1">kurban terdaftar</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Donasi</p>
                <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="ti ti-coin text-emerald-600"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-800">
                Rp {{ number_format($totalDonated / 1_000_000, 1, ',', '.') }}jt
            </p>
            <p class="text-xs text-gray-400 mt-1">
                rata-rata Rp {{ number_format($avgPrice / 1_000_000, 1, ',', '.') }}jt/kurban
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai</p>
                <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="ti ti-circle-check text-green-600"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-800">{{ $completedCount }}</p>
            <div class="mt-2 flex items-center gap-2">
                <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                    <div class="bg-green-500 h-full rounded-full" style="width: {{ $total > 0 ? round($completedCount / $total * 100) : 0 }}%"></div>
                </div>
                <span class="text-xs text-gray-400">{{ $total > 0 ? round($completedCount / $total * 100) : 0 }}%</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Belum Selesai</p>
                <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                    <i class="ti ti-clock text-amber-600"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-800">{{ $inProgressCount + $pendingCount }}</p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $inProgressCount }} berjalan · {{ $pendingCount }} menunggu
            </p>
        </div>

    </div>

    {{-- ── Charts row ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Pie: by animal type --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Jenis Hewan</h3>
            <p class="text-xs text-gray-400 mb-5">Distribusi kurban berdasarkan jenis hewan</p>
            <div class="relative h-56 flex items-center justify-center">
                <canvas id="chart-animal-type"></canvas>
            </div>
            {{-- Legend --}}
            <div class="mt-4 space-y-2">
                @php
                    $animalColors = ['unta' => '#f97316', 'sapi' => '#1D9E75', 'domba' => '#3b82f6', 'default' => '#8b5cf6'];
                    $animalLabels = ['unta' => 'Unta', 'sapi' => 'Sapi', 'domba' => 'Domba'];
                @endphp
                @foreach($byAnimalType as $row)
                @php $color = $animalColors[$row->animal_type] ?? $animalColors['default']; @endphp
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $color }}"></span>
                        <span class="text-gray-700 font-medium">{{ $animalLabels[$row->animal_type] ?? ucfirst($row->animal_type) }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-400 text-xs">Rp {{ number_format($row->total_price / 1_000_000, 1, ',', '.') }}jt</span>
                        <span class="font-semibold text-gray-800 w-6 text-right">{{ $row->count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bar: status distribution --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Status Distribusi</h3>
            <p class="text-xs text-gray-400 mb-5">Jumlah kurban per status pelaksanaan</p>
            <div class="relative h-56">
                <canvas id="chart-status"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-3 text-center">
                <div class="bg-green-50 rounded-lg p-3">
                    <p class="text-xl font-black text-green-700">{{ $completedCount }}</p>
                    <p class="text-xs text-green-600 font-medium mt-0.5">Selesai</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-3">
                    <p class="text-xl font-black text-blue-700">{{ $inProgressCount }}</p>
                    <p class="text-xs text-blue-600 font-medium mt-0.5">Berjalan</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-3">
                    <p class="text-xl font-black text-amber-700">{{ $pendingCount }}</p>
                    <p class="text-xs text-amber-600 font-medium mt-0.5">Menunggu</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Timeline: completion line chart ────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Timeline Pelaksanaan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Jumlah penyembelihan dan kurban selesai per bulan</p>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-8 h-0.5 bg-[#1D9E75] rounded inline-block"></span>
                    Penyembelihan
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-8 h-0.5 bg-blue-400 rounded inline-block"></span>
                    Selesai
                </span>
            </div>
        </div>
        @if($timelineLabels->isEmpty())
        <div class="h-48 flex items-center justify-center text-gray-400 text-sm">
            <div class="text-center">
                <i class="ti ti-chart-line text-3xl mb-2 block"></i>
                Belum ada data untuk ditampilkan
            </div>
        </div>
        @else
        <div class="relative h-64">
            <canvas id="chart-timeline"></canvas>
        </div>
        @endif
    </div>

    {{-- ── Donation breakdown table ─────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Rincian Donasi per Jenis Hewan</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">
                    <th class="px-5 py-3">Jenis Hewan</th>
                    <th class="px-5 py-3 text-right">Jumlah</th>
                    <th class="px-5 py-3 text-right">Total Donasi</th>
                    <th class="px-5 py-3 text-right">Rata-rata</th>
                    <th class="px-5 py-3">Proporsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($byAnimalType as $row)
                @php
                    $pct   = $total > 0 ? round($row->count / $total * 100) : 0;
                    $color = $animalColors[$row->animal_type] ?? $animalColors['default'];
                    $avg   = $row->count > 0 ? $row->total_price / $row->count : 0;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $color }}"></span>
                            <span class="font-medium text-gray-800">{{ $row->animal_type }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right font-semibold text-gray-800">{{ $row->count }}</td>
                    <td class="px-5 py-4 text-right text-gray-700">
                        Rp {{ number_format($row->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 text-right text-gray-500">
                        Rp {{ number_format($avg, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 max-w-24 bg-gray-100 rounded-full h-2">
                                <div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $color }}"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-8">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
                {{-- Totals row --}}
                <tr class="bg-gray-50 font-semibold">
                    <td class="px-5 py-3 text-gray-700">Total</td>
                    <td class="px-5 py-3 text-right text-gray-800">{{ $total }}</td>
                    <td class="px-5 py-3 text-right text-[#1D9E75]">
                        Rp {{ number_format($totalDonated, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3 text-right text-gray-500">
                        Rp {{ number_format($avgPrice, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ── Shared defaults ──────────────────────────────────────────────────
    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    Chart.defaults.color       = '#6b7280';

    const fmt = v => 'Rp ' + (v / 1_000_000).toFixed(1) + 'jt';

    // ── 1. Doughnut: animal type ─────────────────────────────────────────
    const animalData = @json($byAnimalType);
    const animalColors = { Sapi: '#1D9E75', Kambing: '#3b82f6', Domba: '#f59e0b' };
    const defaultColor = '#8b5cf6';

    new Chart(document.getElementById('chart-animal-type'), {
        type: 'doughnut',
        data: {
            labels: animalData.map(r => r.animal_type),
            datasets: [{
                data:            animalData.map(r => r.count),
                backgroundColor: animalData.map(r => animalColors[r.animal_type] ?? defaultColor),
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const row   = animalData[ctx.dataIndex];
                            const total = animalData.reduce((s, r) => s + r.count, 0);
                            const pct   = total > 0 ? Math.round(row.count / total * 100) : 0;
                            return ` ${row.count} kurban (${pct}%) · ${fmt(row.total_price)}`;
                        },
                    },
                },
            },
        },
    });

    // ── 2. Bar: status distribution ──────────────────────────────────────
    new Chart(document.getElementById('chart-status'), {
        type: 'bar',
        data: {
            labels: ['Selesai', 'Sedang Berjalan', 'Menunggu'],
            datasets: [{
                label: 'Jumlah Kurban',
                data:  [{{ $completedCount }}, {{ $inProgressCount }}, {{ $pendingCount }}],
                backgroundColor: ['#1D9E75', '#3b82f6', '#f59e0b'],
                borderRadius: 6,
                borderSkipped: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.parsed.y} kurban` },
                },
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: '#f3f4f6' },
                },
            },
        },
    });

    // ── 3. Line: completion timeline ─────────────────────────────────────
    const timelineCanvas = document.getElementById('chart-timeline');
    if (timelineCanvas) {
        new Chart(timelineCanvas, {
            type: 'line',
            data: {
                labels: @json($timelineLabels),
                datasets: [
                    {
                        label: 'Penyembelihan',
                        data:  @json($timelineSlaughter),
                        borderColor:     '#1D9E75',
                        backgroundColor: 'rgba(29,158,117,0.08)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#1D9E75',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Selesai',
                        data:  @json($timelineCompleted),
                        borderColor:     '#60a5fa',
                        backgroundColor: 'rgba(96,165,250,0.06)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#60a5fa',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.35,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} kurban` },
                    },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#f3f4f6' },
                    },
                },
            },
        });
    }
})();
</script>
@endpush
