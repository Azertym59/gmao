<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="flex justify-between items-center border-b border-gray-700 px-6 py-3">
                    <h3 class="text-lg font-medium text-white">Vos notifications</h3>
                    @if($notifications->where('is_read', false)->count() > 0)
                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-accent-blue hover:text-blue-400">
                                Marquer tout comme lu
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="p-6">
                    @if($notifications->count() > 0)
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="flex items-start p-4 {{ $notification->is_read ? 'bg-gray-800/30' : 'bg-gray-800/50 border-l-4 border-accent-blue' }} rounded-lg transition-all">
                                    <!-- Icône du type de notification -->
                                    <div class="flex-shrink-0 mr-4">
                                        @if($notification->type === 'assignment')
                                            <div class="p-2 bg-green-500/20 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'deadline')
                                            <div class="p-2 bg-yellow-500/20 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'comment')
                                            <div class="p-2 bg-purple-500/20 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="p-2 bg-blue-500/20 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Contenu de la notification -->
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <h4 class="font-medium text-white">{{ $notification->title }}</h4>
                                            <span class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-300 mt-1">{{ $notification->message }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <a href="{{ $notification->link }}" class="text-sm text-accent-blue hover:text-blue-400">
                                                Voir les détails
                                            </a>
                                            <div class="flex space-x-2">
                                                @if(!$notification->is_read)
                                                    <button onclick="markAsRead({{ $notification->id }})" class="text-xs text-gray-400 hover:text-white">
                                                        Marquer comme lu
                                                    </button>
                                                @endif
                                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-gray-400 hover:text-white">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p class="text-gray-400 text-lg">Vous n'avez aucune notification.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour marquer comme lu avec Ajax -->
    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Rafraîchir la page pour mettre à jour l'interface
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-app-layout>