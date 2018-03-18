<template>
    <div class="sidebar">
        <el-menu :default-active="onRoutes" class="el-menu-vertical-demo" unique-opened router background-color="#324157" text-color="#bfcbd9" active-text-color="#20a0ff">
            <template v-for="item in levelBar">
                <template v-if="item.subs">
                    <el-submenu :index="item.index">
                        <template slot="title">
                            <i :class="item.icon">
                            </i>
                            {{ item.title }}
                        </template>
                        <el-menu-item v-for="(subItem,i) in item.subs" :key="i" :index="subItem.index">
                            {{ subItem.title }}
                        </el-menu-item>
                    </el-submenu>
                </template>
                <template v-else>
                    <el-menu-item :index="item.index">
                        <i :class="item.icon">
                        </i>
                        {{ item.title }}
                    </el-menu-item>
                </template>
            </template>
        </el-menu>
    </div>
</template>
<script>
import { fetch } from '../../../utils'
export default {
  data () {
    return {
      active: [],
    }
  },
  props:['levelBar'],
  watch: {

  },
  created () {

  },
  methods: {

  },
  computed:{
      onRoutes(){
          return this.$route.path.replace('/','');
      }
  }
}
</script>
<style scoped>
  .sidebar{display: block;position: absolute;width: 250px;left: 0;top: 70px;bottom:0;background: #2E363F; } 
  .sidebar > ul { height:100%; }
</style>
