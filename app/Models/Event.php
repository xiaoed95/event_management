<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;
use Carbon\Carbon;

class Event extends Model
{

    protected $fillable = [
        'name',
        'venue',
        'datetime',
        'Status',
        'poster',
        'user_id'
    ];

    const PENDING=1;
    const APPROVED=2;
    const REJECTED=3;

    use HasFactory;

    public static function statusOption() {
        return [
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        ];
    }

    public function statusName(): Attribute
    {

        return Attribute::make(
            get: fn($value, $attributes) => self::statusOption()[$attributes['Status']],
        );
    }
    
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');

    }

    public function getOrganizerNameAttribute()
    {
        return $this->organizer?->name;
    }

    public function eventparticipants()
    {
        return $this->belongsToMany(User::class,'event_participants', 'event_id', 'user_id');
    }
    public function isApproved()
    {
        return $this->Status == self::APPROVED;
    }

    public function isExpired()
    {
        if (is_null($this->datetime)) {
            return true;
        }
        $date = Carbon::parse($this->datetime);
        if ($date->isPast()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function scopeApproved($query)
    {
        return $query->where('Status',Event::APPROVED); // added
    }

}



