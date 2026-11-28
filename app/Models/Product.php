<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'unit_id',
        'category_id',
        'sub_category_id',
        'submitted_by',
        'code',
        'name',
        'description',
        'order_type',
        'hpp',
        'price_b2c',
        'price_b2b',
        'show_price',
        'stock',
        'image_path',
        'video_url',
        'status',
        'curation_notes',
    ];

    protected function casts(): array
    {
        return [
            'hpp' => 'decimal:2',
            'price_b2c' => 'decimal:2',
            'price_b2b' => 'decimal:2',
            'show_price' => 'boolean',
            'stock' => 'integer',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(PortalUser::class, 'submitted_by');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Resolve path relatif gambar di bawah public/ (mendukung public/images/products/*
     * maupun upload Storage::disk('public') di public/storage/products/*) tanpa error prefix.
     */
    public function getResolvedImagePathAttribute(): ?string
    {
        $raw = trim((string) ($this->image_path ?? ''));
        if ($raw === '') {
            return null;
        }

        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }

        $normalized = ltrim(str_replace('\\', '/', $raw), '/');

        if (is_file(public_path($normalized))) {
            return $normalized;
        }

        if (! str_starts_with($normalized, 'storage/')) {
            $storageRelative = 'storage/' . $normalized;
            if (is_file(public_path($storageRelative))) {
                return $storageRelative;
            }
        }

        return null;
    }

    /**
     * URL gambar siap pakai untuk tag <img> di seluruh view tanpa double slash atau broken path.
     */
    public function getResolvedImageUrlAttribute(): ?string
    {
        $resolved = $this->resolved_image_path;
        if ($resolved === null) {
            return null;
        }

        if (str_starts_with($resolved, 'http://') || str_starts_with($resolved, 'https://')) {
            return $resolved;
        }

        return asset($resolved);
    }

    /**
     * Data URI base64 agar gambar produk selalu termuat pada engine cetak / PDF offline.
     */
    public function getResolvedImageDataUriAttribute(): ?string
    {
        $resolved = $this->resolved_image_path;
        if ($resolved === null || str_starts_with($resolved, 'http://') || str_starts_with($resolved, 'https://')) {
            return $resolved;
        }

        $fullPath = public_path($resolved);
        if (! is_file($fullPath)) {
            return null;
        }

        $mime = mime_content_type($fullPath) ?: 'image/png';
        $content = file_get_contents($fullPath);

        return $content !== false ? 'data:' . $mime . ';base64,' . base64_encode($content) : null;
    }

    /**
     * Ekstrak ID video YouTube (11 karakter) dari berbagai format URL YouTube
     * (youtu.be/{id}, youtube.com/watch?v={id}, youtube.com/embed/{id}, youtube.com/shorts/{id}).
     */
    protected function extractYoutubeVideoId(?string $url): ?string
    {
        $raw = trim((string) ($url ?? ''));
        if ($raw === '') {
            return null;
        }

        $patterns = [
            '~(?:youtube\.com|youtube-nocookie\.com)/(?:embed|shorts|live|v)/([a-zA-Z0-9_-]{11})~i',
            '~youtu\.be/([a-zA-Z0-9_-]{11})~i',
            '~[?&]v=([a-zA-Z0-9_-]{11})~i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $raw, $matches) === 1 && ! empty($matches[1])) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * URL YouTube embed siap pakai untuk <iframe> (dengan fallback video resmi PNJ jika kosong).
     */
    public function getResolvedEmbedVideoUrlAttribute(): string
    {
        $videoId = $this->extractYoutubeVideoId($this->video_url) ?: 'Im00a5ZL46I';

        return 'https://www.youtube.com/embed/' . $videoId . '?rel=0';
    }

    /**
     * URL YouTube publik (watch) untuk keperluan tampilan cetak PDF / tautan langsung.
     */
    public function getResolvedWatchVideoUrlAttribute(): string
    {
        $videoId = $this->extractYoutubeVideoId($this->video_url) ?: 'Im00a5ZL46I';

        return 'https://www.youtube.com/watch?v=' . $videoId;
    }
}
