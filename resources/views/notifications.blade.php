@extends('layouts.app')

@section('title', 'Mes notifications')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Mes notifications</h1>
    @if($notifications->count() === 0)
        <div class="bg-white p-6 rounded shadow text-gray-500 text-center">
            Vous n'avez aucune notification pour le moment.
        </div>
    @else
        <div class="bg-white rounded shadow divide-y">
            @foreach($notifications as $notification)
                <div class="flex items-start p-4 {{ $notification->is_read ? 'bg-gray-50' : 'bg-blue-50' }}">
                    <div class="flex-shrink-0 mr-3">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $notification->is_read ? 'bg-gray-200' : 'bg-blue-500' }} text-white">
                            <i class="fas fa-{{ $notification->icon ?? 'bell' }}"></i>
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold {{ $notification->is_read ? 'text-gray-700' : 'text-blue-700' }}">{{ $notification->title }}</div>
                        <div class="text-gray-600 text-sm">{{ $notification->message }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="ml-4 flex flex-col items-end space-y-2">
                        @if(!$notification->is_read)
                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs text-blue-600 hover:underline">Marquer comme lu</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('notifications.delete', $notification) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
