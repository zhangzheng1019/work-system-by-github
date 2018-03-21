const $ = require('jquery')
import {
    Message
} from 'element-ui'
// 自定义post请求
export function fetch(options) {
    if (typeof options.dataType === 'undefined') {
        options.dataType = 'json'
    }
    options.type = 'GET'
    if (typeof options.success !== 'function' && (typeof options.cb === 'function' || typeof options.err === 'function')) {
        options.success = function(msg) {
            if (msg.errcode == 0) {
                options.cb(msg.data, msg.msg)
            } else if (msg.errcode == 10000) {
                window.location = '/#/end/login'
            } else if (msg.errcode == 20000) {
                window.location = '/#/'
            } else {
                options.err(msg.data, msg.msg)
            }
        }
    }
    $.ajax(options)
}
// 自定义post请求
export function post(options) {
    if (typeof options.dataType === 'undefined') {
        options.dataType = 'json'
    }
    options.type = 'POST'
    if (typeof options.success !== 'function' && (typeof options.cb === 'function' || typeof options.err === 'function')) {
        options.success = function(msg) {
            if (msg.errcode == 0) {
                options.cb(msg.data, msg.msg)
            } else if (msg.errcode == 10000) {
                window.location = '/#/end/login'
            } else if (msg.errcode == 20000) {
                window.location = '/#/'
            } else {
                options.err(msg.data, msg.msg)
            }
        }
    }
    $.ajax(options)
}
// 计算footer高度
export function countFootPos() {
    setTimeout(() => {
        let clientHeight = document.body.clientHeight
        let $footer = $(".el-footer")
        let docH = $("#indexHead").get(0).offsetHeight + $(".el-main").get(0).offsetHeight;
        if (docH < clientHeight) {
            $footer.css({
                'position': 'fixed',
                'bottom': 0,
                'left': 0,
                'width': '100%'
            })
        }
    }, 200)
}