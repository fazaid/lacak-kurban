<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Sacrifice extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code', 'public_slug', 'donor_email_hash',
        'sacrifice_type', 'donor_name', 'donor_email', 'donor_phone',
        'animal_type', 'animal_price', 'sharing_type', 'share_ratio',
        'purchase_date', 'purchase_location', 'slaughter_location',
        'beneficiary_name', 'beneficiary_address', 'beneficiary_type',
        'status_purchase', 'date_purchase_completed',
        'status_slaughter', 'date_slaughter_completed',
        'status_on_way', 'date_on_way_completed',
        'status_distribution', 'date_distribution_completed',
        'status_report', 'date_report_completed',
        'certificate_file_path', 'certificate_generated_at', 'notes',
        'last_accessed_ip', 'last_accessed_at', 'access_count',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'date_purchase_completed' => 'date',
        'date_slaughter_completed' => 'date',
        'date_on_way_completed' => 'date',
        'date_distribution_completed' => 'date',
        'date_report_completed' => 'date',
        'certificate_generated_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'animal_price' => 'decimal:2',
        'share_ratio' => 'integer',
        'access_count' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Boot — auto-generate slug & hash email on create
    // -------------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (!$model->public_slug) {
                $model->public_slug = static::generateUniqueSlug();
            }
            if ($model->donor_email && !$model->donor_email_hash) {
                $model->donor_email_hash = hash('sha256', strtolower(trim($model->donor_email)));
            }
        });
    }

    public static function generateUniqueSlug(): string
    {
        do {
            $slug = 'sac_' . bin2hex(random_bytes(14));
        } while (static::where('public_slug', $slug)->exists());

        return $slug;
    }

    public static function isValidSlug(string $slug): bool
    {
        return preg_match('/^sac_[a-f0-9]{28}$/', $slug) === 1;
    }

    public function logAccess(string $ipAddress): void
    {
        $this->increment('access_count');
        $this->update([
            'last_accessed_ip' => $ipAddress,
            'last_accessed_at' => now(),
        ]);

        if ($this->access_count > 1000) {
            \Illuminate\Support\Facades\Log::warning('High access count on sacrifice', [
                'sacrifice_id' => $this->id,
                'reference_code' => $this->reference_code,
                'access_count' => $this->access_count,
                'last_ip' => $ipAddress,
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function galleries(): HasMany
    {
        return $this->hasMany(SacrificeGallery::class)
            ->orderBy('order')
            ->orderBy('created_at');
    }

    // -------------------------------------------------------------------------
    // Progress & status
    // -------------------------------------------------------------------------

    public function getProgress(): array
    {
        return [
            [
                'key' => 'purchase',
                'label' => 'Pembelian Hewan',
                'description' => 'Proses pembelian hewan kurban telah dilakukan',
                'status' => $this->status_purchase,
                'date' => $this->date_purchase_completed,
                'icon' => 'ti-shopping-cart',
            ],
            [
                'key' => 'slaughter',
                'label' => 'Penyembelihan',
                'description' => 'Hewan kurban telah disembelih sesuai syariat',
                'status' => $this->status_slaughter,
                'date' => $this->date_slaughter_completed,
                'icon' => 'ti-cut',
            ],
            [
                'key' => 'on_way',
                'label' => 'Menuju Tempat Distribusi',
                'description' => 'Daging kurban sedang dalam perjalanan menuju lokasi distribusi',
                'status' => $this->status_on_way,
                'date' => $this->date_on_way_completed,
                'icon' => 'ti-truck',
            ],
            [
                'key' => 'distribution',
                'label' => 'Distribusi Daging',
                'description' => 'Daging kurban telah didistribusikan kepada penerima manfaat',
                'status' => $this->status_distribution,
                'date' => $this->date_distribution_completed,
                'icon' => 'ti-truck-delivery',
            ],
            [
                'key' => 'report',
                'label' => 'Laporan',
                'description' => 'Laporan pelaksanaan kurban telah selesai dibuat',
                'status' => $this->status_report,
                'date' => $this->date_report_completed,
                'icon' => 'ti-report',
            ],
        ];
    }

    public function getProgressPercentage(): int
    {
        $completed = 0;
        if ($this->status_purchase === 'completed') $completed++;
        if ($this->status_slaughter === 'completed') $completed++;
        if ($this->status_on_way === 'completed') $completed++;
        if ($this->status_distribution === 'completed') $completed++;
        if ($this->status_report === 'completed') $completed++;

        return (int) ($completed / 5 * 100);
    }

    public function isFullyCompleted(): bool
    {
        return $this->status_purchase === 'completed'
            && $this->status_slaughter === 'completed'
            && $this->status_on_way === 'completed'
            && $this->status_distribution === 'completed'
            && $this->status_report === 'completed';
    }

    public function getStatusColor(string $statusType): string
    {
        $status = match ($statusType) {
            'purchase' => $this->status_purchase,
            'slaughter' => $this->status_slaughter,
            'on_way' => $this->status_on_way,
            'distribution' => $this->status_distribution,
            'report' => $this->status_report,
            default => $statusType,
        };

        return match ($status) {
            'completed' => 'green',
            'pending' => 'yellow',
            default => 'gray',
        };
    }

    public function getStatusLabel(): string
    {
        $pct = $this->getProgressPercentage();

        if ($pct === 0) return 'Menunggu';
        if ($pct === 100) return 'Selesai';

        return 'Sedang Berjalan';
    }

    public function getStatusBadgeClass(): string
    {
        $pct = $this->getProgressPercentage();

        if ($pct === 0) return 'bg-yellow-100 text-yellow-800 border border-yellow-200';
        if ($pct === 100) return 'bg-green-100 text-green-800 border border-green-200';

        return 'bg-blue-100 text-blue-800 border border-blue-200';
    }

    // -------------------------------------------------------------------------
    // Sacrifice type & sharing helpers
    // -------------------------------------------------------------------------

    public function getSacrificeTypeLabel(): string
    {
        return match ($this->sacrifice_type) {
            'palestina' => 'Palestina',
            'nusantara' => 'Nusantara',
            default => ucfirst($this->sacrifice_type ?? ''),
        };
    }

    public function getAnimalTypeLabel(): string
    {
        if ($this->animal_type === 'domba' && $this->sacrifice_type === 'nusantara') {
            return 'Kambing';
        }

        return match ($this->animal_type) {
            'unta' => 'Unta',
            'sapi' => 'Sapi',
            'domba' => 'Domba',
            default => ucfirst($this->animal_type ?? ''),
        };
    }

    /** Returns the valid animal types for a given sacrifice type. */
    public static function getValidAnimalsForType(string $sacrificeType): array
    {
        return match ($sacrificeType) {
            'palestina' => ['unta', 'sapi', 'domba'],
            'nusantara' => ['sapi', 'domba'],
            default => ['sapi', 'domba'],
        };
    }

    /** Default collective share ratio for an animal type. */
    public static function getShareRatioForAnimal(string $animalType): int
    {
        return match ($animalType) {
            'unta' => 10,
            'sapi' => 7,
            'domba' => 1,
            default => 1,
        };
    }

    /** Returns true when the animal is valid for the given sacrifice type. */
    public static function validateAnimalTypeForSacrificeType(string $type, string $animal): bool
    {
        return in_array($animal, self::getValidAnimalsForType($type));
    }

    /** Returns false only for the invalid domba+collective combination. */
    public static function validateSharingCombination(string $animal, string $sharing): bool
    {
        return !($animal === 'domba' && $sharing === 'collective');
    }

    public function getShareInfo(): array
    {
        $denominator = self::getShareRatioForAnimal($this->animal_type ?? '');
        if ($this->sharing_type === 'collective') {
            $numerator = $this->share_ratio ?? 1;
            return [
                'type' => 'collective',
                'numerator' => $numerator,
                'denominator' => $denominator,
                'label' => "Kolektif {$numerator}/{$denominator}",
            ];
        }
        return ['type' => 'full', 'numerator' => 1, 'denominator' => 1, 'label' => 'Penuh'];
    }

    public function getShareLabel(): string
    {
        return $this->getShareInfo()['label'];
    }

    /** Kontribusi donatur — nilai yang tersimpan di animal_price sudah merupakan jumlah yang dibayar. */
    public function getTotalPrice(): float
    {
        return (float) ($this->animal_price ?? 0);
    }

    // -------------------------------------------------------------------------
    // Certificate
    // -------------------------------------------------------------------------

    public function hasCertificate(): bool
    {
        return !is_null($this->certificate_generated_at);
    }

    // -------------------------------------------------------------------------
    // Gallery helpers
    // -------------------------------------------------------------------------

    public function getPhotosByCategory(string $category): Collection
    {
        return $this->galleries
            ->where('photo_category', $category)
            ->values();
    }

    public function getPhotosByCategories(): Collection
    {
        return $this->galleries->groupBy('photo_category');
    }

    public function getAllPhotos(): Collection
    {
        return $this->galleries->values();
    }

    public function getPhotoCount(): int
    {
        return $this->galleries->count();
    }

    public function getPhotoCountByCategory(string $category): int
    {
        return $this->galleries->where('photo_category', $category)->count();
    }
}
