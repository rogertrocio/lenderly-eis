<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        // 'avatar_url',
        // 'is_already_checked_in',
        // 'is_already_checked_out',
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

    /**
     * The roles the belongs to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Check if assignes roles to the user has contains permission name.
     *
     * @param [type] $permission
     * @return boolean
     */
    public function hasPermission($permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to filter users record.
     */
    #[Scope]
    protected function commonFilters(Builder $query, array $filters): void
    {
        $search = $filters['filter']['search'] ?? null;
        $role = $filters['filter']['role'] ?? null;

        $query->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->when($search, function ($query) use ($search) {
                $query->where('users.first_name', 'LIKE', "%{$search}%")
                    ->orWhere('users.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('users.email', 'LIKE', "%{$search}%")
                    ->orWhere('users.job', 'LIKE', "%{$search}%");
            })
            ->when($role, function ($query) use ($role) {
                $query->where('roles.id', '=', $role);
            });
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
            get: fn() => $this->avatar ? Storage::url($this->avatar) : null,
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
            get: fn() => (bool) $this->hasActiveAttendance(),
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
            get: fn() => (bool) !$this->hasActiveAttendance(),
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
