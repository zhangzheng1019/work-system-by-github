<template>
    <div id="stuSelect">
			学生单
    </div>
</template>
<script>
  export default {
		data () {
			return {
			  urlParams: {},
			}
		},
		components: {

    },
    mounted() {
    	this.getUrlParams()
    	this.doRedirect();
    },
		create() {

		},
		methods: {
			githubLogin() {
				// fetch({
				// 	url: "/student/loginGit",
				// 	dataType:'json',
				// 	cb: (data,msg)=>{
				// 		console.log(data,msg)
				// 	}
				// })
				// this.getUrlParams()
				// this.doRedirect()	
				// console.log(this.urlParams)
			},
			appendParams(url, params) {
          if (params) {
              var baseWithSearch = url.split('#')[0];
              var hash = url.split('#')[1];
              for (var key in params) {
                  var attrValue = params[key];
                  if (attrValue !== undefined) {
                      var newParam = key + "=" + attrValue;
                      if (baseWithSearch.indexOf('?') > 0) {
                          var oldParamReg = new RegExp('^' + key + '=[-%.!~*\'\(\)\\w]*', 'g');
                          if (oldParamReg.test(baseWithSearch)) {
                              baseWithSearch = baseWithSearch.replace(oldParamReg, newParam);
                          } else {
                              baseWithSearch += "&" + newParam;
                          }
                      } else {
                          baseWithSearch += "?" + newParam;
                      }
                  }
              }

              if (hash) {
                  url = baseWithSearch + '#' + hash;
              } else {
                  url = baseWithSearch;
              }
          }
          return url;
      },
      getUrlParams() {
          var pairs = location.search.substring(1).split('&');
          for (var i = 0; i < pairs.length; i++) {
              var pos = pairs[i].indexOf('=');
              if (pos === -1) {
                  continue;
              }
              this.urlParams[pairs[i].substring(0, pos)] = decodeURIComponent(pairs[i].substring(pos + 1));
          }
          console.log(this.urlParams)
      },
      doRedirect() {
					var code = this.urlParams['code'];
          var appId = this.urlParams['client_id'];
          var scope = this.urlParams['scope'];
          var state = this.urlParams['state'];
          var allow_signup = this.urlParams['allow_signup'];
          var redirectUri;

          if (!code) {
              //第一步，没有拿到code，跳转至授权页面获取code
              redirectUri = this.appendParams('https://github.com/login/oauth/authorize', {
                  'client_id': appId,
                  'redirect_uri': encodeURIComponent(location.href),
                  'scope': scope,
                  'state': state,
                  'allow_signup': allow_signup,
              });
          } else {
              //第二步，从授权页面跳转回来，已经获取到了code，再次跳转到实际所需页面
              redirectUri = this.appendParams(this.urlParams['redirect_uri'], {
                  'code': code,
                  'state': state
              });
          }
          console.log(redirectUri)
          location.href = redirectUri;
      }
		}

	}
</script>
<style scoped>
    #stuSelect{ position: fixed;left: 0; top: 0;right: 0; bottom: 0;background: #fff; }
</style>
