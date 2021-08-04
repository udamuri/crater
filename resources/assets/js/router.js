import Vue from 'vue'
import VueRouter from 'vue-router'

/*
 |--------------------------------------------------------------------------
 | Views
 |--------------------------------------------------------------------------|
 */

// Layouts
import LayoutBasic from './views/layouts/LayoutBasic.vue'
import LayoutLogin from './views/layouts/LayoutLogin.vue'
import LayoutWizard from './views/layouts/LayoutWizard.vue'

// Auth
import Login from './views/auth/Login.vue'
import ForgotPassword from './views/auth/ForgotPassword.vue'
import ResetPassword from './views/auth/ResetPassword.vue'
import Register from './views/auth/Register.vue'

import NotFoundPage from './views/errors/404.vue'

// Dashbord
import Dashboard from './views/dashboard/Dashboard.vue'


//User
import UserIndex from './views/users/Index.vue'
import UserCreate from './views/users/Create.vue'

// Customers
import CustomerIndex from './views/customers/Index.vue'
import CustomerCreate from './views/customers/Create.vue'

// Settings
import SettingsLayout from './views/settings/SettingsIndex.vue'
import CompanyInfo from './views/settings/CompanyInfoSetting.vue'
import Preferences from './views/settings/PreferencesSetting.vue'
import UserProfile from './views/settings/UserProfileSetting.vue'
import MailConfig from './views/settings/MailConfigSetting.vue'
import Backup from './views/settings/BackupSetting.vue'
import FileDisk from './views/settings/FileDiskSetting.vue'
import Wizard from './views/wizard/Wizard.vue'

Vue.use(VueRouter)

const routes = [
  /*
   |--------------------------------------------------------------------------
   | Auth & Registration
   |--------------------------------------------------------------------------|
   */

  {
    path: '/',
    component: LayoutLogin,
    meta: { redirectIfAuthenticated: true },
    children: [
      {
        path: '/',
        component: Login,
      },
      {
        path: 'login',
        component: Login,
        name: 'login',
      },
      {
        path: '/forgot-password',
        component: ForgotPassword,
        name: 'forgot-password',
      },
      {
        path: '/reset-password/:token',
        component: ResetPassword,
        name: 'reset-password',
      },
      {
        path: 'register',
        component: Register,
        name: 'register',
      },
    ],
  },

  /*
   |--------------------------------------------------------------------------
   | Onboarding
   |--------------------------------------------------------------------------|
   */
  {
    path: '/on-boarding',
    component: LayoutWizard,
    children: [
      {
        path: '/',
        component: Wizard,
        name: 'wizard',
      },
    ],
  },

  /*
   |--------------------------------------------------------------------------
   | Admin
   |--------------------------------------------------------------------------|
   */
  {
    path: '/admin',
    component: LayoutBasic,
    meta: { requiresAuth: true },
    children: [
      // Dashboard
      {
        path: '/',
        component: Dashboard,
        name: 'dashboard',
      },
      {
        path: 'dashboard',
        component: Dashboard,
      },

      // Customers
      {
        path: 'customers',
        component: CustomerIndex,
      },
      {
        path: 'customers/create',
        name: 'customers.create',
        component: CustomerCreate,
      },
      {
        path: 'customers/:id/edit',
        name: 'customers.edit',
        component: CustomerCreate,
      },

      // User
      {
        path: 'users',
        component: UserIndex,
      },
      {
        path: 'users/create',
        name: 'users.create',
        component: UserCreate,
      },
      {
        path: 'users/:id/edit',
        name: 'users.edit',
        component: UserCreate,
      },

      // Settings
      {
        path: 'settings',
        component: SettingsLayout,
        children: [
          {
            path: 'company-info',
            name: 'company.info',
            component: CompanyInfo,
          },

          {
            path: 'user-profile',
            name: 'user.profile',
            component: UserProfile,
          },
          {
            path: 'preferences',
            name: 'preferences',
            component: Preferences,
          },

          {
            path: 'mail-configuration',
            name: 'mailconfig',
            component: MailConfig,
          },
          {
            path: 'backup',
            name: 'backup',
            component: Backup,
          },
          {
            path: 'file-disk',
            name: 'file-disk',
            component: FileDisk,
          },
        ],
      },
    ],
  },

  //  DEFAULT ROUTE
  { path: '*', component: NotFoundPage },
]

const router = new VueRouter({
  routes,
  mode: 'history',
  linkActiveClass: 'active',
})

export default router
