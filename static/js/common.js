var is_manager = typeof(is_manager) == 'undefined' ?  false : is_manager;
//得到以JSON格式为字符串的原型
function S(str){
  return eval( '(' + str + ')' );
}

function reload(s){
  if(typeof(s) == 'undefined'){
    window.location.reload(true);
  }else{
    setTimeout(function(){
      window.location.reload(true);
    }, s*1000);
  }
}


function format_number(n){
  var b=parseInt(n).toString();
  var len=b.length;
  if(len<=3){return b;}
  var r=len%3;
  return r>0?b.slice(0,r)+","+b.slice(r,len).match(/\d{3}/g).join(","):b.slice(r,len).match(/\d{3}/g).join(",");
}


function cut_str(str, len){
  var char_length = 0;
  for (var i = 0; i < str.length; i++){
    var son_str = str.charAt(i);
    encodeURI(son_str).length > 2 ? char_length += 1 : char_length += 0.5;
    if (char_length >= len){
      var sub_len = char_length == len ? i+1 : i;
      return str.substr(0, sub_len);
      break;
    }
  }
  return str;
}

function isname(str,len){
  var namep = /^([\u4e00-\u9fa5]|[\ufe30-\uffa0]|[A-Za-z0-9_-])/;
  if(len){
    namep = /^([\u4e00-\u9fa5]|[\ufe30-\uffa0]|[A-Za-z0-9_-]){6,16}/;
  }
  return namep.test(str);
}

function isqq(str){
  var qqp = /^\d{5,10}/;
  return qqp.test(str);
}

function isemail(str){
  var emailp = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.\-]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
  return emailp.test(str);
}

function istel(str){
  var telphonep = /^(13|15|18|17|14){1}\d{9}$/;
  return telphonep.test(str);
}

function isphone(str){
  var phone = /^(0){1}[0-9]{2,3}(-)?\d{7,8}(\-\d{1,6})?$/;
  return phone.test(str);
}


function isidcard(idcard){
  var cardnumPattern = /^\d{6}((1[89])|(2\d))\d{2}((0\d)|(1[0-2]))((3[01])|([0-2]\d))\d{3}(\d|X)$/i;
  var res = cardnumPattern.test(idcard);
  if(!res){
    var cardnumPattern = /(^\d{15}$)|(^\d{17}([0-9]|X)$)/i;
    var res = cardnumPattern.test(idcard);
  }
  return res;
}
function cut_str(str, len){
  var char_length = 0;
  for (var i = 0; i < str.length; i++){
    var son_str = str.charAt(i);
    encodeURI(son_str).length > 2 ? char_length += 1 : char_length += 0.5;
    if (char_length >= len){
      var sub_len = char_length == len ? i+1 : i;
      return str.substr(0, sub_len);
      break;
    }
  }
  return str;
}

/**
 * ajax提交str数据
 * @param url 地址
 * @param jsobject js一维对象
 * @param successfun 成功回调 回调信息类型依据后台返回而定 如果为write_json则为json格式 否则是文本格式
 * @param errorfun 失败回调
 */
function ajax(url,jsobject,successfun,errorfun,pobj,cache){
  if(!pobj){
    pobj = window;
  }
  if(!cache){
    cache = false;
  }
  var md5key = '';
  if(cache){
    md5key = md5(url+$.toJSON(jsobject));
    if(window[md5key]){
      if(typeof successfun =='string'){
        eval(successfun);
      }else{
        successfun.apply(pobj,window[md5key]);
      }
      return;
    }
  }
  var async = true;
  if(errorfun===false){
    async = false;
  }
  if(typeof (jsobject) == 'string' ){
    var B = new Base64();
    var dataStr = B.encode(jsobject);
  }

  loading('加载中...');
  $.ajax({
    url: url,
    type: "post",
    data: { data : dataStr},
    async:async,
    cache: cache,
    dataType:"json",
    success: function(msg,reqdata){
      loading(false);
      if(cache){
        window[md5key] = [msg,reqdata];
      }
      if(successfun){
        if(typeof successfun =='string'){
          eval(successfun);
        }else{
          successfun.apply(pobj,[msg,reqdata]);
        }
      }
    },error : function(obj,errmsg){
      if(errorfun){
        errorfun.apply(pobj,[errmsg]);
      }
    }
  });
}

