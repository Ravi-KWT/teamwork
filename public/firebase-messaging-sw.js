
importScripts('https://www.gstatic.com/firebasejs/7.9.3/firebase-app.js');

importScripts('https://www.gstatic.com/firebasejs/7.9.3/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
var firebaseConfig = {
  apiKey: "AIzaSyAEm9cjXTgLji1Bh0Z6BTBSldIrskreHcs",
  authDomain: "teamwork-bff6a.firebaseapp.com",
  databaseURL: "https://teamwork-bff6a.firebaseio.com",
  projectId: "teamwork-bff6a",
  storageBucket: "teamwork-bff6a.appspot.com",
  messagingSenderId: "249944962835",
  appId: "1:249944962835:web:92d07c58e36427226c4ce6",
  measurementId: "G-VK3Q35YP24"
};
firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/itwonders-web-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});