<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'rate', 'rate_type', 'worked_time', 'payment_status',
        'notes', 'started_at', 'closed_at', 'currency_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'name', 'email', 'is_active']);
    }

    public function project()
    {
        return $this->belongsTo(Project::class)->select(['id', 'name', 'customer_id']);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
