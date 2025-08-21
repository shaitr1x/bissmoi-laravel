<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Emailing - Campagne
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.emailing.send') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Sujet</label>
                <input type="text" name="subject" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="message" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Destinataires</label>
                <select name="recipients[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach(App\Models\User::where('role', 'merchant')->get() as $user)
                        <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <small class="text-gray-500">Maintenez Ctrl ou Cmd pour sélectionner plusieurs commerçants.</small>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Envoyer</button>
        </form>
    </div>
</x-admin-layout>
