<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    
use Notifiable, SoftDeletes;

    public const SKILLS = [
        'PHP',
        'Laravel',
        'MySQL',
        'Vue.js',
        'React',
        'Docker',
        'REST API',
        'Git',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'skills',
        'date_of_birth',
        'joining_date',
        'avatar',
        'country_id',
        'state_id',
        'city_id',
        'status',
    ];

    protected $casts = [
        'skills' => 'array',
        'date_of_birth' => 'date',
        'joining_date' => 'date',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}"
        );
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->avatar ? asset('storage/' . $this->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=0D8ABC&color=fff'
        );
    }
}