<script>
//    function collect_it(collect, callback){
//        var data = 'type='+type + '&id='+id + '&collect='+collect;
//        ajax(SITE_URL + 'Collect', data, function (res){
//            console.log(res.success);
//            if(typeof (callback) == 'function'){
//                callback(res);
//            }
//            if(res.success == 1){
//                if($('#follow').hasClass('curr')){
//                    $('#follow').removeClass('curr');
//                }else{
//                    $('#follow').addClass('curr');
//                }
//
//                if($('#follow2').hasClass('curr')){
//                    $('#follow2').removeClass('curr');
//                }else{
//                    $('#follow2').addClass('curr');
//                }
//            }
//            layer.open({
//                content: res.message
//                ,skin: 'msg'
//                ,time: 2
//            });
//            if(typeof(res.data.forward) != 'undefined'){
//                var url = SITE_URL  + SITE_CLASS  + '/' +  SITE_METHOD + '/' + id;
//                _goto(SITE_URL + 'User/signin?forward=' + encodeURIComponent(url));
//            }
//        });
//    }
</script>
</body>
</html>