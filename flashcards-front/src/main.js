import { createApp } from 'vue'
import { createPinia } from 'pinia'
import FontAwesomeIcon from './fontawesome'
import axios from 'axios'

import App from './App.vue'
import router from './router'

import './assets/base.css'
import Cookies from 'js-cookie'

const app = createApp(App)

app.use(createPinia())
app.use(router)

// Set axios defaults
axios.defaults.baseURL = import.meta.env.VITE_BACKEND_URL
axios.defaults.withCredentials = true
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'

let api_key = Cookies.get( 'api_key' )
if ( api_key ) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${api_key}`;
    Cookies.set( 'api_key', api_key, { expires: 40, sameSite: 'strict' } );
}

app.component( 'font-awesome-icon', FontAwesomeIcon ).mount('#app')
