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

// Sweet alert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// icons
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import { faLock } from '@fortawesome/free-solid-svg-icons';
import { faAt } from '@fortawesome/free-solid-svg-icons';
import { faArrowRightToBracket } from '@fortawesome/free-solid-svg-icons';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import { faXmark } from '@fortawesome/free-solid-svg-icons';
import { faAnglesRight } from '@fortawesome/free-solid-svg-icons';
import { faAnglesLeft } from '@fortawesome/free-solid-svg-icons';
import { faTrash } from '@fortawesome/free-solid-svg-icons';
import { faFloppyDisk } from '@fortawesome/free-solid-svg-icons';
import { faPen } from '@fortawesome/free-solid-svg-icons';
import { faCircleUser } from '@fortawesome/free-regular-svg-icons';

library.add(faLock, faAt, faArrowRightToBracket, faCircleUser, faPlus, faXmark, faPen, faTrash, faFloppyDisk, faAnglesRight, faAnglesLeft	);

// vars
const axiosInstance = axios.create({
	withCredentials: true,
});

createApp(App)
	.use(router)
	.use(VueAxios, axios)
	.use(Notifications)
	.use(VueSweetalert2)
	.component('font-awesome-icon', FontAwesomeIcon)
	.mount('#app');