static_path = typeof( static_path ) == 'undefined' ? '/static/common/' : static_path;
/**
 * 吐丝信息框
 * @param txt
 * @returns
 */
function tusi(txt,fun){
  if(!is_manager){
    layer_tip_mini(txt, fun);
    return;
  }
  $('.tusi').remove();
  var div = $('<div style="background: url('+static_path+'img/tusi.png);max-width: 85%;min-height: 77px;min-width: 270px;position: absolute;left: -1000px;top: -1000px;text-align: center;border-radius:10px;"><span style="color: #ffffff;line-height: 77px;font-size: 23px;">'+txt+'</span></div>');
  $('body').append(div);
  div.css('zIndex',9999999);
  div.css('left',parseInt(($(window).width()-div.width())/2));
  var top = parseInt($(window).scrollTop()+($(window).height()-div.height())/2);
  div.css('top',top);
  setTimeout(function(){
    div.remove();
    if(fun){
      fun();
    }
  },2000);
}

/**
 * 吐丝信息框
 * @param txt
 * @returns
 */
function toast(txt,fun){
  $('.tusi').remove();
  var div = $('<div style="background: url('+static_path+'/img/tusi.png);max-width: 85%;min-height: 77px;min-width: 270px;position: absolute;left: -1000px;top: -1000px;text-align: center;border-radius:10px;"><span style="color: #ffffff;line-height: 77px;font-size: 23px;">'+txt+'</span></div>');
  $('body').append(div);
  div.css('zIndex',9999999);
  div.css('left',parseInt(($(window).width()-div.width())/2));
  var top = parseInt($(window).scrollTop()+($(window).height()-div.height())/2);
  div.css('top',top);
  setTimeout(function(){
    div.animate({
      top: top-200,
      opacity:0}, {
      duration:888,
      complete:function(){
        div.remove();
        if(fun){
          fun();
        }
      }
    });
  },1888);
}

/**
 * 加载信息框
 * @param txt
 * @returns
 */
function loading(txt){
  if(txt !== false && !is_manager){
    layer_tip_mini(txt);
    return;
  }


  if(txt === false){
    $('.qp_lodediv').remove();
  }else{
    $('.qp_lodediv').remove();
    var div = $('<div class="qp_lodediv" style="background: url('+static_path+'img/loadb.png);width: 269px;height: 107px;position: absolute;left: -1000px;top: -1000px;text-align: center;"><span style="color: #ffffff;line-height: 107px;font-size: 23px; white-space: nowrap;">&nbsp;&nbsp;&nbsp;<img src="'+static_path+'/img/load.gif" style="vertical-align: middle;width:auto;"/>&nbsp;&nbsp;'+txt+'</span></div>');
    $('body').append(div);
    div.css('zIndex',9999999);
    div.css('left',parseInt(($(window).width()-div.width())/2));
    var top = parseInt($(window).scrollTop()+($(window).height()-div.height())/2);
    div.css('top',top);
  }
}


function layer_tip(msg, btn){
  var btn = typeof(btn) == 'undefined' ? '确定' : btn;
  setTimeout(function() {
    layer.open({
      content: msg,
      btn: btn
    })
  }, 350);
}

function layer_tip_mini(msg, fun){
  layer.open({
    content:msg
    ,skin: 'msg'
    ,time: 2
  });
  setTimeout(function(){
    if(fun){
      fun();
    }
  },1000);
}

/*
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.1 Copyright (C) Paul Johnston 1999 - 2002.
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */

