<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Filament\Models\Contracts\FilamentUser;
use App\Models\Event;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    
    const ADMIN=1;
    const ORGANIZER=2;
    const STUDENT=3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_ID',
        'role',
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
    ];

    protected $appends = [
        'role_name'
    ];

    public static function roleOption() {
        return [
            self::ADMIN => 'Admin',
            self::ORGANIZER => 'Organizer',
            self::STUDENT => 'Student',
        ];
    }


    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function roleName(): Attribute
    {

        return Attribute::make(
            get: fn($value, $attributes) => self::roleOption()[$attributes['role']],
        );
    }

    public function canAccessFilament(): bool
    {
        return true;
 
    }

    public function isNotAdmin(){
        return $this->role != self::ADMIN;
    }

    public function isOrganizer(){
        return $this->role == self::ORGANIZER;
    }

    public function isAdmin(){
        return $this->role == self::ADMIN;
    }

    public static function getUserOption()
    {
      return  User::select('id','name')->get()->pluck('name','id');
    }

    public function participatedEvents()
    {
        return $this->belongsToMany(Event::class,'event_participants', 'user_id', 'event_id');
    }
}
