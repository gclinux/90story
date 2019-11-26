/*
 * 移动端模拟导航可点击自动滑动 0.1.4
 * Date: 2017-01-11
 * by: xiewei
 * 导航可左右滑动，可点击边缘的一个，自动滚动下一个到可视范围【依赖于iscroll.js】
 */
try{
    var myScroll = new IScroll('#wrapper1', {
        scrollX: true,
        scrollY: false,
        mouseWheel: true, click: true, tap: true,
        keyBindings: true,
        //加入以下三个可解决
        //disablePointer:true,
        disableTouch:false,
        disableMouse:true,
    });
}catch(e){}
var myScroll2 = new IScroll('#wrapper2', {
    scrollX: true,
    scrollY: false,
    mouseWheel: true, click: true, tap: true,
    keyBindings: true,
    //加入以下三个可解决
    //disablePointer:true,
    disableTouch:false,
    disableMouse:true,
});