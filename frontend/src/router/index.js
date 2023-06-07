import { createRouter, createWebHistory } from 'vue-router'
import TodoListView from '../views/TodoListView.vue'
import OverviewView from '../views/OverviewView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: TodoListView
    },
    {
      path: '/overview',
      name: 'overview',
      component: OverviewView
    },
  ]
})

export default router
