import {Message} from 'element-ui'

var check_email = (rule, value, callback) => {	
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (rule.required === true) {
		if(!value){
			callback(new Error('请输入邮箱地址'));
		}else{		
		    setTimeout(() => {        	
		      if (!filter.test(value)) {
		        callback(new Error('请输入正确的邮箱地址'));
		      } else {
		          callback();
		      }
		    }, 200);
		}	
	} else {
		if(value){			
		    setTimeout(() => {        	
		      if (!filter.test(value)) {
		        callback(new Error('请输入正确的邮箱地址'));
		      } else {
		          callback();
		      }
		    }, 200);
		}else{
			callback();
		}	
	}
  };
function check_emails (value) {
    let text = false;
    let filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;	
	if(!value){
		Message.error('请输入邮箱地址')
	}else{  	
      if (!filter.test(value)) {
         Message.error('请输入正确的邮箱地址');
      } else {
          text = true;
      }    
	}
	return text;	
  };
var check_phone = (rule, value, callback) => {
	var filter  = /^1[34578][0-9]{9}$/;
	if (rule.required === true) {
		if(!value){
			callback(new Error('请输入手机号码'));
		}		
		setTimeout(() => {
		  if (!filter.test(value)) {
		    callback(new Error('请输入正确的手机号码'));
		  } else {
		      callback();
		  }
		}, 200);
	}else{
		if (value) {
			setTimeout(() => {
			  if (!filter.test(value)) {
			    callback(new Error('请输入正确的手机号码'));
			  } else {
			      callback();
			  }
			}, 200);
		}else{
			callback();
		}	
	}
};

function check_phones (value) {
    let text = false;
    let filter  = /^1[34578][0-9]{9}$/;	
	if(!value){
		Message.error('请输入手机号码')
	}else{  	
      if (!filter.test(value)) {
         Message.error('请输入正确的手机号码');
      } else {
          text = true;
      }    
	}
	return text;	
  };

var check_telephone = (rule, value, callback) => {
	if(!value){
		callback(new Error('请输入座机号码'));
	}
	var filter  = /^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/;
	setTimeout(() => {
	  if (!filter.test(value)) {
	    callback(new Error('请输入正确的座机号码,eg:010-45634563'));
	  } else {
	      callback();
	  }
	}, 200);
};

function check_telephones (value) {
    let text = false;
    let filter  = /^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/;	
	if(!value){
		Message.error('请输入座机号码')
	}else{  	
      if (!filter.test(value)) {
         Message.error('请输入正确的座机号码');
      } else {
          text = true;
      }    
	}
	return text;	
  };

 function formattime(time){
	var s = new Date(time);
	var M=(s.getMonth() + 1)<10?'0'+(s.getMonth()+1):(s.getMonth()+1);
	var D=s.getDate()<10?'0'+s.getDate():s.getDate();
	var time = s.getFullYear()+'-'+M +'-' + D+' '+'00' +':'+'00'+ ':'+'00';
	return time;
};
 function format(time){
 	if(time!="0000-00-00 00:00:00")
 	{
 		var s = new Date(time);
		var M=(s.getMonth() + 1)<10?'0'+(s.getMonth()+1):(s.getMonth()+1);
		var D=s.getDate()<10?'0'+s.getDate():s.getDate();
		var time = s.getFullYear()+'-'+M +'-' + D;	
 	}else{
 		time = '0000-00-00';
 	}
	
	return time;
};

function removeArrVal(arr, val) {
	let arlen = arr.length;
	  for(let i=0; i<arlen; i++) {
	    if(arr[i] == val) {
	      arr.splice(i,1);
	      break;
	    }
	  }
};

function inArray(val,arr) {
	let arlen = arr.length;	
	let test = '';		
    for (let s = 0; s < arlen; s++) {
        test = arr[s].toString();
        if (test == val) {
            return true;
        }
    }
    return false;
};

function deepCopyObj(obj) { 

	let str, result = obj.constructor === Array ? [] : {};
    if(typeof obj !== 'object'){
        return;
    } else if(window.JSON){
        str = JSON.stringify(obj), 
        result = JSON.parse(str); 
    } else {
        for(let i in obj){
            result[i] = typeof obj[i] === 'object' ? deepCopyObj(obj[i]) : obj[i]; 
        }
    }

    return result; 
};
export {check_email,check_phone,check_telephone,check_emails,check_telephones,check_phones,formattime,format,removeArrVal,inArray,deepCopyObj}  