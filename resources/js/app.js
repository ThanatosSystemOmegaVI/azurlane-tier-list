import { createApp } from 'vue'

// main
import App from './App.vue'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

// routing
import axios from 'axios'
import VueAxios from 'vue-axios'
import router from './router'

// notifications
import Notifications from '@kyvg/vue3-notification'

// icons
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import { faLock } from '@fortawesome/free-solid-svg-icons';
import { faAt } from '@fortawesome/free-solid-svg-icons';
import { faArrowRightToBracket } from '@fortawesome/free-solid-svg-icons';
import { faCircleUser } from '@fortawesome/free-regular-svg-icons';

library.add(faLock, faAt, faArrowRightToBracket, faCircleUser);

// vars
const axiosInstance = axios.create({
	withCredentials: true,
});

createApp(App)
	.use(router)
	.use(VueAxios, axios)
	.use(Notifications)
	.component('font-awesome-icon', FontAwesomeIcon)
	.mount('#app');