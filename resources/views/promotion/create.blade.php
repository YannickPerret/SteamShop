<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Games') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @if ($game->hasPromotion)
                    <p>Le jeu a déjà une promotion en cours
                @endif
                <h2>Promotion pour le jeu : {{$game->name}}</h2>

                <form action="{{ route('promotion.store', $game->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <label>Nouvelle promotion </label><input type="number" min="0" placeholder="0.00" step="0.01" name="new_price" />
                    <label>Début promotion</label><input type="datetime-local" name="start_promo"/>
                    <label>Fin promotion </label><input type="datetime-local" name="end_promo"/>

                    <button>Ajouter une réduction</button>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>