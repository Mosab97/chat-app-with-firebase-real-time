
importScripts('https://www.gstatic.com/firebasejs/7.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.10.0/firebase-messaging.js');

var firebaseConfig = {
    apiKey: "AIzaSyCnXmBxXbum9P_U8IkL8vqUdFezVB0SYuA",
    authDomain: "laravel-with-firebase-test.firebaseapp.com",
    databaseURL: "https://laravel-with-firebase-test.firebaseio.com",
    projectId: "laravel-with-firebase-test",
    storageBucket: "laravel-with-firebase-test.appspot.com",
    messagingSenderId: "768791739190",
    appId: "1:768791739190:web:113b7ae0b47faf6b801197",
    measurementId: "G-56ZY3V05WW"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();


messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    const {title, body} = payload.notification;
    const notificationOptions = {
        body,
    };
    return self.registration.showNotification(title,
        notificationOptions);
});



