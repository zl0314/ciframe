  /*canvas在线切图*/
  var Canvas_cut = function(options) {

      //变量
      var Options = {
          width: 1000,
          height: 1000,
          cutWidth: 150,
          cutHeight: 150,
          cutMinSize: 150, //裁剪框最小尺寸，即最小可以缩放到这个size，width及height任意一个都无法小于这个值。

          //--系统自带，运行时自动运算，请不要修改。
          cropViewWidth: 0, //在画布里面显示的最大宽度
          cropViewHeight: 0, //在画布里面显示的最大高度
          cropLeft: 0,
          cropTop: 0,
          //--裁剪框
          cutViewWidth: 0, //当前宽度，
          cutViewHeight: 0, //当前高度
          cutMaxWidth: 0, //裁剪框最大宽度。
          cutMaxHeight: 0, //裁剪框最大高度。
          //--四象限。用于判断距离。
          cutBoxLimitX1: 0,
          cutBoxLimitX2: 0,
          cutBoxLimitY1: 0,
          cutBoxLimitY2: 0,
          cutLeft: 0, //裁剪框绝对定位，左侧距离。
          cutTop: 0, //裁剪框绝对定位，离顶部距离。
          initStatus: false, //当前组件是否已经初始化了。
          Orientation: 1, //当前图片的颠倒状态，通过exif读取。1表示正常，3表示上下颠倒，8表示要顺时针旋转90度。6表示逆时针旋转90度。
          transX: 0, //需要按照该起始点进行旋转
          transY: 0,
          XDimension: 0,
          YDimension: 0
      };
      var Options_image = {
          width: 0,
          height: 0,
          scaleWidth: 0, //图片缩放后大小
          scaleHeight: 0, //图片缩放后大小
          imgData: ""
      };
      //--添加缩放功能。
      var Options_zoom = {
          beginX1: 0,
          beginY1: 0,
          beginX2: 0,
          beginY2: 0,
          endX1: 0,
          endY1: 0,
          endX2: 0,
          endY2: 0
      };
      //--添加裁剪框移动功能
      var Options_move = {
          isInCutter: false,
          beginX1: 0,
          beginY1: 0,
          endX1: 0,
          endY1: 0
      };
      console.log(options);
      $.extend(Options, options);
      //---修正ios压扁问题。
      //--修正ios下面canvas图片压缩的情况。
      /**
       * Detecting vertical squash in loaded image.
       * Fixes a bug which squash image vertically while drawing into canvas for some images.
       * This is a bug in iOS6 devices. This function from https://github.com/stomita/ios-imagefile-megapixel
       *
       */
      function detectVerticalSquash(img) {
          var iw = img.naturalWidth,
              ih = img.naturalHeight;
          var canvas = document.createElement('canvas');
          canvas.width = 1;
          canvas.height = ih;
          var ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0);
          var data = ctx.getImageData(0, 0, 1, ih).data;
          // search image edge pixel position in case it is squashed vertically.
          var sy = 0;
          var ey = ih;
          var py = ih;
          while (py > sy) {
              var alpha = data[(py - 1) * 4 + 3];
              if (alpha === 0) {
                  ey = py;
              } else {
                  sy = py;
              }
              py = (ey + sy) >> 1;
          }
          var ratio = (py / ih);
          return (ratio === 0) ? 1 : ratio;
      }

      /**
       * A replacement for context.drawImage
       * (args are for source and destination).
       */
      function drawImageIOSFix(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
          var vertSquashRatio = detectVerticalSquash(img);
          // Works only if whole image is displayed:
          // ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
          // The following works correct also when only a part of the image is displayed:
          ctx.drawImage(img, sx * vertSquashRatio, sy * vertSquashRatio,
              sw * vertSquashRatio, sh * vertSquashRatio,
              dx, dy, dw, dh);
      }


      //--变量及常用状态，方法。
      var jel_tips = $("#tips");
      var oFReader = new FileReader(),
          rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

      /*var tmpCanvas=document.getElementById("tmp_canvas");
      var tmpContext=tmpCanvas.getContext("2d");
      var canvas = document.getElementById('canvas');
      var context = canvas.getContext('2d');*/

      var tmpCanvas;
      var tmpContext;
      var canvas;
      var context;



      function drawImageAllFixed(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
          var vertSquashRatio = detectVerticalSquash(img);
          // Works only if whole image is displayed:
          // ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
          // The following works correct also when only a part of the image is displayed:

          ctx.drawImage(img, sx * vertSquashRatio, sy * vertSquashRatio,
              sw * vertSquashRatio, sh * vertSquashRatio,
              dx, dy, dw, dh);
      }



      function append_hide_div() {
          $('body').append('<div id="canvas_cut" style="height:0; overflow:hidden;">\
                <canvas id="tmp_canvas" width="' + Options.width + '" height="' + Options.height + '"></canvas>\
                <canvas id="canvas" width="' + Options.width + '" height="' + Options.height + '" ></canvas>\
                </div>');

          tmpCanvas = document.getElementById("tmp_canvas");
          tmpContext = tmpCanvas.getContext("2d");
          canvas = document.getElementById('canvas');
          context = canvas.getContext('2d');
      }

      //--逻辑方法定义
      //$("#uploadImage").change(function(){
      var st;
      console.log(Options);
      Options.fileinput.change(function() {
          this.disabled = true;
          st = new Date().getTime();
          console.log(st);
          append_hide_div();
          if ($(this)[0].files.length === 0) {
              $('#canvas_cut').remove();
              return;
          }
          var oFile = $(this)[0].files[0];
          jel_tips.html(oFile.size / (1024 * 1024));
          //if (!rFilter.test(oFile.type)) { alert("You must select a valid image file!"); return; }
          //oFReader.readAsDataURL(oFile);
          read_file(oFile);

          $(this).val('');
      });


      function read_file(oFile) {
          var oFReader = null;
          oFReader = new FileReader();
          oFReader.readAsDataURL(oFile);

          oFReader.onload = function(oFREvent) {
              //document.getElementById("uploadPreview").src = oFREvent.target.result;
              //alert(oFREvent.target.result);
              var image = new Image();

              image.onload = function() {
                  //--修正ios6 7 颠倒，压扁问题。
                  __EXIF.getData(this, function() {
                      var make = __EXIF.getTag(this, "Make");
                      //jel_tips.text("相关图片信息："+__EXIF.pretty(this));
                      Options.Orientation = __EXIF.getTag(this, "Orientation");

                      if (Options.Orientation == undefined) {
                          Options.Orientation = 1;
                      }

                      var _realWidth = 0;
                      var _realHeight = 0;
                      //--计算是否需要旋转。
                      if (Options.Orientation != 1) {
                          Options.transX = parseInt(Options.width / 2);
                          Options.transY = parseInt(Options.height / 2);

                      } else {
                          Options.transX = 0;
                          Options.transY = 0;
                          _realWidth = image.width;
                          _realHeight = image.height;
                      }
                      var XDimension = __EXIF.getTag(this, "PixelXDimension");
                      var YDimension = __EXIF.getTag(this, "PixelYDimension");
                      Options.XDimension = XDimension;
                      Options.YDimension = YDimension;
                      caculateOptions(image.width, image.height);
                      //--放入临时画布。
                      tmpContext.clearRect(0, 0, tmpCanvas.width, tmpCanvas.height);
                      //context.clearRect(0,0,canvas.width,canvas.height);
                      //context.drawImage(image, 0, 0, image.width, image.height, Options.cropLeft, Options.cropTop, Options_image.scaleWidth, Options_image.scaleHeight);
                      // context.drawImage(image, 0, 0, image.width, image.height, Options.cropLeft, Options.cropTop, canvas.width, canvas.height);
                      drawImageIOSFix(tmpContext, image, 0, 0, image.width, image.height, Options.cropLeft, Options.cropTop, Options_image.scaleWidth, Options_image.scaleHeight);
                      context.clearRect(0, 0, canvas.width, canvas.height);
                      //生成一个可以用的缩略图。

                      switch (Options.Orientation) {
                          case 8:

                              // 90 rotate left     --需要90度向左旋转。。那么，这个 PixelYDimension就是宽度了，PixelXDimension就是高度了。
                              context.translate(Options.transX, Options.transY);
                              context.rotate(-0.5 * Math.PI);
                              _realWidth = YDimension;
                              _realHeight = XDimension;
                              break;
                          case 3:
                              //180向左旋转
                              context.translate(Options.transX, Options.transY);
                              context.rotate(Math.PI);
                              _realWidth = XDimension;
                              _realHeight = YDimension;
                              break;
                          case 6:
                              //90 rotate right 需要向右旋转90度，PixelYDimension就是宽度了，PixelXDimension就是高度了。
                              _realWidth = YDimension;
                              _realHeight = XDimension;

                              context.translate(Options.transX, Options.transY);
                              context.rotate(0.5 * Math.PI);
                              break;
                          case 1:
                              break;
                      }


                    //  caculateOptions(_realWidth, _realHeight);
                      context.drawImage(tmpCanvas, 0, 0, tmpCanvas.width, tmpCanvas.height, 0 - Options.transX, 0 - Options.transY, tmpCanvas.width, tmpCanvas.height);



                      //jel_tips.html('<br>'+Options.cropViewWidth +' ,' +Options.cropViewHeight);

                      //重新画到一个新的canvas里，（目的是去掉四周的空白）
                      $('#canvas_cut').append('<canvas id="canvas_finish" width="' + Options.cropViewWidth + '" height="' + Options.cropViewHeight + '" ></canvas>');

                      var ctx = $('#canvas_finish')[0].getContext("2d");
                      var img = canvas;
                      var sx = (Options.width - Options.cropViewWidth) * 0.5;
                      var sy = (Options.height - Options.cropViewHeight) * 0.5;
                      var sw = Options.cropViewWidth;
                      var sh = Options.cropViewHeight;
                      var dx = 0;
                      var dy = 0;
                      var dw = Options.cropViewWidth;
                      var dh = Options.cropViewHeight;

                      //console.log(context.getImageData(sx,sy,sw,sh).toDataURL());

                      ctx.putImageData(context.getImageData(sx, sy, sw, sh), 0, 0);

                      //return_base_str(ctx.getImageData(sx,sy,sw,sh));


                      /*ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh);*/
                      return_base64();
                      var st2 = (new Date().getTime()) - st;
                      jel_tips.html("上面代码的执行时间为" + st2 + "毫秒");
                      //--计算比例，将其放到canvas里面。


                      //context.drawImage(tmpCanvas,0,0,tmpCanvas.width,tmpCanvas.height,0-Options.transX,0-Options.transY,tmpCanvas.width,tmpCanvas.height);
                      //context.drawImage(tmpCanvas,0,0,tmpCanvas.width,tmpCanvas.height,0,0,tmpCanvas.width,tmpCanvas.height);
                      //changePanel("edit");
                  });
                  return;

              };

              image.src = oFREvent.target.result;
          };
      }


      function return_base_str(base_str) {

          var canvas = $('#canvas_finish')[0];
          var base_str = base_str;

          var img = new Image();
          img.src = base_str;
          img.id = "finish_img";

          $('body').append(img);

          Options.callback(base_str);

          $('#canvas_cut').remove();
      }

      function return_base64() {

          var canvas = $('#canvas_finish')[0];
          var base_str = canvas.toDataURL("image/png");

          var img = new Image();
          img.src = base_str;
          img.id = "s_img";

          $('body').append(img);

          $('#canvas_cut').remove();

          tmpCanvas = null;
          tmpContext = null;
          canvas = null;
          context = null;
          $(Options.fileinput)[0].disabled = false;
          Options.callback(base_str);
      }

      //--通过宽度及高度计算各种尺寸。
      function caculateOptions(_width, _height) {
          Options_image.width = _width;
          Options_image.height = _height;
          var scale = Math.max(Options_image.width / Options.width, Options_image.height / Options.height);
          if (scale > 1) {
              Options.cropViewWidth = parseInt(Math.floor(Options_image.width / scale));
              Options.cropViewHeight = parseInt(Math.floor(Options_image.height / scale));
          } else {
              Options.cropViewWidth = Options_image.width;
              Options.cropViewHeight = Options_image.height;
          }

          Options_image.scaleWidth = Options.cropViewWidth;
          Options_image.scaleHeight = Options.cropViewHeight;

          //--计算比例，将其放到canvas里面。

          Options.cropViewWidth = Options_image.scaleWidth;
          Options.cropViewHeight = Options_image.scaleHeight;
          //--计算画布里面的图像的位置。
          Options.cropLeft = parseInt((Options.width - Options.cropViewWidth) / 2);
          Options.cropTop = parseInt((Options.height - Options.cropViewHeight) / 2);

          //--计算裁剪框实际大小及实际位置。
          //计算裁剪框的位置。

          var scale_2 = Math.max(Options.cutWidth / Options.cropViewWidth, Options.cutHeight / Options.cropViewHeight);
          if (scale_2 > 1) {
              Options.cutViewWidth = parseInt(Math.floor(Options.cutWidth / scale_2));
              Options.cutViewHeight = parseInt(Math.floor(Options.cutHeight / scale_2));
          } else {
              //两个大于cutWidth及cutHeight，那么就按照相关步骤得出最大值。

              Options.cutViewHeight = parseInt(Options.cutHeight / scale_2);
              Options.cutViewWidth = parseInt(Options.cutWidth / scale_2);
          }
          Options.cutMaxWidth = Options.cutViewWidth;
          Options.cutMaxHeight = Options.cutViewHeight;

          Options.cutLeft = parseInt(Math.floor((Options.width - Options.cutViewWidth)) / 2);
          Options.cutTop = parseInt(Math.floor((Options.height - Options.cutViewHeight)) / 2);
          //-四象限。
          Options.cutBoxLimitX1 = parseInt((Options.width - Options_image.scaleWidth) / 2);
          Options.cutBoxLimitX2 = Options.cutBoxLimitX1 + Options_image.scaleWidth;
          Options.cutBoxLimitY1 = parseInt((Options.height - Options_image.scaleHeight) / 2);
          Options.cutBoxLimitY2 = Options.cutBoxLimitY1 + Options_image.scaleHeight;
      }

  }


  var square = function(str) {
     var w=cut_width || 150;
     var h=cut_height || 150;

      var c_canvas = document.createElement("canvas");
      c_canvas.id = "pic";
      c_canvas.width = w;
      c_canvas.height = h;


      var img = new Image();
      img.src=str;
      img.onload=function(){
         var img_w = this.width;
         var img_h = this.height;

         $('body').append(c_canvas);

           var statx = 0;
           var staty = 0;
           var cut_w;
           var cut_h;
           var ratio1 = img_w / img_h;   // 2000/1203
           var ratio2 = w / h;           // 320/480
           
           if(ratio1>=ratio2){
               
               staty = 0;
               cut_w = img_h * ratio2;
               cut_h = img_h;
               statx = (img_w-cut_w)*0.5;
               
               
               
           }else{
               statx=0;
               cut_h = img_w / ratio2;
               cut_w = img_w;
               staty = (img_h-cut_h)*0.5;
               
           }
           
           c_canvas.getContext("2d").drawImage(this,statx,staty,cut_w,cut_h,0,0,w,h);

           //return c_canvas.toDataURL("image/png");

           parent.up_face(c_canvas.toDataURL("image/png"));

           $('img').remove();
           $('#pic').remove();

           //window.location.reload();

      }
  }


  //调用
  Canvas_cut({
      "fileinput": $('#uploadImage'),
      "callback": function(base_str) {
          
          //切成一个正方形
          square(base_str);

          


      }

  })