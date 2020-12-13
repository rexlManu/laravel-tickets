<?php


namespace RexlManu\LaravelTickets\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use RexlManu\LaravelTickets\Traits\HasConfigModel;

/**
 * Class Ticket
 *
 * The main data model for the ticket system
 *
 * @package RexlManu\LaravelTickets\Models
 */
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
     * @return Collection
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

    /**
     * Gives every message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    /**
     * Gets the creator of the ticket,
     * can be null if the user has created ticket himself
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function opener()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

    /**
     * The owner of the ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('laravel-tickets.user'));
    }

    /**
     * The category that the ticket belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * The ticket reference that the ticket binds to
     * Can be null if the user hasnt selected any reference
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reference()
    {
        return $this->hasOne(TicketReference::class);
    }

    /**
     * Gives the complete ticket activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(TicketActivity::class);
    }

    /**
     * Used for filtering the tickets by state
     *
     * @param $query
     * @param $state
     *
     * @return mixed
     */
    public function scopeState($query, $state)
    {
        return $query->whereIn('state', is_string($state) ? [ $state ] : $state);
    }

    /**
     * Used for filtering the tickets by priority
     *
     * @param $query
     * @param $priority
     *
     * @return mixed
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
