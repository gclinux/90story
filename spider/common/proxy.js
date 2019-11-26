const redis = require('./redis');
const redis_key = 'joffe_proxy_list'
function popIp(){
	return new Promise(async (ac,rj)=>{
		let data;
		try{
			data = await redis.rpop(redis_key);
		}catch(e){
			return rj(e);
		}
		try{
			ac(JSON.parse(data));
		}catch(e){
			rj(e);
		}
	})
}

function storeIp(value){
	value = JSON.stringify(value);
	return redis.lpush(redis_key,value);
}
module.exports.popIp = popIp;
module.exports.storeIp = storeIp;