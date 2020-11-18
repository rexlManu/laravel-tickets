<?php


namespace RexlManu\LaravelTickets\Traits;


use Ramsey\Uuid\Uuid;

trait HasConfigModel
{

    public function getKeyType()
    {
        return 'string';
    }

    public function isIncrementing()
    {
        return config('laravel-tickets.model.incrementing');
    }

    public static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (config('laravel-tickets.model.uuid') && empty($model->id)) {
                $model->id = Uuid::uuid4();
            }
        });
    }

}
