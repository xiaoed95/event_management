<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{

    protected $fillable = [
        'name',
        'venue',
        'datetime',
        'Status',
        'poster'
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
}



