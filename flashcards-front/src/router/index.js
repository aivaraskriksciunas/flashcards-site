import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/auth/Login.vue'
import AuthTemplate from '../views/templates/AuthTemplate.vue'
import BaseTemplate from '../views/templates/BaseTemplate.vue'
import ViewDeck from '../views/decks/ViewDeck.vue'
import Dashboard from '../views/dashboard/Dashboard.vue'
import ReviseDeck from '../views/decks/ReviseDeck.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        { 
            path: '',
            component: BaseTemplate, 
            children: [
                {
                    path: '',
                    name: 'home',
                    component: Dashboard
                },

                { 
                    path: '/decks/new',
                    name: 'create-deck',
                    component: () => import( '../views/decks/CreateDeck.vue' )
                },
                {
                    path: '/decks/:id',
                    name: 'view-deck',
                    component: ViewDeck
                },
                {
                    path: '/decks/:id/revise',
                    name: 'revise-deck',
                    component: ReviseDeck,
                },
                { 
                    path: '/decks/edit/:id',
                    name: 'edit-deck',
                    component: () => import( '../views/decks/EditDeck.vue' )
                },

            ]
        },
        
        {
            path: '',
            component: AuthTemplate,
            children: [
                {
                    path: '/login',
                    name: 'login',
                    component: LoginView
                }
            ]
        },

        
        
        
    ]
})

export default router
