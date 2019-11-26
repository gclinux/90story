const redis = require('redis');
const {promisify} = require('util');
const config = require('../config/index.js');
let connected=false;
let client;
async function connect(){
	config.redis.retry_strategy= function (options) {
        if (options.error.code === 'ECONNREFUSED') {
            // End reconnecting on a specific error and flush all commands with a individual error 
            console.log('连接被拒绝');
        }
        if (options.times_connected > 10) {
            console.log('重试连接超过十次');        
        }
        // reconnect after 
        return Math.max(options.attempt * 100, 3000);
    }
    client=redis.createClient(config.redis);
    connected=true;
	
	client.on("error", function (err) {
	   console.log(err);
	});
 	
}
if(!connected){
	connect();
}



module.exports.get = promisify(client.get).bind(client);
module.exports.set = promisify(client.set).bind(client);
module.exports.lpush = promisify(client.lpush).bind(client);
module.exports.rpush = promisify(client.rpush).bind(client);
module.exports.blpop = promisify(client.blpop).bind(client);
module.exports.rpop = promisify(client.rpop).bind(client);
module.exports.client = client;
module.exports.connect=connect;