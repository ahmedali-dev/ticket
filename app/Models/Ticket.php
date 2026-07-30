<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;


    protected $fillable = [
        'title',
        'description',
        'image',
        'id'
    ];
    public const STATUS_PENDING = 'pending';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
    ];

    /**
     * @var list<string>
     *

    /**
     * @var list<string>
     */
    protected $appends = [
        'image_url',
    ];

    /**
     * Human-readable status labels.
     *
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): hasMany
    {
        return $this->hasMany(Media::class, 'ticket_id', 'id');
    }

    public function ticketView(): HasMany
    {
        return $this->hasMany(TicketView::class);
    }
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->oldest();
    }
}
