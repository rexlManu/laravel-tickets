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
        'state',
        'category_id',
    ];

    public function getTable()
    {
        return config('laravel-tickets.database.tickets-table');
    }

    /**
     * returns every user that had sent a message in the ticket
     *
     * @param false $ticketCreatorIncluded if the ticket user should be included
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRelatedUsers($ticketCreatorIncluded = false)
    {
        return $this
            ->messages()
            ->whereNotIn('user_id', $ticketCreatorIncluded ? [] : [ $this->user_id ])
            ->pluck('user_id')
            ->unique()
            ->values();
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function opener()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

    public function user()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function reference()
    {
        return $this->hasOne(TicketReference::class);
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class);
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
