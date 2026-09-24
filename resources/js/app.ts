import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createPinia, setActivePinia } from 'pinia';
import App from './App.vue';
import router from './router';
import i18n from './locales/i18n';
import { useThemeStore } from './stores/theme';
import { registerPwa } from './services/registerServiceWorker';

const app = createApp(App);
const pinia = createPinia();
setActivePinia(pinia);

app.use(pinia);
app.use(router);
app.use(i18n);

const themeStore = useThemeStore(pinia);
themeStore.initTheme();

registerPwa();

app.mount('#app');
