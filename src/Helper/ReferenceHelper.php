<?php


namespace RexlManu\LaravelTickets\Helper;


class ReferenceHelper
{

    public static function modelToReference($model) : string
    {
        $type = get_class($model);
        return "$type,$model->id";
    }


}
