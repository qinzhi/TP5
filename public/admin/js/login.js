// JavaScript Document
//支持Enter键登录
		document.onkeydown = function(e){
			if($(".bac").length==0)
			{
				if(!e) e = window.event;
				if((e.keyCode || e.which) == 13){
					var obtnLogin=document.getElementById("submit_btn")
					obtnLogin.focus();
				}
			}
		}

    	$(function(){
			//提交表单
			$('#submit_btn').click(function(){
				show_loading();
				if($('#account').val() == ''){
					show_err_msg('账号还没填呢！');
					$('#account').focus();
				}else if($('#password').val() == ''){
					show_err_msg('密码还没填呢！');
					$('#password').focus();
				}else if($('#captcha').val() == ''){
                    show_err_msg('验证码还没填呢！');
                    $('#captcha').focus();
                }else{
                    var params = $(this).closest('form').serialize();
                    $.post(location.href,params,function(result){
                        if(result.code == 1){
                            show_msg('登录成功咯！  正在为您跳转...','/');
                        }else{
                            $('#verify').attr('src','/captcha?rand=' + Math.random());
                            show_err_msg(result.msg);
                        }
                    });
				}
			});
		});