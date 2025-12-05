<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'active' => 'boolean',
        ];
    }


    // Relations
    public function doctorant()
    {
        return $this->hasOne(Doctorant::class);
    }

    public function encadrant()
    {
        return $this->hasOne(Encadrant::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'uploader_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'auteur_id');
    }

    public function actualites()
    {
        return $this->hasMany(Actualite::class, 'auteur_id');
    }

    // Méthodes helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSecretaire()
    {
        return $this->role === 'secrétaire';
    }

    public function isDoctorant()
    {
        return $this->role === 'doctorant';
    }

    public function isEncadrant()
    {
        return $this->role === 'encadrant';
    }
}
