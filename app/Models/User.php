<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    
    // INACTIVE DAYS CONSTANT - Change this number to change inactive period
    const INACTIVE_DAYS = 3; // Users are inactive after 3 days of no login

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',              // Phone number (only for users, not admins)
        'address',            // ✅ ADD THIS - Delivery address
        'profile_photo_path', // Profile photo path
        'last_login_at',      // Last login timestamp
        'status',             // User status (active/inactive)
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
            'last_login_at' => 'datetime',
        ];
    }
    
    /**
     * Get the user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }
        
        return null;
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if user is a regular user
     */
    public function isUser()
    {
        return $this->role === 'user';
    }
    
    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    /**
     * Determine if phone field should be shown
     */
    public function canHavePhone()
    {
        return $this->role === 'user';
    }
    
    /**
     * Determine if address field should be shown
     */
    public function canHaveAddress()
    {
        return $this->role === 'user';
    }
    
    /**
     * Scope to get active users (logged in within inactive days)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->whereNotNull('last_login_at')
                     ->where('last_login_at', '>=', now()->subDays(self::INACTIVE_DAYS));
    }
    
    /**
     * Scope to get inactive users (no login for inactive days)
     */
    public function scopeInactive($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'inactive')
              ->orWhereNull('last_login_at')
              ->orWhere('last_login_at', '<', now()->subDays(self::INACTIVE_DAYS));
        });
    }
    
    /**
     * Check if user is inactive (no login for inactive days)
     */
    public function isInactive()
    {
        // User with no login is considered inactive
        if (!$this->last_login_at) {
            return true;
        }
        
        return $this->last_login_at->diffInDays(now()) >= self::INACTIVE_DAYS;
    }
    
    /**
     * Update user status based on last login
     */
    public function updateStatusByLastLogin()
    {
        // If user has never logged in, mark as inactive
        if (!$this->last_login_at) {
            $this->status = 'inactive';
        }
        // If user hasn't logged in for X days, mark as inactive
        elseif ($this->last_login_at->diffInDays(now()) >= self::INACTIVE_DAYS) {
            $this->status = 'inactive';
        }
        // Otherwise, user is active
        else {
            $this->status = 'active';
        }
        $this->save();
        
        return $this;
    }
    
    /**
     * Get the user's cart items
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}