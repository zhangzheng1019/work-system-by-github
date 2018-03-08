import Vue from 'vue'
import Router from 'vue-router'
import End from '@/components/End'
import endHello from '@/components/end/Hello'
import endTeacher from '@/components/end/teacher'
import endStudent from '@/components/end/student'
import Front from '@/components/Front'
import Hello from '@/components/front/hello'
import Teacher from '@/components/front/teacher'
import Student from '@/components/front/student'
Vue.use(Router)
var menu = [{
    path: '/end',
    component: End,
    children: [{
        path: '/end/',
        component: endHello
    }, {
        path: '/end/teacher',
        component: endTeacher
    }, {
        path: '/end/student',
        component: endStudent
    }, ]
}, {
    path: '/',
    component: Front,
    children: [{
        path: '/',
        component: Hello
    }, {
        path: '/teacher',
        component: Teacher
    }, {
        path: '/student',
        component: Student
    }]
}];
export default new Router({
    mode: 'hash',
    routes: menu
});