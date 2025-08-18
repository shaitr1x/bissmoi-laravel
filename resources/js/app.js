// Notifications temps réel admin
if (window.Laravel && window.Laravel.isAdmin && window.Echo) {
    window.Echo.channel('admin.notifications')
        .listen('AdminNotificationCreated', (e) => {
            showAdminNotification(e.notification.title, e.notification.message);
            updateAdminNotifBadge();
        });
}

window.updateAdminNotifBadge = function() {
    fetch('/admin/notifications/unread-count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('adminNotifBadge');
            if (badge) {
                badge.textContent = data.count > 0 ? data.count : '';
                badge.classList.toggle('hidden', data.count === 0);
            }
        });
}

window.showAdminNotification = function(title, message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-5 right-5 bg-blue-700 text-white px-4 py-2 rounded shadow z-50 animate-bounce';
    toast.innerHTML = `<strong>${title}</strong><div>${message}</div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('adminNotifBtn');
    const dropdown = document.getElementById('adminNotifDropdown');
    if (btn && dropdown) {
        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                fetch('/admin/notifications/recent')
                    .then(r => r.json())
                    .then(data => {
                        const list = document.getElementById('adminNotifList');
                        list.innerHTML = data.notifications.map(n => `<div class='p-3 ${n.is_read ? '' : 'bg-gray-50'}'><strong>${n.title}</strong><div class='text-xs'>${n.message}</div></div>`).join('') || '<div class="p-3 text-gray-400">Aucune notification</div>';
                    });
            }
        });
    }
    updateAdminNotifBadge();
});

import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Notifications temps réel utilisateur
import.meta.env.VITE_PUSHER_APP_KEY && window.Echo && window.Laravel && window.Laravel.userId && (() => {
    window.Echo.private('user.' + window.Laravel.userId)
        .listen('UserNotificationCreated', (e) => {
            showNotification(e.notification.title, e.notification.message);
            updateNotifBadge();
        });
})();

// Affichage badge et liste notifications (simplifié)
window.updateNotifBadge = function() {
    fetch('/notifications/unread-count')
        .then(r => r.json())
        .then(data => {
            // Desktop notification badge
            const badge = document.getElementById('notifBadge');
            if (badge) {
                badge.textContent = data.count > 0 ? data.count : '';
                badge.classList.toggle('hidden', data.count === 0);
            }
            
            // Mobile notification badges
            updateMobileNotificationBadges(data.count);
        });
}

// Mise à jour des badges mobiles
window.updateMobileNotificationBadges = function(count) {
    // Badge burger menu dot
    const burgerDot = document.getElementById('burgerNotifDot');
    if (burgerDot) {
        burgerDot.classList.toggle('hidden', !count || count === 0);
    }
    
    // Badge dans le menu sliding mobile
    const mobileNotifCount = document.getElementById('mobileNotifCount');
    if (mobileNotifCount) {
        if (count > 0) {
            mobileNotifCount.textContent = count > 99 ? '99+' : count;
            mobileNotifCount.classList.remove('hidden');
        } else {
            mobileNotifCount.classList.add('hidden');
        }
    }
    
    // Badge dans la navigation bottom mobile
    const bottomNotifCount = document.getElementById('bottomNotifCount');
    if (bottomNotifCount) {
        if (count > 0) {
            bottomNotifCount.textContent = count > 99 ? '99+' : count;
            bottomNotifCount.classList.remove('hidden');
        } else {
            bottomNotifCount.classList.add('hidden');
        }
    }
}

// Mise à jour du panier (pour synchroniser avec la nav mobile)
window.updateCartCount = function() {
    fetch('/api/cart/count', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(r => r.json())
    .then(data => {
        // Desktop cart badge
        const cartBadge = document.getElementById('cartCount');
        if (cartBadge) {
            if (data.count > 0) {
                cartBadge.textContent = data.count;
                cartBadge.classList.remove('hidden');
            } else {
                cartBadge.classList.add('hidden');
            }
        }
        
        // Mobile cart badges
        const mobileCartCount = document.getElementById('mobileCartCount');
        if (mobileCartCount) {
            if (data.count > 0) {
                mobileCartCount.textContent = data.count > 99 ? '99+' : data.count;
                mobileCartCount.classList.remove('hidden');
            } else {
                mobileCartCount.classList.add('hidden');
            }
        }
        
        const bottomCartCount = document.getElementById('bottomCartCount');
        if (bottomCartCount) {
            if (data.count > 0) {
                bottomCartCount.textContent = data.count > 99 ? '99+' : data.count;
                bottomCartCount.classList.remove('hidden');
            } else {
                bottomCartCount.classList.add('hidden');
            }
        }
    })
    .catch(() => {
        // Fallback silencieux
        console.debug('Could not fetch cart count');
    });
}

window.showNotification = function(title, message) {
    if (!window.Alpine) return;
    
    // Toast moderne et responsive
    const toast = document.createElement('div');
    toast.className = 'fixed top-5 right-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm transform transition-all duration-300 ease-out translate-x-full opacity-0';
    toast.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm">${title}</p>
                <p class="text-blue-100 text-xs mt-1">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="flex-shrink-0 text-blue-200 hover:text-white transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animation d'entrée
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    });

    // Effet mobile : vibration + highlight badge
    if (window.navigator && window.navigator.vibrate) {
        window.navigator.vibrate([200, 100, 200]);
    }
    
    // Animation du badge desktop
    const badge = document.getElementById('notifBadge');
    if (badge) {
        badge.classList.add('ring-4', 'ring-blue-400', 'ring-opacity-60', 'animate-pulse');
        setTimeout(() => {
            badge.classList.remove('ring-4', 'ring-blue-400', 'ring-opacity-60', 'animate-pulse');
        }, 2000);
    }
    
    // Animation des badges mobiles
    const bottomNotifCount = document.getElementById('bottomNotifCount');
    if (bottomNotifCount && !bottomNotifCount.classList.contains('hidden')) {
        bottomNotifCount.classList.add('animate-bounce');
        setTimeout(() => bottomNotifCount.classList.remove('animate-bounce'), 1000);
    }

    // Auto-hide après 5 secondes
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
}

document.addEventListener('DOMContentLoaded', () => {
    // Desktop notification dropdown
    const btn = document.getElementById('notifBtn');
    const dropdown = document.getElementById('notifDropdown');
    if (btn && dropdown) {
        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                fetch('/notifications/recent')
                    .then(r => r.json())
                    .then(data => {
                        const list = document.getElementById('notifList');
                        list.innerHTML = data.notifications.map(n => `<div class='p-3 ${n.is_read ? '' : 'bg-gray-50'}'><strong>${n.title}</strong><div class='text-xs'>${n.message}</div></div>`).join('') || '<div class="p-3 text-gray-400">Aucune notification</div>';
                    });
            }
        });
    }

    // Mobile notification link in sliding menu
    const mobileNotifLink = document.getElementById('mobileNotifLink');
    if (mobileNotifLink && mobileNotifLink.getAttribute('href') === '#') {
        mobileNotifLink.addEventListener('click', (e) => {
            e.preventDefault();
            // Redirect to notifications page
            window.location.href = '/notifications';
        });
    }

    // Initialisation des compteurs
    updateNotifBadge();
    updateCartCount();
    
    // Mise à jour périodique
    setInterval(() => {
        updateNotifBadge();
        updateCartCount();
    }, 30000); // Toutes les 30 secondes
});
