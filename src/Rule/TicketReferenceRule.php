<?php


namespace RexlManu\LaravelTickets\Rule;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\Relation;
use RexlManu\LaravelTickets\Interfaces\TicketReference;

class TicketReferenceRule implements Rule
{

    /**
     * Determine if the ticket reference is valid
     * Checks
     * if value contains type and id
     * if the model exists
     * if the model is a instance of @param string $attribute
     *
     * @param mixed $value
     *
     * @return bool if the value is valid
     * @link TicketReference
     * if the user has rights to the model
     *
     */
    public function passes($attribute, $value)
    {
        if (! str_contains($value, ',')) return false;

        $values = explode(',', $value);
        if (count($values) !== 2) return false;

        $type = $values[ 0 ];
        if (! class_exists($type)) return false;
        $model = resolve($type)->find($values[ 1 ]);
        if (empty($model)
            || ! $model instanceof TicketReference
            || ! $model->hasReferenceAccess()) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('The reference is not valid');
    }
}
