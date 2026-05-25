<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SacrificeGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'sacrifice_id', 'photo_category', 'file_path',
        'file_name', 'file_size', 'mime_type', 'caption', 'order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'order' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function sacrifice(): BelongsTo
    {
        return $this->belongsTo(Sacrifice::class);
    }

    // -------------------------------------------------------------------------
    // Accessors (Eloquent attribute syntax)
    // -------------------------------------------------------------------------

    /** Full public URL to the photo file. */
    public function getUrlAttribute(): string
    {
        return $this->getPhotoUrl();
    }

    /** Friendly category label as a computed attribute. */
    public function getCategoryLabelAttribute(): string
    {
        return $this->getCategoryLabel();
    }

    // -------------------------------------------------------------------------
    // Named methods (callable directly, not only via magic attribute)
    // -------------------------------------------------------------------------

    /**
     * Returns the human-readable label for photo_category.
     */
    public function getCategoryLabel(): string
    {
        return match ($this->photo_category) {
            'hewan' => 'Foto Hewan',
            'penyembelihan' => 'Penyembelihan',
            'pengemasan' => 'Pengemasan',
            'distribusi' => 'Distribusi',
            default => ucfirst($this->photo_category),
        };
    }

    /**
     * Returns the full public URL to the stored photo.
     */
    public function getPhotoUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Returns a human-readable file size (e.g. "1.4 MB", "320 KB", "890 B").
     */
    public function getFormattedFileSize(): string
    {
        $bytes = (int) $this->file_size;

        if ($bytes <= 0) {
            return '0 B';
        }

        if ($bytes < 1_024) {
            return "{$bytes} B";
        }

        if ($bytes < 1_048_576) {
            return round($bytes / 1_024, 1) . ' KB';
        }

        return round($bytes / 1_048_576, 1) . ' MB';
    }

    /**
     * Returns a short uppercase file-type label derived from the MIME type.
     *
     * Examples: "JPEG", "PNG", "WebP", "GIF", "Image"
     */
    public function getTypeLabel(): string
    {
        return match ($this->mime_type) {
            'image/jpeg', 'image/jpg' => 'JPEG',
            'image/png' => 'PNG',
            'image/webp' => 'WebP',
            'image/gif' => 'GIF',
            'image/bmp' => 'BMP',
            'image/svg+xml' => 'SVG',
            'image/avif' => 'AVIF',
            'image/heic', 'image/heif' => 'HEIC',
            default => $this->mime_type
                ? strtoupper(last(explode('/', $this->mime_type)))
                : 'Image',
        };
    }
}
