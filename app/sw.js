//cache on install
const staticChacheName='paroquia10_2021_02_19_14_35';
const assets = [
	//'/',
	'offline.html',
	'assets/css/material-dashboard.css?v=2.1.1',
    'assets/demo/demo.css',
    'images/icone_transp_compl.png',
    'images/offline.png'
];


// Install stage sets up the index page (home page) in the cache and opens a new cache
self.addEventListener("install", function (event) {
  console.log("[PWA Builder] Install Event processing");

  event.waitUntil(
    caches.open(staticChacheName).then(function (cache) {
      console.log("[PWA Builder] Cached offline page during install");
      return cache.addAll(assets);
    })
  );
});

// If any fetch fails, it will look for the request in the cache and serve it from there first
self.addEventListener("fetch", function (event) {
  if (event.request.method !== "GET") return;
 
  event.respondWith(
  	caches.match(event.request)
  		.then(response => {
  			return response || fetch(event.request);
  		})
  		.catch(() => {
  			return caches.match('offline.html');
  		}) 
  )
});

//clear cache on activate
self.addEventListener("activate", event => {
	event.waitUntil(
		caches.keys().then(cacheNames =>{
			return Promise.all(
				cacheNames
					.filter(cacheName => (cacheName.startsWith('paroquia10')))
					.filter(cacheName => (cacheName !== staticChacheName))
					.map(cacheName => caches.delete(cacheName))
				);
		})
	);
});

importScripts("https://cdn.pushalert.co/sw-31368.js");