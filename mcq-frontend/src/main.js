import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import './assets/main.css';

import VueGoogleSignIn from 'vue3-google-signin';

const app = createApp(App);

app.use(createPinia());
app.use(router);

// google client ID
app.use(VueGoogleSignIn, {
  clientId: '452296054571-c4rcgj8mn2rmc2jtdhepvkr59j4g1ohc.apps.googleusercontent.com'
});

app.mount('#app');
