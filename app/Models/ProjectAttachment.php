<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the project that owns the attachment.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    /**
     * Get the download URL.
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('admin.attachments.download', $this->id);
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is an image.
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if file is a PDF.
     */
    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Get file icon based on mime type.
     */
    public function getIconAttribute(): string
    {
        if ($this->is_image) {
            return 'heroicon-o-photo';
        }

        if ($this->is_pdf) {
            return 'heroicon-o-document-text';
        }

        return match (true) {
            str_contains($this->mime_type, 'word') => 'heroicon-o-document',
            str_contains($this->mime_type, 'excel') || str_contains($this->mime_type, 'spreadsheet') => 'heroicon-o-table-cells',
            str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'rar') => 'heroicon-o-archive-box',
            default => 'heroicon-o-paper-clip',
        };
    }

    /**
     * Delete the file from storage when model is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            Storage::disk('public')->delete($attachment->path);
        });
    }
}
