<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerVerification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'legal_name',
        'date_of_birth',
        'country',
        'id_type',
        'document_type',
        'id_file_path',
        'id_document_path',
        'document_file',
        'id_number',
        'document_number',
        'portfolio_link',
        'portfolio_url',
        'selfie_file_path',
        'selfie_path',
        'selfie_file',
        'video_file_path',
        'video_file',
        'status',
        'admin_notes',
        'admin_note',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'rejected_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    /**
     * Accessor for full_name (fallback to legal_name).
     */
    public function getFullNameAttribute(): ?string
    {
        return $this->attributes['full_name'] ?? $this->attributes['legal_name'] ?? null;
    }

    /**
     * Accessor for legal_name (fallback to full_name).
     */
    public function getLegalNameAttribute(): ?string
    {
        return $this->attributes['legal_name'] ?? $this->attributes['full_name'] ?? null;
    }

    /**
     * Accessor for document_type (fallback to id_type).
     */
    public function getDocumentTypeAttribute(): ?string
    {
        return $this->attributes['document_type'] ?? $this->attributes['id_type'] ?? null;
    }

    /**
     * Accessor for document_file (fallback to id_file_path or id_document_path).
     */
    public function getDocumentFileAttribute(): ?string
    {
        return $this->attributes['document_file'] ?? $this->attributes['id_file_path'] ?? $this->attributes['id_document_path'] ?? null;
    }

    /**
     * Accessor for id_file_path (fallback to id_document_path or document_file).
     */
    public function getIdFilePathAttribute(): ?string
    {
        return $this->attributes['id_file_path'] ?? $this->attributes['id_document_path'] ?? $this->attributes['document_file'] ?? null;
    }

    /**
     * Accessor for id_document_path (fallback to id_file_path or document_file).
     */
    public function getIdDocumentPathAttribute(): ?string
    {
        return $this->attributes['id_document_path'] ?? $this->attributes['id_file_path'] ?? $this->attributes['document_file'] ?? null;
    }

    /**
     * Accessor for selfie_file (fallback to selfie_file_path or selfie_path).
     */
    public function getSelfieFileAttribute(): ?string
    {
        return $this->attributes['selfie_file'] ?? $this->attributes['selfie_file_path'] ?? $this->attributes['selfie_path'] ?? null;
    }

    /**
     * Accessor for selfie_file_path (fallback to selfie_path or selfie_file).
     */
    public function getSelfieFilePathAttribute(): ?string
    {
        return $this->attributes['selfie_file_path'] ?? $this->attributes['selfie_path'] ?? $this->attributes['selfie_file'] ?? null;
    }

    /**
     * Accessor for selfie_path (fallback to selfie_file_path or selfie_file).
     */
    public function getSelfiePathAttribute(): ?string
    {
        return $this->attributes['selfie_path'] ?? $this->attributes['selfie_file_path'] ?? $this->attributes['selfie_file'] ?? null;
    }

    /**
     * Accessor for video_file (fallback to video_file_path).
     */
    public function getVideoFileAttribute(): ?string
    {
        return $this->attributes['video_file'] ?? $this->attributes['video_file_path'] ?? null;
    }

    /**
     * Accessor for admin_note (fallback to admin_notes).
     */
    public function getAdminNoteAttribute(): ?string
    {
        return $this->attributes['admin_note'] ?? $this->attributes['admin_notes'] ?? null;
    }

    /**
     * The user who submitted the verification request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
