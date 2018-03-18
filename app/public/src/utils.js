const $ = require('jquery')
import {Message} from 'element-ui'

export function fetch (options) {
	if (typeof options.dataType === 'undefined') {
		options.dataType = 'json'
	}
	options.type = 'GET'
	if (typeof options.success !=='function' && (typeof options.cb === 'function' || typeof options.err === 'function')) {
		options.success = function(msg) {
			if (msg.errcode == 0) {
				options.cb(msg.data,msg.msg)	
			}else if (msg.errcode == 10000) {
				window.location = '/#/end/login'
			}else{
				options.err(msg.data,msg.msg)
			}			
		}
	}
	$.ajax(options)
}

export function post (options) {
	if (typeof options.dataType === 'undefined') {
		options.dataType = 'json'
	}
	options.type = 'POST'
	if (typeof options.success !=='function' && (typeof options.cb === 'function' || typeof options.err === 'function')) {
		options.success = function(msg) {
			if (msg.errcode == 0) {
				options.cb(msg.data,msg.msg)	
			}else if (msg.errcode == 10000) {
				window.location = '/#/end/login'
			}else{
				options.err(msg.data,msg.msg)
			}	
		}
	}
	$.ajax(options)
}