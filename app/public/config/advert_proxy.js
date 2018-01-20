const DEVHOST = 'http://dev.advert.douguo.net/';
module.exports = {
	'/home': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/user': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/login': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/customer':{
         target: DEVHOST,
        changeOrigin: true
    },
    '/contract': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/execontract': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/framecontract': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/booking': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/advert': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/lib':{
        target: DEVHOST,
        changeOrigin: true
    },
    '/execonfirmbill':{
        target: DEVHOST,
        changeOrigin: true
    },
    '/list':{
         target: DEVHOST,
         changeOrigin: true
    }
}