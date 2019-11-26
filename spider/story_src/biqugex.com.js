const jq = require('cheerio');
const strtotime=require('locutus/php/datetime/strtotime');
const time = require('locutus/php/datetime/time');
const file_name = 'biqugex.com.js';
const Entities = require('html-entities').XmlEntities;
const iconv = require("encoding").convert;
module.exports={
    "top":{
        "exec":function(html){
            let paichong = {};
            html = iconv(html,'utf-8','GBK');
            let $=jq.load(html);
            let list = $('.block.bd li a');
            let ret = [];
            list.each((inx,ele)=>{
                let book={};
                let uri = $(ele).prop('href');
                if(paichong[uri]==true){
                    return true;
                }
                paichong[uri]=true
                book.url = 'https://www.biqugex.com'+uri;
                book.catUrl = '';//与url相同
                book.file = file_name;
                ret.push(book);
            });
            return ret;
        },
        "isPhone":false,
        "proxy":false,
        "referer":"https://www.biqugex.com/",
        "url":"https://www.biqugex.com/paihangbang/"
    },
    "bookInfo":{
        "exec":function(html){
        	html = iconv(html,'utf-8','GBK');
            let book = {};
            let $=jq.load(html);
            book.name=$('.info h2').text();
            book.author = ($('.info .small span').eq(0).text()+'').trim().replace('作者：','');
            book.class = ($('.info .small span').eq(1).text()+'').trim().replace('分类：','');
            let status = $('.info .small span').eq(2).text()+''
            let lastupdate = ($('.info .small span').eq(4).text()+'').trim()
            lastupdate = strtotime(lastupdate.replace('更新时间：',''));
            book.img = $('.over img').eq(0).prop('src');
            book.spider_status=1
            book.char_num = parseInt(($('.info .small span').eq(3).text()+'').replace('字数：',''));
            let des = $('.intro').text()+''
		    let aPos = des.indexOf('简介：');
		    let bPos = des.indexOf('作者：');
		    book.des = des.substr(aPos + '简介：'.length, bPos - aPos - '作者：'.length).trim();
            let now = time();
            if(status.trim() != '状态：连载'){
                book.status = 1;
            }else if(now-lastupdate>86400*180){//半年没更新了,就当作完成了
                book.status = 1;
            }else{
                book.status = 0;
            }
            book.file=file_name;
            return book;
        },
        "isPhone":false,
        "proxy":false,
        "referer":"https://www.biqugex.com/paihangbang/",
    },
    "cat":{
        "exec":function(html){
            html = iconv(html,'utf-8','GBK');
            let ret = [];
            let $=jq.load(html);
            let catsDom = $('dt').eq(1).nextAll();
            catsDom.each((i,ele)=>{
                let cat = {};
                cat.num = 0;
                cat.inx=i
                cat.name=$(ele).children('a').text();
                cat.url = 'https://www.biqugex.com'+$(ele).children('a').attr('href');
                cat.type=1;
                cat.file=file_name;
                if((!cat.name)||(!cat.url)){
                    return true;
                }
                ret.push(cat);
            });
            return ret;
        },
        "isPhone":true,
        "proxy":false,
        "referer":"https://www.biqugex.com/",
    },
    "content":{
        "exec":function(html){
            html = iconv(html,'utf-8','GBK');
            let $=jq.load(html);
            let content = $('#content').html();
            if(!content){
                return null;
            }
            let entities = new Entities();
            content = entities.decode(content)
            content = content.split('(https://www.biqugex.com');
            return content[0];
        },
        "isPhone":false,
        "proxy":false,
        "referer":"https://www.biquge.tw/",
    }
}