@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-grey text-sm font-normal">
                <a href="/projects" class="text-grey text-sm font-normal no-underline">
                    Mes Généalogies
                </a> /
                <a href="{{ $project->path() }}" class="text-grey text-sm font-normal no-underline">
                    {{ $project->title }}
                </a> / {{ $person->completeName() }}
            </p>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <div class="flex items-center">
                        <div class="flex-1 mr-2">
                            <div class="card mb-3">Nom Prenom</div>
                        </div>
                        <div class="flex-1 mx-2">
                            <div class="card mb-3">Nom Prenom</div>
                        </div>
                        <div class="flex-1 mx-2">
                            <div class="card mb-3">Nom Prenom</div>
                        </div>
                        <div class="flex-1 ml-2">
                            <div class="card mb-3">Nom Prenom</div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-1 mr-2">
                            <div class="card mb-3">
                                <h4 class="font-normal mb-2">Nom Prenom</h4>
                                <div class="text-grey text-sm w-full mb-2">
                                    Né le à / Mort le à
                                </div>
                                <div class="text-grey text-sm w-full mb-1">

                                </div>
                            </div>
                        </div>
                        <div class="flex-1 ml-2">
                            <div class="card mb-3">
                                <h4 class="font-normal mb-2">Nom Prenom</h4>
                                <div class="text-grey text-sm w-full mb-2">
                                    Né le à / Mort le à
                                </div>
                                <div class="text-grey text-sm w-full mb-1">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="card mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-normal mb-1">{{ $person->completeName() }}</h4>

                                    <div>
                                        <span class="text-xs text-grey">
                                            {{ $person->profession ?: 'Profession inconnue' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-grey text-sm w-full mb-2">
                                    <div class="mb-1">
                                    @if($person->birthdate || $person->birthplace)
                                        Naissance :
                                        {{ $person->birthdate ? ' le ' . $person->birthdate : '' }}
                                        {{ $person->birthplace ? ' à ' . $person->birthplace : '' }}
                                    @else
                                        Date et lieu de naissance inconnus
                                    @endif
                                    </div>

                                    <div>
                                    @if($person->death_date || $person->death_place)
                                        Décès :
                                        {{ $person->death_date ? ' le ' . $person->death_date : '' }}
                                        {{ $person->death_place ? ' à ' . $person->death_place : '' }}
                                    @else
                                        Date et lieu de décès inconnus
                                    @endif
                                    </div>
                                </div>
                                <div class="flex items-center justify-end text-grey text-sm w-full mb-1">
                                    <button @click.prevent="$modal.show('new-person')"
                                            class="button mr-2">
                                        Modifier
                                    </button>

{{--                                    <form method="POST" action="{{ $person->path() }}" class="text-right">--}}
{{--                                        @method('DELETE')--}}
{{--                                        @csrf--}}

                                        <button type="submit" class="text-sm">Supprimer</button>
{{--                                    </form>--}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-1 mr-2">
                            <div class="card mb-3">Nom Prenom</div>
                        </div>
                        <div class="flex-1 ml-2">
                            <div class="card mb-3">Nom Prenom</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/4 px-3">
                <div class="card mb-4" style="height: 200px">
                    <form action="{{ $person->path() }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-normal">Notes</h4>

                            <button type="submit" class="text-sm text-grey-darker">Valider</button>
                        </div>

                        <textarea
                            name="notes"
                            rows="7"
                            class="text-grey-dark text-sm p-2 font-normal w-full border border-grey-light rounded">{{ $person->notes }}</textarea>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <new-person-modal :person="{{ $person }}"></new-person-modal>
@endsection