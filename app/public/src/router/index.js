import Vue from 'vue'
import Router from 'vue-router'
import Hello from '@/components/Hello'
import Teacher from '@/components/teacher'
// import FrameContractlist from '@/components/contract/FrameContractlist'
// import ExContractlist from '@/components/contract/ExContractlist'
// import Mycustomer from '@/components/customer/mycustomer'
// import Customerlist from '@/components/customer/customerlist'
// import Customervisit from '@/components/customer/visitlist'
// import Positionlist from '@/components/position/positionlist'
// import permission from '@/components/permission/permission'
// import Register from '@/components/permission/permissionchild/register'
// import Lib from '@/components/lib/liblist'
// import Adv from '@/components/adv/advlist'
// import Libinfo from '@/components/lib/libchild/infolib'
// import ConfirmBill from '@/components/confirmbill/confirm'
// import MyConfirmBill from '@/components/confirmbill/myconfirm'
// import HelpUser from '@/components/helpuser/pmuser'
// import Helplib from '@/components/helpuser/pmadvlib'
// import Helplist from '@/components/helpuser/pmadvlist'
// import HelpCustomer from '@/components/helpuser/pmcustomer'
// import HelpFinancial from '@/components/helpuser/pmfinancial'
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
