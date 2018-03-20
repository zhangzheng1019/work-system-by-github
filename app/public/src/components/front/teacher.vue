<template>
    <div id="teacher">
        <index-head :userInfo='userInfo'></index-head>
    </div>
</template>
<script>
    import indexHead  from './comment/indexHead'

    export default {
    	name: 'teacher',
		data () {
			return {
			  
			}
		},
		props:['userInfo'],
		components: {
			'index-head' : indexHead
    },
		create() {

		},
		methods: {
			
		}

	}
</script>
<style scoped>
    
</style>
