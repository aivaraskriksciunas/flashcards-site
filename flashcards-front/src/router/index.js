import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/auth/Login.vue'
import AuthTemplate from '../views/templates/AuthTemplate.vue'
import BaseTemplate from '../views/templates/BaseTemplate.vue'
import Dashboard from '../views/Dashboard.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [

        { 
            path: '',
            component: BaseTemplate, 
            children: [
                {
                    path: '',
                    component: Dashboard
                }
            ]
        },
        
        {
            path: '',
            component: AuthTemplate,
            children: [
                {
                    path: '/login',
                    component: LoginView
                }
            ]
        },
    ]
})

export default router
