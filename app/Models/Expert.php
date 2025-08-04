<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expert extends Model
{
    //
    protected $fillable = [
        'bio',
        'industry',
        'user_id',
        'profile_picture',
        'session_price', // Added session_price to fillable attributes
    ];

    /**
     * Get the user that owns the Expert
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the comments for the Expert
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'expert_id');
    }
}
