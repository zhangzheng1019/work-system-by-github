<template>
    <div class="app">
        <router-view :userInfo='userInfo'>
        </router-view>
    </div>
</template>
<script>

import { fetch } from './utils'
export default {
  name:'app',
  data () {
    return {
      userInfo:'',
    }
  },
  created() {
    
   }
}
</script>
<style>
</style>
