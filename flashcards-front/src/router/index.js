import axios from 'axios'
import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/auth/Login.vue'
import RegisterView from '../views/auth/Register.vue'
import LogoutView from '../views/auth/Logout.vue'
import AuthTemplate from '../views/templates/AuthTemplate.vue'
import BaseTemplate from '../views/templates/BaseTemplate.vue'
import ViewDeck from '../views/decks/ViewDeck.vue'
import Dashboard from '../views/dashboard/Dashboard.vue'
import AccountPicker from '../views/auth/AccountPicker.vue'
import LearnDeck from '../views/decks/LearnDeck.vue'
import PracticeDeck from '../views/decks/PracticeDeck.vue'
import { useUserStore } from '../stores/user'

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
                    path: '/profile',
                    name: 'profile',
                    component: () => import( '../views/profile/ProfilePage.vue' ),
                },

                { 
                    path: '/decks/new',
                    name: 'create-deck',
                    component: () => import( '../views/decks/CreateDeck.vue' )
                },
                {
                    path: '/decks/:id',
                    name: 'view-deck',
                    component: ViewDeck,
                    meta: { allowGuest: true }
                },
                {
                    path: '/decks/:id/revise',
                    name: 'revise-deck',
                    component: PracticeDeck,
                },
                {
                    path: '/decks/:id/practice',
                    name: 'practice-deck',
                    component: PracticeDeck,
                },
                {
                    path: '/decks/:id/learn',
                    name: 'learn-deck',
                    component: LearnDeck,
                },
                { 
                    path: '/decks/edit/:id',
                    name: 'edit-deck',
                    component: () => import( '../views/decks/EditDeck.vue' )
                },

                {
                    path: '/forum/:topic?',
                    name: 'forum',
                    component: () => import( '../views/forum/Forum.vue' ),
                },
                {
                    path: '/forum/:topic/new',
                    name: 'create-forum-post',
                    component: () => import( '../views/forum/CreatePost.vue' ),
                },
                { 
                    path: '/forum/post/:id',
                    name: 'view-forum-post',
                    component: () => import( '../views/forum/Post.vue' ),
                },

                {
                    path: '/import/quizlet',
                    name: 'import-quizlet',
                    component: () => import( '../views/importing/ImportQuizlet.vue' ),
                },
                {
                    path: '/import/anki',
                    name: 'import-anki',
                    component: () => import( '../views/importing/ImportAnki.vue' ),
                },
            ]
        },
        
        {
            path: '',
            component: AuthTemplate,
            children: [
                {
                    path: '/register',
                    name: 'register',
                    component: RegisterView,
                    meta: { allowGuest: true }
                },
                {
                    path: '/login',
                    name: 'login',
                    component: LoginView,
                    meta: { allowGuest: true }
                },
                {
                    path: '/logout',
                    name: 'logout',
                    component: LogoutView,
                    meta: { allowGuest: true }
                },
                {
                    path: '/verify-email/:verification_code',
                    name: 'verify-email',
                    component: () => import( '../views/auth/VerifyEmail.vue' ),
                    meta: { allowGuest: true }
                },
                {
                    path: '/login/accounts',
                    name: 'account-picker',
                    component: AccountPicker,
                },
                {
                    path: '/register/org',
                    name: 'register-org',
                    component: () => import( '../views/auth/RegisterOrganization.vue' ),
                },
            ]
        },
    ]
})

/**
 * Is Authenticated middleware
 */
router.beforeEach( async ( to ) => {
    const { isLoggedIn, refreshUserInfo } = useUserStore();

    // Allow navigation if logged in or unprotected route
    if ( isLoggedIn || to.meta.allowGuest === true ) {
        return;
    }

    // Attempt to get information about the logged in user from the server
    try {
        await refreshUserInfo()
    }
    catch ( e ) {
        return { name: 'login', query: { r: to.fullPath } }
    }
})

export default router
