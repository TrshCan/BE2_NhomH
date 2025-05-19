<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'ban_reason',
        'status_id',
        'google_id',
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

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    // User.php

    public static function findOrCreateFromSocialite($socialUser, string $provider): self
    {
        $user = self::where("{$provider}_id", $socialUser->getId())->first();
        if ($user) {
            return $user;
        }
        $user = self::where('email', $socialUser->getEmail())->first();
        if ($user) {
            $user->update(["{$provider}_id" => $socialUser->getId()]);
            return $user;
        }
        return self::create([
            "{$provider}_id" => $socialUser->getId(),
            'name' => $socialUser->getName() ?? 'No Name',
            'email' => $socialUser->getEmail() ?? "{$provider}_{$socialUser->getId()}@noemail.com",
            'email_verified_at' => now(),
        ]);
    }

    public static function search(?string $keyword=null)
    {

        $query = self::with('status');

        if ($keyword)
        {
            $query ->where(function ($q)use($keyword){
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });


        }
        return $query;
    }

}
