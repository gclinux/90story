const fs = require('fs');
const path = require('path');
const rp = require('request-promise-native');
const request = require('request');
const mkdirp = require('mkdirp-promise');
delete process.env['http_proxy'];
delete process.env['HTTP_PROXY'];
delete process.env['https_proxy'];
delete process.env['HTTPS_PROXY'];
const ipList=[[16777473,16778241],[16779265,16781313],[16785409,16793601],[16842753,16843009],[16843265,16859137],[16908289,16909057],[16909313,16941057],[16973825,17039361],[17039617,17072129],[17301505,17367041],[17432577,17435137],[17435393,17465345],[17563649,17825793],[18350081,18874369],[19726337,19791873],[19922945,20185089],[20447233,20971521],[21233665,21495809],[22020097,23068673],[24379393,24641537],[28573697,28966913],[29097985,29884417],[30015489,30408705],[88021249,88021505],[88022017,88022273],[234881025,234883073],[234884097,234885121],[234946561,234947585],[235929601,236978177],[241598465,241599489],[241605633,241606657],[241631233,243269633],[243400705,243531777],[243662849,243793921],[244318209,245366785],[247479297,247480321],[247483393,247484417],[247726081,247857153],[248250369,248381441],[248512513,249561089],[251672321,251672577],[251678209,251678465],[251899137,251899393],[251937537,251937793],[251953153,251953409],[252121089,252123137],[252435713,252435969],[252473857,252474113],[252907009,252907265],[252943105,252943361],[252969729,252969985],[253049345,253049601],[253394433,253394689],[253640449,253640705],[253644801,253645057],[253645313,253645825],[253646081,253646337],[253646593,253646849],[253648897,253649409],[253650945,253651713],[253652993,253655041],[253700097,253700353],[254043649,254043905],[254149121,254149377],[254155265,254155521],[254328321,254328577],[254350849,254351105],[254408961,254409473],[254424065,254424321],[254456577,254456833],[254527489,254527745],[254580225,254580481],[254591233,254591489],[254819329,254819585],[255014913,255015169],[255104001,255104257],[255219713,255219969],[255675393,255675649],[255895553,255895809],[255990785,255991041],[256270337,256272385],[256276481,256276737],[256278529,256283649],[256368641,256368897],[256370689,256372225],[256635393,256635649],[256772865,256773121],[256817665,256817921],[256837633,256838657],[256839681,256839937],[256841985,256842497],[256845825,256846081],[256846849,256847105],[256848129,256849153],[256850433,256850689],[256853249,256853505],[256858113,256860161],[256873217,256873473],[256953089,256953345],[257253377,257255425],[257263617,257263873],[257265665,257266689],[257336065,257336321],[257561089,257561345],[257616641,257616897],[257842433,257842689],[257847297,257855489],[257979649,257979905],[257984513,257985793],[257986049,257986305],[257992705,257993473],[258051329,258051585],[258060289,258060545],[258122241,258122497],[258166017,258166273],[258277121,258277377]]
const uaList = [
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
	'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)',
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',
	'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 TaoBrowser/2.0 Safari/536.11',
	'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; QQBrowser/7.0.3698.400)',
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 UBrowser/4.0.3214.0 Safari/537.36',
	'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)',
	'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0);',
	// Opera
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 OPR/26.0.1656.60',
	'Opera/8.0 (Windows NT 5.1; U; en)',
	'Mozilla/5.0 (Windows NT 5.1; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.50',
	'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.50',
	// Firefox
	'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0',
	'Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.10) Gecko/20100922 Ubuntu/10.10 (maverick) Firefox/3.6.10',

	// Safari
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
	// chrome
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',
	'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
	'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.133 Safari/534.16',

	// 360
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',
	'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',

	// 淘宝浏览器
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 TaoBrowser/2.0 Safari/536.11',
	// 猎豹浏览器
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.71 Safari/537.1 LBBROWSER',
	'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER)',
	'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E; LBBROWSER)',

	// QQ浏览器
	'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; QQBrowser/7.0.3698.400)',
	'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)',

	// sogou浏览器
	'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 SE 2.X MetaSr 1.0',
	'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)',

	// maxthon浏览器
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.4000 Chrome/30.0.1599.101 Safari/537.36',

	// UC浏览器
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 UBrowser/4.0.3214.0 Safari/537.36',

	/* 其他朋友推荐的ua start */
	'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.12) Gecko/20070731 Ubuntu/dapper-security Firefox/1.5.0.12',
	'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Acoo Browser; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)',
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11',
	'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.20 (KHTML, like Gecko) Chrome/19.0.1036.7 Safari/535.20',
	'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko Fedora/1.9.0.8-1.fc10 Kazehakase/0.5.6',
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.71 Safari/537.1 LBBROWSER',
];
const uaListPhone = [
	'Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; BLA-AL00 Build/HUAWEIBLA-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 MQQBrowser/8.9 Mobile Safari/537.36',
	'Mozilla/5.0 (Linux; Android 8.1; PAR-AL00 Build/HUAWEIPAR-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 MQQBrowser/6.2 TBS/044304 Mobile Safari/537.36 MicroMessenger/6.7.3.1360(0x26070333) NetType/WIFI Language/zh_CN Process/tools',
	'Mozilla/5.0 (Linux; Android 8.1.0; ALP-AL00 Build/HUAWEIALP-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/63.0.3239.83 Mobile Safari/537.36 T7/10.13 baiduboxapp/10.13.0.11 (Baidu; P1 8.1.0)',
	'Mozilla/5.0 (Linux; U; Android 8.0.0; zh-CN; MHA-AL00 Build/HUAWEIMHA-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.6.4.950 UWS/2.11.1.50 Mobile Safari/537.36 AliApp(DingTalk/4.5.8) com.alibaba.android.rimet/10380049 Channel/227200 language/zh-CN',
	'Mozilla/5.0 (Linux; U; Android 8.1.0; zh-CN; EML-AL00 Build/HUAWEIEML-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.108 UCBrowser/11.9.4.974 UWS/2.13.1.48 Mobile Safari/537.36 AliApp(DingTalk/4.5.11) com.alibaba.android.rimet/10487439 Channel/227200 language/zh-CN',
	'ozilla/5.0 (Linux; Android 5.1.1; vivo X6S A Build/LMY47V; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 MQQBrowser/6.2 TBS/044207 Mobile Safari/537.36 MicroMessenger/6.7.3.1340(0x26070332) NetType/4G Language/zh_CN Process/tools',
	'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/72.0.3626.101 Mobile/15E148 Safari/605.1',
	'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 MQQBrowser/9.0.3 Mobile/16D57 Safari/604.1 MttCustomUA/2 QBWebViewType/1 WKType/1',
	'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/16D57',
	'User-Agent: MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1 Android Opera Mobile'
]

