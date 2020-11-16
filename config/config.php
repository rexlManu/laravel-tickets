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

    /*
     * Activate permission support
     */
    'permission' => false,

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
    'autoclose-days' => 7
];
