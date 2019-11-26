/**所有与mysql相关的操作封装在这里,如果更换数据库种类,请修改这个文件 */
const mysql = require('mysql2');
const config = require('../config/index').mysql;
config.table_pre = config.table_pre||'t_';
const pool = mysql.createPool(config.connect);
const promisePool = pool.promise();
const fun=require('./functions');
const book_table = config.table_pre+'books';
const catalog_table = config.table_pre+'book_catalogs';
const content_table = config.table_pre+'book_contents';
const source_table = config.table_pre+'book_sources'

async function storeBook(book){
    let sql = "INSERT IGNORE INTO `"+book_table+'` (`name`,`author`,`des`,`url`,`cat_url`,`file`,`img`,`spider_status`,`status`,`class`,`hot`,`created_at`,`updated_at`,`img_down`) '+
    'VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
    let now = fun.now();
    let value=[
        book.name,
        book.author||'佚名',
        book.des||'',
        book.url,
        book.cat_url||'',
        book.file,
        book.img||'',
        book.spider_status||0,
        book.status||0,
        book.class||'未知',
        book.hot||0,
        now,
        now,
        0
    ];
    let rows,fields;
    let old_book = await isBookExists(book.name,book.author);
    if(old_book){
        return {success:false,data:'book_exists'};
    }
    try{
        [rows,fields]= await promisePool.execute(sql,value);
    }catch(e){
        return {success:false,data:e.message};
    }
    return {success:true,data:rows.insertId};
    
}

async function isBookExists(book_name,book_author){
    let sql = 'SELECT id FROM `'+book_table+ '` WHERE (`name`=? AND `author`=?) LIMIT 1';
    let [rows,fields]= await promisePool.query(sql,[book_name,book_author]);
    if(!rows){
        return null;
    }else{
        return rows[0];
    }
}
async function storeCatalogs(cats,book_id){
    let now = fun.now();
    let sql = 'INSERT IGNORE INTO '+catalog_table+
    '(`book_id`,`name`,inx,`num`,`spider_status`,`type`,`url`,`open_type`,`file`,`created_at`,`updated_at`) VALUES ?';
    let values=[];
    for(let i in cats){
        let cat = cats[i];
       // console.log(cat);
        values.push([book_id,cat.name,cat.inx,cat.num,cat.spider_status||0,cat.type||0,cat.url,cat.open_type||0,cat.file,now,now]);
    }
    if(values.length==0){
        return {"success":0,"data":'no catalogs'}
    }
    try{
        let [rows,fields] = await promisePool.query(sql,[values]);
        if(rows.affectedRows >0){
            updateBook({"id":book_id,"updated_at":now});//有章节更新,把更新时间修改
        }
        return {"success":1,"data":rows.affectedRows};
    }catch(e){
        return {"success":0,"data":e.message};
    }

}
async function storeContents(content,cat_id){
    now = fun.now();
    let sql = 'INSERT IGNORE INTO '+content_table+'(content,catalog_id,created_at,updated_at)VALUES(?,?,?,?)';
    
    try{
        let [rows,fields] = await promisePool.query(sql,[content,cat_id,now,now]);
        updateCat({"id":cat_id,"spider_status":1,"updated_at":now})
        return {"success":1,"data":rows.insertId};
    }catch(e){
        return {"success":0,"data":e.message};
    }
    
}
async function getCatalogsByStatus(status,limit,now){
    status=status||0;
    limit = parseInt(limit)||5000;
    max_retry = 50;
    let sql = 'SELECT * FROM '+catalog_table+' WHERE spider_status=? AND retry<? LIMIT ?';
    try{
        let [rows,fields] = await promisePool.query(sql,[status,max_retry,limit]);
        return {"success":1,"data":rows};
    }catch(e){
        return {"success":0,"data":e.message};
    }
}

async function getBooksByStatus(status,limit,status_type,page){
    status=status||0;
    limit = parseInt(limit)||5000;
    page = page?page:1;
    start = limit*(page-1);
    let sql = 'SELECT * FROM '+book_table+' WHERE spider_status=? LIMIT ?,?';
    if(status_type == 'update'){
        sql = 'SELECT * FROM '+book_table+' WHERE status=? LIMIT ?,?';
    }
    try{
        let [rows,fields] = await promisePool.query(sql,[status,start,limit]);
        return {"success":1,"data":rows};
    }catch(e){
        return {"success":0,"data":e.message};
    }
}
async function isBookUrlExist(url){
    let sql = 'SELECT url FROM '+book_table+' WHERE url=? LIMIT 1';
    let [rows,fields] = await promisePool.query(sql,[url]);
    if(rows.length>0){
        return true;
    }else{
        return false;
    }
}

async function getBook(book_id){
    let sql = 'SELECT * FROM '+book_table+' WHERE id=? LIMIT 1';
    try{
        let [rows,fields] = await promisePool.query(sql,[book_id]);
        return {"success":1,"data":rows};
    }catch(e){
        return {"success":0,"data":e.message};
    }

}


async function getBookImages(hasDown,limit,page){
    status=hasDown||0;
    limit = parseInt(limit)||50;
    page = page?page:1;
    start = page*(page-1);
    let sql = 'SELECT id,img,YEAR(created_at) as year FROM '+book_table+' WHERE img_down=? and img !="" LIMIT ?,?';
    try{
        let [rows,fields] = await promisePool.query(sql,[status,start,limit]);
        return {"success":1,"data":rows};
    }catch(e){
        return {"success":0,"data":e.message};
    }
}

async function updateBook(book){
    let book_id = book.id;
   // delete book.id;
    let sql='UPDATE '+book_table+' SET ? WHERE id=?';
    let [rows,fields] = await promisePool.query(sql,[book,book_id]);
    //console.log('update book info');
}
async function updateCat(cat){
    let cat_id = cat.id
    let sql = 'UPDATE '+catalog_table+' SET ? WHERE id=?'
    let [rows,fields] = await promisePool.query(sql,[cat,cat_id]);
   // console.log('update catalog info');
}
async function getSource(){
    let sql = 'SELECT * FROM '+source_table+' WHERE status=1';
     try{
        let [rows,fields] = await promisePool.query(sql);
        return {"success":1,"data":rows};
    }catch(e){
        return {"success":0,"data":e.message};
    }
}

async function catRetryAdd(cat_id){
    let sql = 'UPDATE '+catalog_table+' SET retry=retry+1 WHERE id=?'
    try{
        let [rows,fields] = await promisePool.query(sql,[cat_id]);
        return {"success":1,"data":rows};
    }catch(e){
        return {"success":0,"data":e.message};
    }
}

async function bookRetryAdd(book_id){
    let sql = 'UPDATE '+book_table+' SET retry=retry+1 WHERE id=?'
    try{
        let [rows,fields] = await promisePool.query(sql,[book_id]);
        return {"success":1,"data":rows};
    }catch(e){
        return {"success":0,"data":e.message};
    }
}


module.exports.storeBook = storeBook;
module.exports.storeCatalogs = storeCatalogs;
module.exports.updateBook = updateBook;
module.exports.getBooksByStatus = getBooksByStatus;
module.exports.storeContents=storeContents;
module.exports.getCatalogsByStatus=getCatalogsByStatus
module.exports.getBookImages = getBookImages;
module.exports.getSource = getSource;
module.exports.isBookUrlExist = isBookUrlExist;
module.exports.catRetryAdd=catRetryAdd;
module.exports.bookRetryAdd = bookRetryAdd;
module.exports.getBook = getBook;