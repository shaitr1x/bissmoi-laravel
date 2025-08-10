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
            const badge = document.getElementById('notifBadge');
            if (badge) {
                badge.textContent = data.count > 0 ? data.count : '';
                badge.classList.toggle('hidden', data.count === 0);
            }
        });
}

window.showNotification = function(title, message) {
    if (!window.Alpine) return;
    // Toast simple
    const toast = document.createElement('div');
    toast.className = 'fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow z-50 animate-bounce';
    toast.innerHTML = `<strong>${title}</strong><div>${message}</div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);

    // Effet mobile : vibration + highlight badge
    if (window.navigator && window.navigator.vibrate) {
        window.navigator.vibrate([200, 100, 200]);
    }
    const badge = document.getElementById('notifBadge');
    if (badge) {
        badge.classList.add('ring-4', 'ring-green-400', 'ring-opacity-60');
        setTimeout(() => badge.classList.remove('ring-4', 'ring-green-400', 'ring-opacity-60'), 1200);
    }
}

document.addEventListener('DOMContentLoaded', () => {
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

    // Mobile : point rouge sur burger si notif non lue
    function updateBurgerNotifDot(count) {
        const dot = document.getElementById('burgerNotifDot');
        if (dot) {
            dot.classList.toggle('hidden', !count || count === 0);
        }
    }
    // Mobile : compteur sur lien Notifications dans menu mobile
    function updateMobileNotifCount(count) {
        const badge = document.getElementById('mobileNotifCount');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }
    // Met à jour les deux en même temps
    function updateMobileNotifs() {
        fetch('/notifications/unread-count')
            .then(r => r.json())
            .then(data => {
                updateBurgerNotifDot(data.count);
                updateMobileNotifCount(data.count);
            });
    }
    // Sur ouverture du menu burger, maj compteur mobile
    const burgerBtn = document.querySelector('button[aria-label="Toggle navigation"]') || document.querySelector('.sm\\:hidden button');
    if (burgerBtn) {
        burgerBtn.addEventListener('click', () => {
            setTimeout(updateMobileNotifs, 200); // petit délai pour affichage
        });
    }
    updateNotifBadge();
    updateMobileNotifs();
});
