<template>
    <div id="personinfo">
    	<el-col :span="5">
          <div class="grid-content">
            <img :src="userInfo.thumb" alt="" class="person-pic"/>
            <p class="person-pub person-name oneline"><i class="el-icon-news">&nbsp;</i>{{ userInfo.username }}</p>
            <p class="person-pub person-desc moreline" :title="userInfo.desc"><i class="el-icon-edit-outline">&nbsp;</i>{{ userInfo.desc }}</p>
            <p class="person-pub person-email oneline"><i class="el-icon-message">&nbsp;</i>{{ userInfo.email }}</p>
          </div>
          <edit-person :userInfo="userInfo"></edit-person>
      </el-col>
    </div>
</template>

<script>
	import editPerson from './editperson'
  export default {
		data () {
			return {
			  
			}
		},
		props: ['userInfo'],
		components: {
			'edit-person' : editPerson
    },
		create() {

		},
		methods: {
			
		}

	}
</script>

<style scoped>
    /*用户*/
  .person-pub{ margin: 10px 0; width: 100%;font-size: 14px; }
  .person-pub i{ font-size: 16px; }
  .person-pic{ width: 100%;height: 100%; display: inline-block;vertical-align: middle; border-radius: 6px;border: 1px solid #cdcdcd; }
  .person-name{  }
  .person-desc{  }
  .person-email{  }
</style>