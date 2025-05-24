import '../css/app.css';
import './bootstrap';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

import { createApp } from 'vue'
import App from './Pages/App.vue'
import router from './router'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

const app = createApp(App);

app
    .use(router)
    .use(PrimeVue, 
        { ripple: true, 
            inputStyle: 'filled',
            theme: {
                preset : Aura
            }
        })
    .mount('#app')

