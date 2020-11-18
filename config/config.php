<?php

/*
 * The configuration for laravel-tickets
 */
return [
    /*
     * Should file upload be enabled?
     */
    'files' => true,
    'file' => [
        /*
         * Where should the files be saved?
         */
        'driver' => 'local',
        /*
         * Path for files
         */
        'path' => 'tickets/',
        /*
         * File size limit
         * The size is in kilobytes, so 5120 = 5 megabytes
         */
        'size-limit' => 5120,
        /*
         * Allowed file types
         * Full extension list: https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
         */
        'memes' => 'pdf,png,jpg',
        /*
         * Maximal file uploads for message
         */
        'max-files' => 5
    ],

    /*
     * The user model
     */
    'user' => App\Models\User::class,
    /*
     * The default guard for authentication middleware
     */
    'guard' => [ 'web', 'auth' ],

    /*
     * Database tables name
     */
    'database' => [
        'users-table' => 'users',
        'tickets-table' => 'tickets',
        'ticket-messages-table' => 'ticket_messages',
        'ticket-uploads-table' => 'ticket_uploads',
        'ticket-categories-table' => 'ticket_categories',
        'ticket-references-table' => 'ticket_references'
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
     * User can reopen a ticket with a answer
     */
    'open-ticket-with-answer' => true,

    /*
     * Date format
     */
    'datetime-format' => 'H:i d.m.Y',

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
        'download-ticket' => 'can:tickets.download',
        /*
         * For administrate tickets
         */
        'all-ticket' => 'can:tickets.all',
    ],

    /*
     * The priorities
     */
    'priorities' => [ 'LOW', 'MID', 'HIGH' ],
    /*
     * Layout view
     */
    'layouts' => 'layouts.app',
    /*
     * Force pdf to preview instead to download
     */
    'pdf-force-preview' => true,

    /*
     * Use uuids instead of unsigned integers
     */
    'models' => [
        'incrementing' => true,
        'key-type' => 'int',
        /*
         * If you use uuids for your database, please adjust the structure in the migration itself, because everybody works differently
         */
        'uuid' => false
    ],

    /*
     * Enable categories for tickets
     */
    'category' => true,
    /*
     * Enable references for tickets
     */
    'references' => true,
    'references-nullable' => true,
    /*
     * Ether you define your models for references or customize the view.
     */
    'reference-models' => []
];
