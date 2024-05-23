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

const isOrgManager = ( to ) => {
    const { isOrgManager: isManager } = useUserStore();
    
    if ( !isManager() ) return false;
}

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
                    path: '/decks/new/from-notes',
                    name: 'create-deck-from-notes',
                    component: () => import( '../views/decks/CreateDeckFromNotes.vue' )
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
                    path: '/import/wordlist',
                    name: 'import-wordlist',
                    component: () => import( '../views/importing/ImportWordList.vue' ),
                },
                {
                    path: '/import/anki',
                    name: 'import-anki',
                    component: () => import( '../views/importing/ImportAnki.vue' ),
                },

                {
                    path: '/course/create',
                    name: 'create-course',
                    component: () => import( '../views/courses/CreateCourse.vue' ),
                },
                {
                    path: '/course/:id/view',
                    name: 'course-summary',
                    component: () => import( '../views/courses/CourseSummary.vue' ),
                },
                {
                    path: '/course/:id/invite',
                    name: 'course-assign-members',
                    component: () => import( '../views/courses/InviteMembers.vue' ),
                    beforeEnter: [ isOrgManager ],
                },
                {
                    path: '/course/:id/edit',
                    component: () => import( '../views/courses/EditCourse.vue' ),
                    children: [
                        {
                            path: '',
                            name: 'edit-course',
                            component: () => import( '../views/courses/EditCourseContent.vue' ),
                        },
                        {
                            path: 'page/:page_id',
                            name: 'edit-course-page',
                            component: () => import( '../views/courses/EditCoursePage.vue' ),
                        }
                    ],
                },
                {
                    path: '/members',
                    name: 'org-members',
                    component: () => import( '../views/org-members/ShowMembers.vue' ),
                    beforeEnter: [ isOrgManager ],
                }
            ]
        },

        {
            path: '/course/:id',
            name: 'view-course',
            component: () => import( '../views/view-course/ViewCourse.vue' ),
            children: [
                {
                    path: 'page/:page_id',
                    name: 'view-course-page',
                    component: () => import( '../views/view-course/ViewCoursePage.vue' ),
                },
            ],
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
                    path: '/invitation/accept/:invitation_code',
                    name: 'view-invitation',
                    component: () => import( '../views/auth/Invitation.vue' ),
                    meta: { allowGuest: true },
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
                {
                    path: '/forgot-password',
                    name: 'forgot-password',
                    component: () => import( '../views/auth/ForgotPassword.vue' ),
                    meta: { allowGuest: true }
                },
                {
                    path: '/reset-password/:reset_code',
                    name: 'reset-password',
                    component: () => import( '../views/auth/ResetPassword.vue' ),
                    meta: { allowGuest: true }
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