/*
 * Configurable variables. You may need to tweak these to be compatible with
 * the server-side, but the defaults work in most cases.
 */
var hexcase = 0;  /* hex output format. 0 - lowercase; 1 - uppercase        */
var b64pad  = ""; /* base-64 pad character. "=" for strict RFC compliance   */
var chrsz   = 8;  /* bits per input character. 8 - ASCII; 16 - Unicode      */

/*
 * These are the functions you'll usually want to call
 * They take string arguments and return either hex or base-64 encoded strings
 */
function hex_md5(s){ return binl2hex(core_md5(str2binl(s), s.length * chrsz));}
function b64_md5(s){ return binl2b64(core_md5(str2binl(s), s.length * chrsz));}
function str_md5(s){ return binl2str(core_md5(str2binl(s), s.length * chrsz));}
function hex_hmac_md5(key, data) { return binl2hex(core_hmac_md5(key, data)); }
function b64_hmac_md5(key, data) { return binl2b64(core_hmac_md5(key, data)); }
function str_hmac_md5(key, data) { return binl2str(core_hmac_md5(key, data)); }

/*
 * Perform a simple self-test to see if the VM is working
 */
function md5_vm_test()
{
  return hex_md5("abc") == "900150983cd24fb0d6963f7d28e17f72";
}

/*
 * Calculate the MD5 of an array of little-endian words, and a bit length
 */
