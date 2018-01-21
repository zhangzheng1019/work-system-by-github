const DEVHOST = 'http://dev.wg.com/';
module.exports = {
	'/teacher': {
        target: DEVHOST,
        changeOrigin: true
    }
}