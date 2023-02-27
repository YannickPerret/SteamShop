<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <!-- postObject form -->

                    <form id="create-game" method="{{ $formAttributes['method'] }}" action="{{ $formAttributes['action'] }}" class="mt-12 space-y-12" enctype="{{ $formAttributes['enctype'] }}">
                        @foreach ($formInputs as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}" required />
                        @endforeach

                        <div>
                            <x-input-label for="name" value="{{ __('Name') }}"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div>
                            <x-input-label for="email" value="{{ __('Price') }}"/>
                            <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" step=".01" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('price')"/>
                        </div>

                        <div>
                            <x-input-label for="email" value="{{ __('Description') }}"/>
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full"/>
                            <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                        </div>


                        <div class="col-md-6">
                            <x-text-input name="image_path" type="file" class="mt-1 block w-full" required/>
                            <x-text-input id="image_path" type="hidden" value="{{$imageURL}}"/>
                        </div>

                        <div>
                            <x-input-label for="email" value="{{ __('Release date') }}"/>
                            <x-text-input id="release_date" name="release_date" type="datetime-local" class="mt-1 block w-full datetimepicker" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('release_date')"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Save</x-primary-button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.querySelector("#create-game").addEventListener('submit', async () => {
        event.preventDefault();
        const formData = new FormData();
        //put input in formData
        event.target.querySelectorAll("input").forEach((input) => {
            if (!input.name.includes("image_path")){
                formData.append(input.name, input.value);
            }
        });
        //put @csrf token in formData
        formData.append("_token", "{{ csrf_token() }}");
        //put input in formData
        formData.append("image_path", document.getElementById("image_path").value);
        //send request to presigned url

        await fetch('{{route('games.store')}}', {
            method: 'POST',
            credentials: "same-origin", 
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData, 
        })
        .then((response) => {
            if (response.ok) {
                document.querySelector("#image_path").remove();
            }
        })  // parse JSON from request
        .catch((error) => {                        // catch
            console.log('Request failed', error);
        });
    });
</script>