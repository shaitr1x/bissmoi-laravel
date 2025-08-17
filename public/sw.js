self.addEventListener('install', function(event) {
  console.log('Service worker installé');
});

self.addEventListener('activate', function(event) {
  console.log('Service worker activé');
});

self.addEventListener('fetch', function(event) {
  // Service worker basique - pas de cache pour simplifier
});
