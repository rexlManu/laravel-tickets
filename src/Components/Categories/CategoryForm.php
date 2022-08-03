<?php

namespace RexlManu\LaravelTickets\Components\Categories;

use Livewire\Component;
use RexlManu\LaravelTickets\Models\TicketCategory;

class CategoryForm extends Component
{
    public $action;
    public $category;

    public $translation;

    public function mount($action = 'add', TicketCategory $category)
    {
        $this->action = $action;
        $this->category = $category;

        if ($category) {
            $this->translation = $category->translation;
        }
    }

    public function render()
    {
        return view('laravel-tickets::categories.form');
    }

    public function store()
    {
        $this->validate([
            'translation' => ['required', 'string', 'max:191'],
        ], [], [
            'translation' => __('Translation')
        ]);

        if ($this->action == 'edit') {
            $this->category->translation = $this->translation;
            $this->category->save();
            session()->flash('success', __('The category was successfully updated'));
        } else if ($this->action == 'add') {
            TicketCategory::create(
                [
                    'translation' => $this->translation
                ]
            );
            session()->flash('success', __('The category was successfully created'));
        }

        return redirect()->to(route('laravel-tickets.categories.index'));
    }
}
