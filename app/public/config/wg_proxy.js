const DEVHOST = 'http://47.106.75.132/';
module.exports = {
    '/course': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/email': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/student': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/teacher': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/login': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/upload': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/main': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/task': {
        target: DEVHOST,
        changeOrigin: true
    },
    '/github': {
        target: DEVHOST,
        changeOrigin: true
    }
}
