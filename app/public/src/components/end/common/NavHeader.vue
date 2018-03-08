<template>
    <div class="user-menu"  v-if='userInfo'>
        <span class="nick-name">
            账号类型：{{ userInfo.role }}
        </span>
        <span class="nick-name">
            账号ID：{{ userInfo.id }}
        </span>
        <el-dropdown>
            <span class="el-dropdown-link nick-name">
                {{ userInfo.name }}
                <i class="el-icon-caret-bottom el-icon--right">
                </i>
            </span>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item>
                    <a class='logout' href="/login/logout">
                        退出
                    </a>
                </el-dropdown-item>
                <!-- <el-dropdown-item><a class='reset' href='/#/resetpwd'>修改密码</a></el-dropdown-item> -->
            </el-dropdown-menu>
        </el-dropdown>
    </div>
</template>
<script>
export default {
  props: ['userInfo']
}
</script>
<style>
.user-menu {
  float: right;
}
.reset, .logout{
  text-decoration: none;
  color: #666;
}
.nick-name {
  color: white;
  font-size: 15px;
  display: block;
  margin-right:20px;
  float: left;
  padding: 0 20px;
  line-height: 64px;
}
.el-dropdown-menu__item a{
  display: block;width: 100%;
}
a{
  text-decoration: none;
  color:#666;
}
</style>