//创建一个中国范围内假的IP
function createIp(){
	var ipRange = ipList[Math.floor(Math.random()*ipList.length)];
	return int2ip(getRandom(ipRange[0],ipRange[1]));
}
function createUa(is_phone){
	if(is_phone){
		return uaListPhone[Math.floor(Math.random()*uaListPhone.length)]
	}else{
		return uaList[Math.floor(Math.random()*uaList.length)]
	}
}
function getRandom(istart, iend) {
	    var iChoice = iend - istart + 1;    //加1是为了取到100
	    var res = Math.floor(Math.random() * iChoice + istart);  //[0,90]+10
	    return res;
}
function int2ip(num) {
    var str;
    var tt = [];
    tt[0] = (num >>> 24) >>> 0;
    tt[1] = ((num << 8) >>> 24) >>> 0;
    tt[2] = (num << 16) >>> 24;
    tt[3] = (num << 24) >>> 24;
    str = String(tt[0]) + "." + String(tt[1]) + "." + String(tt[2]) + "." + String(tt[3]);
    return str;
};
let errors = [];
let pushError=function(e){
	if(errors.length>1000){
		errors.shift();
	}
	errors.push(e);
}
let  getError = function(){
	return error;
}

//伪造IP
let get = function(link,proxy,timeout,referer,cookies,need_create_ip,is_phone) {
	
	let ip=false;
	
	if(need_create_ip){
		ip = createIp();
	}
	let ua=createUa(is_phone);
	let options = {
		url: link,
		headers: {
		  'User-Agent': ua,
		},
		encoding: null,//防止编码问题
		rejectUnauthorized: false,
		strictSSL:false
	};
	if(ip){
		options.headers['X-Forwarded-For']=ip
	}
	if(proxy){
		options.proxy= proxy;
	}
	if(timeout){
		options.timeout = timeout;
	}
	if(referer){
		options.headers.Referer=referer
	}
	if(cookies){
		options.headers.cookies=cookies
	}

	return new Promise((ac,rj)=>{
		rp(options).then(function (htmlString) {
			ac(htmlString);
		}).catch(function (err) {
			pushError(err);
			ac(null);
		});
	})

}

let checkProxy=function(proxyData){
    return new Promise((ac,rj)=>{
        //console.log(data.type+'://'+data.ip+':'+data.port);
        get('https://www.baidu.com',proxyData.type+'://'+proxyData.ip+':'+proxyData.port,20000).then(baidu_rs=>{
            if(baidu_rs){
                ac(proxyData);
            }else{
                ac(null);
            }
        }).catch((e)=>{
            ac(null);
        });
    })
     
}

let pieDown = function(link,local,proxy,timeout,referer,cookies,need_create_ip){
	return new Promise(async (ac,rj)=>{
		let ip=false;
		
		if(need_create_ip){
			ip = createIp();
		}
		let ua=createUa();
		let options = {
			url: link,
			headers: {
			'User-Agent': ua,
			},
			encoding: null,//防止编码问题
			rejectUnauthorized: false,
			strictSSL:false
		};
		if(ip){
			options.headers['X-Forwarded-For']=ip
		}
		if(proxy){
			options.proxy= proxy;
		}
		if(timeout){
			options.timeout = timeout;
		}
		if(referer){
			options.headers.Referer=referer
		}
		if(cookies){
			options.headers.cookies=cookies
		}
		try{
			await mkdirp(path.dirname(local));
		}catch(e){
			console.log(e);
			ac( {'success':0,data:e});
		}
		let writeStream = fs.createWriteStream(local);
		let readStream = request(options)
		readStream.pipe(writeStream);
		readStream.on('end', function() {
			
		});
		readStream.on('error', function(err) {
			ac( {'success':0,data:err});
		})
		writeStream.on("finish", function() {
			writeStream.end();
			ac( {'success':1,data:local});
		});
	});
}

module.exports.rp=rp;
module.exports.get = get;
module.exports.getError= getError;
module.exports.checkProxy = checkProxy;
module.exports.pieDown = pieDown;