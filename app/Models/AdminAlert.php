<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAlert extends Model
{
    use HasFactory;

    public $timestamps = false;

    public const TYPE_INACTIVE_STUDENT = 'inactive_student';

    public const STATUS_OPEN = 'open';
    public const STATUS_DISMISSED = 'dismissed';
    public const STATUS_RESOLVED = 'resolved';

    protected $fillable = [
        'admin_id',
        'subject_user_id',
        'type',
        'status',
        'created_at',
        'resolved_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function subjectUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subject_user_id');
    }
}
