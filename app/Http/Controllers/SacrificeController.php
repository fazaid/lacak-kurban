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
            'reference_code' => ['required', 'string'],
        ]);

        $referenceCode = strtoupper(trim($request->input('reference_code')));
        $sacrifice = Sacrifice::where('reference_code', $referenceCode)->first();

        if (!$sacrifice) {
            return back()
                ->with('error', 'Kode referensi tidak ditemukan. Pastikan kode yang Anda masukkan sudah benar.')
                ->withInput();
        }

        return redirect()->route('sacrifice.show', $sacrifice->reference_code);
    }

    public function show(string $code): View
    {
        return $this->renderDetail($code, 'profile');
    }

    public function profile(string $code): View
    {
        return $this->renderDetail($code, 'profile');
    }

    public function progress(string $code): View
    {
        return $this->renderDetail($code, 'progress');
    }

    public function gallery(string $code): View
    {
        return $this->renderDetail($code, 'gallery');
    }

    public function certificate(string $code): View
    {
        return $this->renderDetail($code, 'certificate');
    }

    public function downloadCertificate(string $code)
    {
        $sacrifice = Sacrifice::where('reference_code', $code)->firstOrFail();

        if (!$sacrifice->hasCertificate()) {
            abort(403, 'Sertifikat belum tersedia untuk kurban ini.');
        }

        $pdf = Pdf::loadView('certificates.sacrifice', compact('sacrifice'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("sertifikat-kurban-{$sacrifice->reference_code}.pdf");
    }

    private function renderDetail(string $code, string $activeTab): View
    {
        $sacrifice = Sacrifice::with('galleries')
            ->where('reference_code', $code)
            ->firstOrFail();

        $photosByCategory = $sacrifice->getPhotosByCategories();

        return view('donor.sacrifice-detail', compact('sacrifice', 'photosByCategory', 'activeTab'));
    }
}
