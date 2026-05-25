<?php

namespace App\Http\Controllers;

use App\Models\Sacrifice;
use App\Models\SacrificeGallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SacrificeAdminController extends Controller
{
    public function dashboard(): View
    {
        $total = Sacrifice::count();
        $completed = Sacrifice::where('status_purchase', 'completed')
            ->where('status_slaughter', 'completed')
            ->where('status_distribution', 'completed')
            ->where('status_report', 'completed')
            ->count();
        $pending = Sacrifice::where('status_purchase', 'pending')
            ->where('status_slaughter', 'pending')
            ->where('status_distribution', 'pending')
            ->where('status_report', 'pending')
            ->count();
        $inProgress = $total - $completed - $pending;

        $stats = compact('total', 'completed', 'pending', 'inProgress');
        $recentSacrifices = Sacrifice::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentSacrifices'));
    }

    public function index(Request $request): View
    {
        $query = Sacrifice::query();

        // Full-text search: reference code, donor name, email, phone
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('reference_code', 'like', "%{$term}%")
                  ->orWhere('donor_name',    'like', "%{$term}%")
                  ->orWhere('donor_email',   'like', "%{$term}%")
                  ->orWhere('donor_phone',   'like', "%{$term}%");
            });
        }

        // Donor name filter (standalone, additive with search)
        if ($request->filled('donor_name')) {
            $query->where('donor_name', 'like', '%' . $request->input('donor_name') . '%');
        }

        // Sacrifice type filter
        if ($request->filled('sacrifice_type')) {
            $query->where('sacrifice_type', $request->input('sacrifice_type'));
        }

        // Animal type filter
        if ($request->filled('animal_type')) {
            $query->where('animal_type', $request->input('animal_type'));
        }

        // Status filter: completed / in_progress / pending
        if ($request->filled('status')) {
            match ($request->input('status')) {
                'completed' => $query
                    ->where('status_purchase',     'completed')
                    ->where('status_slaughter',    'completed')
                    ->where('status_distribution', 'completed')
                    ->where('status_report',       'completed'),

                'in_progress' => $query
                    ->where(fn ($q) => $q
                        ->where('status_purchase',     'completed')
                        ->orWhere('status_slaughter',    'completed')
                        ->orWhere('status_distribution', 'completed')
                        ->orWhere('status_report',       'completed'))
                    ->where(fn ($q) => $q
                        ->where('status_purchase',     'pending')
                        ->orWhere('status_slaughter',    'pending')
                        ->orWhere('status_distribution', 'pending')
                        ->orWhere('status_report',       'pending')),

                'pending' => $query
                    ->where('status_purchase',     'pending')
                    ->where('status_slaughter',    'pending')
                    ->where('status_distribution', 'pending')
                    ->where('status_report',       'pending'),

                default => null,
            };
        }

        // Date range filter on registration date (created_at)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $sacrifices = $query->latest()->paginate(15)->withQueryString();
        $total      = Sacrifice::count();

        return view('admin.sacrifices.index', compact('sacrifices', 'total'));
    }

    public function create(): View
    {
        return view('admin.sacrifices.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSacrificeInput($request);
        $validated['reference_code'] = $this->generateReferenceCode();

        $sacrifice = Sacrifice::create($validated);

        return redirect()->route('admin.sacrifices.show', $sacrifice)
            ->with('success', "Data kurban berhasil ditambahkan dengan kode {$sacrifice->reference_code}");
    }

    public function edit(Sacrifice $sacrifice): View
    {
        return view('admin.sacrifices.edit', compact('sacrifice'));
    }

    public function update(Request $request, Sacrifice $sacrifice): RedirectResponse
    {
        $validated = $this->validateSacrificeInput($request);
        $sacrifice->update($validated);

        return redirect()->route('admin.sacrifices.show', $sacrifice)
            ->with('success', 'Data kurban berhasil diperbarui.');
    }

    public function show(Sacrifice $sacrifice): View
    {
        $sacrifice->load('galleries');
        $photosByCategory = $sacrifice->getPhotosByCategories();
        $categories = [
            'hewan' => 'Foto Hewan',
            'penyembelihan' => 'Penyembelihan',
            'pengemasan' => 'Pengemasan',
            'distribusi' => 'Distribusi',
        ];

        return view('admin.sacrifices.show', compact('sacrifice', 'photosByCategory', 'categories'));
    }

    public function destroy(Sacrifice $sacrifice): RedirectResponse
    {
        foreach ($sacrifice->galleries as $gallery) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $sacrifice->delete();

        return redirect()->route('admin.sacrifices.index')
            ->with('success', 'Data kurban berhasil dihapus.');
    }

    public function editProgress(Sacrifice $sacrifice): View
    {
        return view('admin.sacrifices.edit-progress', compact('sacrifice'));
    }

    public function updateProgress(Request $request, Sacrifice $sacrifice): RedirectResponse
    {
        $validated = $request->validate([
            'status_purchase' => ['required', 'in:pending,completed'],
            'date_purchase_completed' => ['nullable', 'date'],
            'status_slaughter' => ['required', 'in:pending,completed'],
            'date_slaughter_completed' => ['nullable', 'date'],
            'status_distribution' => ['required', 'in:pending,completed'],
            'date_distribution_completed' => ['nullable', 'date'],
            'status_report' => ['required', 'in:pending,completed'],
            'date_report_completed' => ['nullable', 'date'],
        ]);

        $sacrifice->update($validated);

        return redirect()->route('admin.sacrifices.show', $sacrifice)
            ->with('success', 'Progress kurban berhasil diperbarui.');
    }

    public function uploadPhotos(Request $request, Sacrifice $sacrifice): RedirectResponse
    {
        $request->validate([
            'photos' => ['required', 'array', 'max:10'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'photo_category' => ['required', 'in:hewan,penyembelihan,pengemasan,distribusi'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $category = $request->input('photo_category');
        $caption = $request->input('caption');
        $uploaded = 0;

        foreach ($request->file('photos') as $photo) {
            $fileName = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $filePath = "galleries/{$sacrifice->id}/{$fileName}";

            Storage::disk('public')->put($filePath, file_get_contents($photo->getRealPath()));

            SacrificeGallery::create([
                'sacrifice_id' => $sacrifice->id,
                'photo_category' => $category,
                'file_path' => $filePath,
                'file_name' => $photo->getClientOriginalName(),
                'file_size' => $photo->getSize(),
                'mime_type' => $photo->getMimeType(),
                'caption' => $caption,
                'order' => 0,
            ]);

            $uploaded++;
        }

        return back()->with('success', "{$uploaded} foto berhasil diunggah.");
    }

    public function deletePhoto(SacrificeGallery $photo): RedirectResponse
    {
        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function generateCertificate(Sacrifice $sacrifice): RedirectResponse
    {
        $sacrifice->update([
            'certificate_generated_at' => now(),
        ]);

        return back()->with('success', 'Sertifikat berhasil dibuat dan siap diunduh oleh donatur.');
    }

    public function export(): StreamedResponse
    {
        $sacrifices = Sacrifice::all();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-kurban-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($sacrifices) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Kode Referensi', 'Jenis Kurban', 'Nama Donatur', 'Email', 'Telepon',
                'Jenis Hewan', 'Harga Hewan', 'Jenis Berbagi', 'Rasio',
                'Tanggal Pembelian', 'Lokasi Pembelian',
                'Lokasi Penyembelihan', 'Nama Penerima', 'Alamat Penerima', 'Jenis Penerima',
                'Status Pembelian', 'Tgl Selesai Pembelian',
                'Status Penyembelihan', 'Tgl Penyembelihan',
                'Status Distribusi', 'Tgl Distribusi',
                'Status Laporan', 'Tgl Laporan',
                'Progress (%)', 'Status', 'Catatan', 'Dibuat',
            ]);

            foreach ($sacrifices as $s) {
                fputcsv($file, [
                    $s->reference_code, $s->getSacrificeTypeLabel(), $s->donor_name, $s->donor_email, $s->donor_phone,
                    $s->getAnimalTypeLabel(), $s->animal_price, $s->sharing_type, $s->share_ratio,
                    $s->purchase_date?->format('d/m/Y'), $s->purchase_location,
                    $s->slaughter_location, $s->beneficiary_name,
                    $s->beneficiary_address, $s->beneficiary_type,
                    $s->status_purchase, $s->date_purchase_completed?->format('d/m/Y'),
                    $s->status_slaughter, $s->date_slaughter_completed?->format('d/m/Y'),
                    $s->status_distribution, $s->date_distribution_completed?->format('d/m/Y'),
                    $s->status_report, $s->date_report_completed?->format('d/m/Y'),
                    $s->getProgressPercentage(), $s->getStatusLabel(),
                    $s->notes, $s->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function statistics(): View
    {
        $total = Sacrifice::count();

        // ── Summary figures ──────────────────────────────────────────────────
        $totalDonated    = (float) Sacrifice::sum('animal_price');
        $priceCount      = Sacrifice::whereNotNull('animal_price')->count();
        $avgPrice        = $priceCount > 0 ? $totalDonated / $priceCount : 0;

        $completedCount  = Sacrifice::where('status_purchase',     'completed')
                               ->where('status_slaughter',    'completed')
                               ->where('status_distribution', 'completed')
                               ->where('status_report',       'completed')
                               ->count();
        $pendingCount    = Sacrifice::where('status_purchase',     'pending')
                               ->where('status_slaughter',    'pending')
                               ->where('status_distribution', 'pending')
                               ->where('status_report',       'pending')
                               ->count();
        $inProgressCount = $total - $completedCount - $pendingCount;

        // ── Animal type breakdown ────────────────────────────────────────────
        $byAnimalType = Sacrifice::selectRaw('animal_type, COUNT(*) as count, COALESCE(SUM(animal_price), 0) as total_price')
            ->groupBy('animal_type')
            ->orderByDesc('count')
            ->get();

        // ── Completion timeline ──────────────────────────────────────────────
        // Penyembelihan (slaughter) per month
        $slaughterByMonth = Sacrifice::whereNotNull('date_slaughter_completed')
            ->selectRaw('DATE_FORMAT(date_slaughter_completed, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Fully completed (report) per month
        $reportByMonth = Sacrifice::whereNotNull('date_report_completed')
            ->selectRaw('DATE_FORMAT(date_report_completed, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $allMonths = $slaughterByMonth->keys()
            ->merge($reportByMonth->keys())
            ->unique()->sort()->values();

        $timelineLabels    = $allMonths->map(
            fn ($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y')
        )->values();
        $timelineSlaughter = $allMonths->map(fn ($m) => $slaughterByMonth->get($m, 0))->values();
        $timelineCompleted = $allMonths->map(fn ($m) => $reportByMonth->get($m, 0))->values();

        return view('admin.statistics', compact(
            'total', 'totalDonated', 'avgPrice',
            'completedCount', 'pendingCount', 'inProgressCount',
            'byAnimalType',
            'timelineLabels', 'timelineSlaughter', 'timelineCompleted'
        ));
    }

    private function validateSacrificeInput(Request $request): array
    {
        $animalType  = $request->input('animal_type', '');
        $sharingType = $request->input('sharing_type', '');

        // Max share ratio depends on animal + sharing combination
        $maxRatio = 1;
        if ($sharingType === 'collective') {
            $maxRatio = match ($animalType) {
                'unta' => 10,
                'sapi' => 7,
                default => 1,
            };
        }

        $validated = $request->validate([
            'sacrifice_type'      => ['required', 'in:palestina,nusantara'],
            'donor_name'          => ['required', 'string', 'max:255'],
            'donor_email'         => ['nullable', 'email', 'max:255'],
            'donor_phone'         => ['nullable', 'string', 'max:20'],
            'animal_type'         => ['required', 'in:unta,sapi,domba'],
            'animal_price'        => ['nullable', 'numeric', 'min:0'],
            'sharing_type'        => ['required', 'in:full,collective'],
            'share_ratio'         => ['required', 'integer', 'min:1', "max:{$maxRatio}"],
            'purchase_date'       => ['nullable', 'date'],
            'purchase_location'   => ['nullable', 'string', 'max:255'],
            'slaughter_location'  => ['nullable', 'string', 'max:255'],
            'beneficiary_name'    => ['nullable', 'string', 'max:255'],
            'beneficiary_address' => ['nullable', 'string'],
            'beneficiary_type'    => ['nullable', 'string', 'max:100'],
            'notes'               => ['nullable', 'string'],
        ]);

        // Cross-field: animal must be valid for sacrifice type
        if (!Sacrifice::validateAnimalTypeForSacrificeType($validated['sacrifice_type'], $validated['animal_type'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'animal_type' => "Hewan {$validated['animal_type']} tidak tersedia untuk program {$validated['sacrifice_type']}.",
            ]);
        }

        // Cross-field: domba cannot be collective
        if (!Sacrifice::validateSharingCombination($validated['animal_type'], $validated['sharing_type'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'sharing_type' => 'Domba hanya bisa dikurbankan secara penuh, tidak bisa kolektif.',
            ]);
        }

        return $validated;
    }

    private function generateReferenceCode(): string
    {
        $year = date('Y');
        $lastCode = Sacrifice::where('reference_code', 'like', "NPC-{$year}-%")
            ->orderByDesc('reference_code')
            ->value('reference_code');

        $newNumber = $lastCode ? ((int) substr($lastCode, -3)) + 1 : 1;

        return sprintf('NPC-%s-%03d', $year, $newNumber);
    }
}
