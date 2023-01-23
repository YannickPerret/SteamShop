<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div>
                    <a href="{{ route('games.show', $game) }}">
                        <img src="{{ url($game->image_path) }}" style="max-width: 200px">
                    </a>
                    <x-input-label for="name" value="{{ $game->name }}"/>
                        @if ($game->hasPromotion->start_promo <= now())
                        <td>
                            Price :
                            <del><x-input-label for="name" value="{{ $game->price }}"/></del>
                            Promotion : <x-input-label for="name" value="{{ $game->hasPromotion->new_price }}"/>
                        </td>
                        @else
                        <td>
                            <x-input-label for="name" value="{{ $game->price }}"/>
                        </td>
                        @endif
                    <x-input-label for="name" value="{{ $game->release_date }}"/>
                    @if($game->release_date > now())
                        <x-primary-button disabled>Coming soon</x-primary-button>
                    @elseif(Auth::check() && Auth::user()->hasGame($game))
                        <x-primary-button disabled>In library</x-primary-button>
                    @else
                        <form method="post" action="{{ route('games.purchase', $game) }}">
                            {{ csrf_field() }}
                            <div class="flex items-center gap-4">
                                <x-primary-button>Buy</x-primary-button>
                            </div>
                        </form>
                    @endif
                    @if(Auth::check() && $game->owner_id == Auth::User()->id)
                        <a href="{{route('promotion.create', $game->id)}}">Promote</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
