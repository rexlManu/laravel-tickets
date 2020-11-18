<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

class Ticket extends Model
{
    use HasConfigModel;

    protected $fillable = [
        'subject',
        'priority',
        'state'
    ];

    public function getTable()
    {
        return config('laravel-tickets.database.tickets-table');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function user()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

    public function scopeState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
