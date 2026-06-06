importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyDzjDJDugIH8GWpisuAdYBqmKaAAkQQTMk",
  authDomain: "aishcathering-project.firebaseapp.com",
  projectId: "aishcathering-project",
  storageBucket: "aishcathering-project.firebasestorage.app",
  messagingSenderId: "175555114047",
  appId: "1:175555114047:web:c0b9b80267517003eab48a"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(async (payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  let notificationTitle = payload.notification?.title || payload.data?.title || 'Aish Catering';
  let notificationBody = payload.notification?.body || payload.data?.body || '';

  const isOperationalNotification =
    payload.data?.type === 'operational_status' ||
    /Aish Catering (BUKA|TUTUP)/i.test(notificationTitle || '');

  if (isOperationalNotification) {
    try {
      const response = await fetch('/api/operational-status', { cache: 'no-store' });
      if (response.ok) {
        const status = await response.json();
        notificationTitle = status.title || notificationTitle;
        notificationBody = status.body || notificationBody;
      }
    } catch (error) {
      console.warn('[firebase-messaging-sw.js] Operational status check failed', error);
    }
  }

  const notificationOptions = {
    body: notificationBody,
    icon: '/images/logo.jpg',
    badge: '/images/logo.jpg',
    data: payload.data
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
