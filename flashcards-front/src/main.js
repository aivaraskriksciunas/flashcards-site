import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'

import App from './App.vue'
import router from './router'

import './assets/base.css'
import Cookies from 'js-cookie'
import { setApiCookie } from './utils'

const app = createApp(App)

app.use(createPinia())
app.use(router)

// Set axios defaults
axios.defaults.baseURL = import.meta.env.VITE_BACKEND_URL
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true
axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'

let api_key = Cookies.get( 'api_key' )
if ( api_key ) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${api_key}`;
    setApiCookie( api_key );
}

app.mount('#app')


