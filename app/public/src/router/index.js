import Vue from 'vue'
import Router from 'vue-router'
import Hello from '@/components/Hello'
import Teacher from '@/components/teacher'

Vue.use(Router)

var menu=[
    {
      path: '/',
      name: 'Hello',
      component: Hello
    }, {
      path: '/teacher',
      name: 'Teacher',
      component: Teacher
    }

];

export default new Router({
  mode:'hash',
  routes:  menu
});
