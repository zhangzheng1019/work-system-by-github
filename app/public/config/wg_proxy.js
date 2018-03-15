const DEVHOST = 'http://dev.wg.com/';
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
    }
}