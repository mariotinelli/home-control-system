<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\{HasPermissions, HasRoles};

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasPermissions;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'filament_roles'    => 'array',
    ];

    protected $appends = [
        'filament_roles',
    ];

    public function canAccessFilament(): bool
    {
        return $this->hasPermissionTo('access_admin');
    }

    public function filamentRoles(): Attribute
    {
        return new Attribute(function () {
            return $this->getRoleNames()->toArray();
        });
    }

    public function coupleSpendingCategories(): HasMany
    {
        return $this->hasMany(CoupleSpendingCategory::class);
    }

    public function coupleSpendings(): HasMany
    {
        return $this->hasMany(CoupleSpending::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function marketItemCategories(): HasMany
    {
        return $this->hasMany(MarketItemCategory::class);
    }

    public function marketItems(): HasMany
    {
        return $this->hasMany(MarketItem::class);
    }

    public function markets(): HasMany
    {
        return $this->hasMany(Market::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

}
