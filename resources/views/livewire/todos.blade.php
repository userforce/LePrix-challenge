<div>
    <div class="flex flex-col mt-8">


        <div class="mt-3 px-4">
            <form method="POST" wire:submit.prevent="create">
                @csrf
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Create Todo
                </h3>
                <div class="mt-2 pwb-7">
                    <input
                        type="text"
                        name="name"
                        wire:model.lazy="name"
                        class="px-4 py-2 bg-white-500 ring-1 ring-grey-300 w-full text-grey-500 text-base font-medium rounded-md shadow-sm hover:bg-white-600 focus:outline-none"/>
                </div>
                <div class="py-3 text-right">
                    @error('name')
                        <span class="text-red-600 float-left">{{ $message }}</span>
                    @enderror
                    <button
                        type="submit"
                        class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Create
                    </button>
                </div>
            </form>
        </div>

        <div class="py-2">
            <div
                class="min-w-full border-b border-gray-200 shadow">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                Id
                            </th>
                            <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                Completed
                            </th>
                            <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                Updated
                            </th>
                            <th class="px-6 py-3 text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach($todos as $todo)
                            <tr class="{{$todo->deleted_at ? 'opacity-25' : ''}}">
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $todo->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        {{ $todo->name }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm text-green-400">
                                        <input type="checkbox"
                                            {{ $todo->completed ? 'checked disabled' : '' }}
                                            wire:change="setCompleted({{$todo->id}})">
                                        @if ($todo->completed)
                                            <span class="font-black text-1xl">&check;</span>
                                            Completed
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        {{ $todo->updated_at }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 border-b border-gray-200">
                                    @if (!$todo->deleted_at)
                                        <button
                                            wire:click="setTodo({{ $todo->id }})"
                                            class="bg-green-500 text-white rounded-md px-4 py-1 mr-2 text-base font-medium hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 edit-todo">
                                            Edit
                                        </button>
                                        <button
                                            wire:click="delete({{ $todo->id }})"
                                            class="bg-red-500 text-white rounded-md px-4 py-1 text-base font-medium hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                            Delete
                                        </button>
                                    @else
                                        <span>Deleted</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="edit-modal"
        class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 shadow-lg rounded-md bg-white max-w-7xl mx-auto sm:px-4 lg:px-4">
            @if ($selectedTodo)
                <div class="mt-3 px-4">
                    <form method="POST" wire:submit.prevent="update({{ $selectedTodo->id }}, Object.fromEntries(new FormData($event.target)))">
                        @csrf
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Edit Todo: # {{$selectedTodo->id}}
                        </h3>
                        <div class="mt-2 pwb-7">
                            <input
                                type="text"
                                name="name"
                                value="{{$selectedTodo->name}}"
                                class="px-4 py-2 bg-white-500 ring-1 ring-grey-300 w-full text-grey-500 text-base font-medium rounded-md shadow-sm hover:bg-white-600 focus:outline-none"/>
                        </div>
                        <div class="py-3 text-right">
                            <button
                                type="submit"
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                                Update
                            </button>
                            <button
                                type="button"
                                wire:click="$emit('tooggleModal')"
                                class="px-4 py-2 bg-white-500 float-left ring-2 ring-grey-500 text-grey-500 text-base font-medium rounded-md shadow-sm hover:bg-white-600 focus:outline-none focus:ring-2 focus:ring-grey-300">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('showEdit', () => {
            document.querySelector("#edit-modal").classList.remove('hidden');
        });
        Livewire.on('tooggleModal', () => {
            let modal = document.querySelector("#edit-modal");
            let showModal = Object.values(modal.classList).includes('hidden');
            document.querySelector("#edit-modal").classList[showModal ? 'remove' : 'add']('hidden');
        });
    })
</script>