function core_md5(x, len)
{
  /* append padding */
  x[len >> 5] |= 0x80 << ((len) % 32);
  x[(((len + 64) >>> 9) << 4) + 14] = len;

  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;

  for(var i = 0; i < x.length; i += 16)
  {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;

    a = md5_ff(a, b, c, d, x[i+ 0], 7 , -680876936);
    d = md5_ff(d, a, b, c, x[i+ 1], 12, -389564586);
    c = md5_ff(c, d, a, b, x[i+ 2], 17,  606105819);
    b = md5_ff(b, c, d, a, x[i+ 3], 22, -1044525330);
    a = md5_ff(a, b, c, d, x[i+ 4], 7 , -176418897);
    d = md5_ff(d, a, b, c, x[i+ 5], 12,  1200080426);
    c = md5_ff(c, d, a, b, x[i+ 6], 17, -1473231341);
    b = md5_ff(b, c, d, a, x[i+ 7], 22, -45705983);
    a = md5_ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
    d = md5_ff(d, a, b, c, x[i+ 9], 12, -1958414417);
    c = md5_ff(c, d, a, b, x[i+10], 17, -42063);
    b = md5_ff(b, c, d, a, x[i+11], 22, -1990404162);
    a = md5_ff(a, b, c, d, x[i+12], 7 ,  1804603682);
    d = md5_ff(d, a, b, c, x[i+13], 12, -40341101);
    c = md5_ff(c, d, a, b, x[i+14], 17, -1502002290);
    b = md5_ff(b, c, d, a, x[i+15], 22,  1236535329);

    a = md5_gg(a, b, c, d, x[i+ 1], 5 , -165796510);
    d = md5_gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
    c = md5_gg(c, d, a, b, x[i+11], 14,  643717713);
    b = md5_gg(b, c, d, a, x[i+ 0], 20, -373897302);
    a = md5_gg(a, b, c, d, x[i+ 5], 5 , -701558691);
    d = md5_gg(d, a, b, c, x[i+10], 9 ,  38016083);
    c = md5_gg(c, d, a, b, x[i+15], 14, -660478335);
    b = md5_gg(b, c, d, a, x[i+ 4], 20, -405537848);
    a = md5_gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
    d = md5_gg(d, a, b, c, x[i+14], 9 , -1019803690);
    c = md5_gg(c, d, a, b, x[i+ 3], 14, -187363961);
    b = md5_gg(b, c, d, a, x[i+ 8], 20,  1163531501);
    a = md5_gg(a, b, c, d, x[i+13], 5 , -1444681467);
    d = md5_gg(d, a, b, c, x[i+ 2], 9 , -51403784);
    c = md5_gg(c, d, a, b, x[i+ 7], 14,  1735328473);
    b = md5_gg(b, c, d, a, x[i+12], 20, -1926607734);

    a = md5_hh(a, b, c, d, x[i+ 5], 4 , -378558);
    d = md5_hh(d, a, b, c, x[i+ 8], 11, -2022574463);
    c = md5_hh(c, d, a, b, x[i+11], 16,  1839030562);
    b = md5_hh(b, c, d, a, x[i+14], 23, -35309556);
    a = md5_hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
    d = md5_hh(d, a, b, c, x[i+ 4], 11,  1272893353);
    c = md5_hh(c, d, a, b, x[i+ 7], 16, -155497632);
    b = md5_hh(b, c, d, a, x[i+10], 23, -1094730640);
    a = md5_hh(a, b, c, d, x[i+13], 4 ,  681279174);
    d = md5_hh(d, a, b, c, x[i+ 0], 11, -358537222);
    c = md5_hh(c, d, a, b, x[i+ 3], 16, -722521979);
    b = md5_hh(b, c, d, a, x[i+ 6], 23,  76029189);
    a = md5_hh(a, b, c, d, x[i+ 9], 4 , -640364487);
    d = md5_hh(d, a, b, c, x[i+12], 11, -421815835);
    c = md5_hh(c, d, a, b, x[i+15], 16,  530742520);
    b = md5_hh(b, c, d, a, x[i+ 2], 23, -995338651);

    a = md5_ii(a, b, c, d, x[i+ 0], 6 , -198630844);
    d = md5_ii(d, a, b, c, x[i+ 7], 10,  1126891415);
    c = md5_ii(c, d, a, b, x[i+14], 15, -1416354905);
    b = md5_ii(b, c, d, a, x[i+ 5], 21, -57434055);
    a = md5_ii(a, b, c, d, x[i+12], 6 ,  1700485571);
    d = md5_ii(d, a, b, c, x[i+ 3], 10, -1894986606);
    c = md5_ii(c, d, a, b, x[i+10], 15, -1051523);
    b = md5_ii(b, c, d, a, x[i+ 1], 21, -2054922799);
    a = md5_ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
    d = md5_ii(d, a, b, c, x[i+15], 10, -30611744);
    c = md5_ii(c, d, a, b, x[i+ 6], 15, -1560198380);
    b = md5_ii(b, c, d, a, x[i+13], 21,  1309151649);
    a = md5_ii(a, b, c, d, x[i+ 4], 6 , -145523070);
    d = md5_ii(d, a, b, c, x[i+11], 10, -1120210379);
    c = md5_ii(c, d, a, b, x[i+ 2], 15,  718787259);
    b = md5_ii(b, c, d, a, x[i+ 9], 21, -343485551);

    a = safe_add(a, olda);
    b = safe_add(b, oldb);
    c = safe_add(c, oldc);
    d = safe_add(d, oldd);
  }
  return Array(a, b, c, d);

}

/*
 * These functions implement the four basic operations the algorithm uses.
 */
