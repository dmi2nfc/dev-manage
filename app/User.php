<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    public function activeSprints()
    {
        return $this->hasMany(Sprint::class)->whereNull('sprints.closed_at');
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function payedSprints()
    {
        return $this->hasMany(Sprint::class)->where('sprints.payment_status', 2);
    }
}
