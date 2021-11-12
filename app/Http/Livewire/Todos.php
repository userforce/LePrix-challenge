<?php

namespace App\Http\Livewire;

use App\Models\Todo;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Todos extends Component
{
    public ?Todo $selectedTodo = null;
    public string $name = '';

    public function render(): View
    {
        return view('livewire.todos', ['todos' => $this->getTodos()]);
    }

    protected $rules = [
        'name' => 'required|string|min:5|max:255'
    ];

    public function update(Todo $todo, array $formData): void
    {
        $validatedData = Validator::make(
            $formData,
            $this->rules
        )->validate();

        $todo->update(Arr::only($validatedData, ['name']));
    }

    public function create(): void
    {
        $this->validate();

        auth()->user()->todos()->create([
            'name' => $this->name,
            'user'
        ]);
    }

    public function delete(Todo $todo): void
    {
        $todo->delete();
    }

    public function setCompleted(Todo $todo): void
    {
        $todo->update(['completed' => true]);
    }

    public function setTodo(int $id): void
    {
        $this->selectedTodo = Todo::find($id);
        $this->emit('showEdit');
    }

    private function getTodos(): Collection
    {
        // if we want to push completed to the bottom as in task
        // and at the same time keep not complete orderd my updated date on the top
        // otherwise we can just do
        // Todo::orderBy('completed')->orderBy('updated_at')->get();
        // to keep last completed at the bottom
        return Todo::withTrashed()
            ->orderByRaw('CASE WHEN completed = 0 THEN updated_at END desc')
            ->orderByRaw('CASE WHEN completed = 1 THEN updated_at END asc')
            ->get();
    }

}
