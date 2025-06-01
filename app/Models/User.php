<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'job',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar_url',
        'is_already_checked_in',
        'is_already_checked_out',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the attendances for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

    /**
     * Get the latest record of attendance where date is today.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestAttendance(): HasOne
    {
        return $this->hasOne(Attendance::class, 'user_id', 'id')->latestOfMany('date')
            ->where('date', now()->toDateString());
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get the url of the avatar of user in public avatars storage.
     *
     * @return string
     */
    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->avatar ? Storage::url($this->avatar) : null,
        );
    }


    /**
     * Check if the user is already checked in.
     *
     * @return string
     */
    public function isAlreadyCheckedIn(): Attribute
    {
        return new Attribute(
            get: fn () => (bool) $this->hasActiveAttendance(),
        );
    }

    /**
     * Check if the user is already checked out.
     *
     * @return string
     */
    public function isAlreadyCheckedOut(): Attribute
    {
        return new Attribute(
            get: fn () => (bool) !$this->hasActiveAttendance(),
        );
    }

    /**
     * Check if the user has active attendance
     *
     * @return boolean
     */
    public function hasActiveAttendance(): bool
    {
        return (bool) $this->attendances()->where('is_active', true)->exists();
    }
}
