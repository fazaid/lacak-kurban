<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    private const DISK = 'local';
    private const DIR  = 'signatures';

    public function index(): View
    {
        return view('admin.settings', [
            'sigLeft'  => $this->signatureExists('left'),
            'sigRight' => $this->signatureExists('right'),
        ]);
    }

    public function uploadSignature(Request $request): RedirectResponse
    {
        $position = $request->input('position'); // 'left' or 'right'

        $request->validate([
            'signature' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'position'  => ['required', 'in:left,right'],
        ]);

        $filename = "sig_{$position}.png";

        // Convert to PNG-named file and store
        Storage::disk(self::DISK)->putFileAs(self::DIR, $request->file('signature'), $filename);

        return back()->with('success', 'Tanda tangan berhasil diperbarui.');
    }

    public function deleteSignature(Request $request): RedirectResponse
    {
        $position = $request->input('position');

        if (in_array($position, ['left', 'right'])) {
            Storage::disk(self::DISK)->delete(self::DIR . "/sig_{$position}.png");
        }

        return back()->with('success', 'Tanda tangan berhasil dihapus.');
    }

    public function serveSignature(string $position): Response
    {
        abort_unless(in_array($position, ['left', 'right']), 404);

        $path = Storage::disk(self::DISK)->path(self::DIR . "/sig_{$position}.png");

        abort_unless(file_exists($path), 404);

        return response(file_get_contents($path), 200)
            ->header('Content-Type', mime_content_type($path))
            ->header('Cache-Control', 'private, max-age=3600');
    }

    private function signatureExists(string $position): bool
    {
        return Storage::disk(self::DISK)->exists(self::DIR . "/sig_{$position}.png");
    }

    // Helper: get base64 for embedding in PDF (called from certificate template)
    public static function signatureBase64(string $position): ?string
    {
        $path = Storage::disk(self::DISK)->path(self::DIR . "/sig_{$position}.png");

        if (!file_exists($path)) {
            return null;
        }

        $mime = mime_content_type($path);
        return "data:{$mime};base64," . base64_encode(file_get_contents($path));
    }
}