function md5_cmn(q, a, b, x, s, t)
{
  return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s),b);
}
function md5_ff(a, b, c, d, x, s, t)
{
  return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function md5_gg(a, b, c, d, x, s, t)
{
  return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function md5_hh(a, b, c, d, x, s, t)
{
  return md5_cmn(b ^ c ^ d, a, b, x, s, t);
}
function md5_ii(a, b, c, d, x, s, t)
{
  return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Calculate the HMAC-MD5, of a key and some data
 */
function core_hmac_md5(key, data)
{
  var bkey = str2binl(key);
  if(bkey.length > 16) bkey = core_md5(bkey, key.length * chrsz);

  var ipad = Array(16), opad = Array(16);
  for(var i = 0; i < 16; i++)
  {
    ipad[i] = bkey[i] ^ 0x36363636;
    opad[i] = bkey[i] ^ 0x5C5C5C5C;
  }

  var hash = core_md5(ipad.concat(str2binl(data)), 512 + data.length * chrsz);
  return core_md5(opad.concat(hash), 512 + 128);
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function safe_add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left.
 */
function bit_rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * Convert a string to an array of little-endian words
 * If chrsz is ASCII, characters >255 have their hi-byte silently ignored.
 */
function str2binl(str)
{
  var bin = Array();
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < str.length * chrsz; i += chrsz)
    bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (i%32);
  return bin;
}

/*
 * Convert an array of little-endian words to a string
 */
function binl2str(bin)
{
  var str = "";
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < bin.length * 32; i += chrsz)
    str += String.fromCharCode((bin[i>>5] >>> (i % 32)) & mask);
  return str;
}

/*
 * Convert an array of little-endian words to a hex string.
 */
function binl2hex(binarray)
{
  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i++)
  {
    str += hex_tab.charAt((binarray[i>>2] >> ((i%4)*8+4)) & 0xF) +
        hex_tab.charAt((binarray[i>>2] >> ((i%4)*8  )) & 0xF);
  }
  return str;
}

/*
 * Convert an array of little-endian words to a base-64 string
 */
function binl2b64(binarray)
{
  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i += 3)
  {
    var triplet = (((binarray[i   >> 2] >> 8 * ( i   %4)) & 0xFF) << 16)
        | (((binarray[i+1 >> 2] >> 8 * ((i+1)%4)) & 0xFF) << 8 )
        |  ((binarray[i+2 >> 2] >> 8 * ((i+2)%4)) & 0xFF);
    for(var j = 0; j < 4; j++)
    {
      if(i * 8 + j * 6 > binarray.length * 32) str += b64pad;
      else str += tab.charAt((triplet >> 6*(3-j)) & 0x3F);
    }
  }
  return str;
}
function md5(code){
  return hex_md5(code);
}

function goto_back(){
  history.go(-1);
}

function _goto(url){
  window.location.href = url;
}

//裁剪图片
function getCutImgUrl(id, src, w, h){
  $('body').append('<canvas id="s_tanvas" style="width:100%;display:none;"></canvas>');
  //源图片渲染的画布
  var s_tanvas = document.getElementById("s_tanvas");

  //裁剪后的宽
  var t_w = typeof( w ) == 'undefined' ? 185 : w;
  //裁剪后的高
  var t_h = typeof( h ) == 'undefined' ? 134 : h;

  //源面布 指定图片路径
  var cxt_s = s_tanvas.getContext("2d")
  var img = new Image();
  img.src = src;

  //临时画布， 用于存储裁剪后的图片
  var canvas_temp = document.createElement("canvas")
  var cxt_temp = canvas_temp.getContext("2d")

  img.onload = function(){
    //源画布渲染图片
    cxt_s.drawImage(img, 0, 0, s_tanvas.width, s_tanvas.height);

    //在源图片上进行图片的裁剪， X Y 裁剪的宽  裁剪的高
    var srcX = ( s_tanvas.width ) / 2 - (( s_tanvas.width ) / 2)/2;
    var srcY = 0;
    var dataImg = cxt_s.getImageData(srcX,srcY,t_w,t_h);

    //临时画布的宽高
    canvas_temp.width = t_w;
    canvas_temp.height = t_h;

    //临时画布渲染图片
    cxt_temp.putImageData(dataImg,0,0,0,0,canvas_temp.width,canvas_temp.height)
    var img_temp = canvas_temp.toDataURL("image/png");

    var t_img = new Image();
    t_img.src = img_temp;

    $('#imgsrc_'+id).attr('src', img_temp);
  }

}