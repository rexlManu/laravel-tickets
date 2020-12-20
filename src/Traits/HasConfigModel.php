<?php


namespace RexlManu\LaravelTickets\Traits;


trait HasConfigModel
{

    public function getKeyType()
    {
        return config('laravel-tickets.models.key-type');
    }

    public function isIncrementing()
    {
        return config('laravel-tickets.models.incrementing');
    }
    
    public static function bootHasUuid()
    {
        if(config('laravel-tickets.models.uuid')) {
            static::creating(function ($model) {
                if (empty($model->id)) {
                    $model->id = Uuid::uuid4();
                }
            });
        }
    }
    
}
