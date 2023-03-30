import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { auth } from '@/middlewares/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue')
    },
    {
      path: '/event/:id',
      name: 'event',
      component: () => import('../views/EventView.vue')
    },
    {
      path: '/event/new',
      name: 'newEvent',
      component: () => import('../views/CreateEventView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/user/:id',
      name: 'user',
      component: () => import('../views/UserView.vue')
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    //defaut route 
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('../views/NotFoundView.vue')
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    auth(to, from, next);
  } else {
    next();
  }
});

export default router
