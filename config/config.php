<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
     * Should file upload be enabled?
     */
    'files' => true,

    'user' => App\Models\User::class,

    'database' => [
        'users-table' => 'users',
        'tickets-table' => 'tickets',
        'ticket-messages-table' => 'ticket_messages'
    ],

    /*
     * How many tickets the user can have open
     */
    'maximal-open-tickets' => 3,
    /*
     * How many days after last message sent, the ticket gets as closed declared
     */
    'autoclose-days' => 7,

    /*
     * Activate permission support
     */
    'permission' => false,

    /*
     * The permissions that should be used for tickets
     */
    'permissions' => [
        'create-ticket' => 'can:tickets.create',
        'list-ticket' => 'can:tickets.index',
        'close-ticket' => 'can:tickets.close',
        'show-ticket' => 'can:tickets.show',
        'message-ticket' => 'can:tickets.message',
    ]
];
