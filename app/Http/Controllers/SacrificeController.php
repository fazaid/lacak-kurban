<?php

namespace App\Http\Controllers;

use App\Models\Sacrifice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SacrificeController extends Controller
{
    public function index(): View
    {
        return view('donor.search');
    }

    public function search(Request $request): RedirectResponse
    {
        $request->validate([
            'reference_code' => ['required', 'string', 'max:20'],
        ]);

        $referenceCode = strtoupper(trim($request->input('reference_code')));
        $sacrifice = Sacrifice::where('reference_code', $referenceCode)->first();

        if (!$sacrifice) {
            return back()
                ->with('error', 'Kode referensi tidak ditemukan. Pastikan kode yang Anda masukkan sudah benar.')
                ->withInput();
        }

        // Redirect to unguessable public slug URL, not the sequential reference code
        return redirect()->route('sacrifice.show', $sacrifice->public_slug);
    }

    public function show(string $slug, Request $request): View
    {
        return $this->renderDetail($slug, 'profile', $request);
    }

    public function profile(string $slug, Request $request): View
    {
        return $this->renderDetail($slug, 'profile', $request);
    }

    public function progress(string $slug, Request $request): View
    {
        return $this->renderDetail($slug, 'progress', $request);
    }

    public function gallery(string $slug, Request $request): View
    {
        return $this->renderDetail($slug, 'gallery', $request);
    }

    public function certificate(string $slug, Request $request): View
    {
        return $this->renderDetail($slug, 'certificate', $request);
    }

    public function downloadCertificate(string $slug)
    {
        $sacrifice = Sacrifice::where('public_slug', $slug)->firstOrFail();

        if (!$sacrifice->hasCertificate()) {
            abort(403, 'Sertifikat belum tersedia untuk kurban ini.');
        }

        $pdf = Pdf::loadView('certificates.sacrifice', compact('sacrifice'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("sertifikat-kurban-{$sacrifice->reference_code}.pdf");
    }

    public function printCertificate(string $slug)
    {
        $sacrifice = Sacrifice::where('public_slug', $slug)->firstOrFail();

        if (!$sacrifice->hasCertificate()) {
            abort(403, 'Sertifikat belum tersedia untuk kurban ini.');
        }

        $pdf = Pdf::loadView('certificates.sacrifice', compact('sacrifice'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream("sertifikat-kurban-{$sacrifice->reference_code}.pdf");
    }

    private function renderDetail(string $slug, string $activeTab, Request $request): View
    {
        $sacrifice = Sacrifice::with('galleries')
            ->where('public_slug', $slug)
            ->firstOrFail();

        $sacrifice->logAccess($request->ip());

        $photosByCategory = $sacrifice->getPhotosByCategories();

        return view('donor.sacrifice-detail', compact('sacrifice', 'photosByCategory', 'activeTab'));
    }
}
