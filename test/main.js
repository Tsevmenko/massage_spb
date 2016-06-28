$( document ).ready(function() {
	
	$('#oferta').click(function(e){
		e.preventDefault();
		$('#popup-bg').fadeIn();
		$('#popup-text').fadeIn();
	});
	$('#popup-bg').click(function(){
		$('#popup-text').hide();
		$('#popup-bg').hide();
	});
	
    $('.phoneNumber').mask('+7 (000) 000 00 00', {placeholder: "+7 ("});
    $('#company_phone').mask('+7 (000) 000 00 00', {placeholder: "+7 "});
	  
	//	list select
	$('.select span').click(function(){
		$(this).parent('.select').find('ul').toggleClass('dropped');
	});
	
	$('.select li').click(function(){
		if($(this).parents('.select').hasClass('e')){
			$(this).parents('.select').siblings('.input').find('input').val($(this).text());
			$(this).parents('.select').find('ul').toggleClass('dropped');
      $(this).parents('.select').find('input').val($(this).data('value'));
		}else{
			$(this).siblings().removeClass('selected');
			$(this).addClass('selected');
			$(this).parents('.select').find('span').html($(this).text());
			$(this).parents('.select').find('input').val($(this).data('value'));
			$(this).parents('.select').find('ul').toggleClass('dropped');
		}
	});

	
	$('.e .register span').click(function(e){
		e.preventDefault();
		if(e.target.tagName != 'A'){
			$(this).toggleClass('checked')
		}
	});
	
	
	$('.fx-form input').focusin(function(e){
		$(this).parents('.fx-row').css('background','#f5fbf8');
		$(this).parents('.fx-row').find('.select').css('background','#f5fbf8');
		/*$(this).parents('.select span').css('background','#f5fbf8 !important');*/
	}).focusout(function(e) {
        if($(this).parents('.fx-row').hasClass('alt')){
			$(this).parents('.fx-row').css('background','#f9f8fd');
		}else{
			$(this).parents('.fx-row').css('background','#fff');
		}
		$(this).parents('.fx-row').find('.select').css('background','#f4f4f7');
    });;
	
	
	//	show/hide login form
	$('.fx-slider').click(function(e){
		e.preventDefault();
		$i = $(this).find('i')
		if($i.hasClass('left')){
			$i.removeClass('left');
			$('.fx-form-2').hide();
			$('.fx-form-1').show();
			$('.fx-select .t1').removeClass('active');
			$('.fx-select .t2').addClass('active');
			$(this).find('input').val('0');
		}else{
			$i.addClass('left');
			$('.fx-form-1').hide();
			$('.fx-form-2').show();
			$('.fx-select .t2').removeClass('active');
			$('.fx-select .t1').addClass('active');
			$(this).find('input').val('1');
		}
	});

    $("#authBtn").on('click',function(e) {   
        e.preventDefault();
        var login = $('#userlogin').val();
        var pass = $('#userpassword').val();
        if ($("#rememberme").prop("checked")) {
          var remember = 'Y';
        } else {
          var remember = 'N';
        }
        $('.required-field').removeClass('required-field');
        $.post(
           thisUrl,
           {
              is_authorization: "Y",  
              login: login,
              pass: pass, 
              remember: remember,                                                                                   
           }, function(data) {
              if (data === '1') {
                 window.location.href = "/";
              } else {
                $('.fx-line-4').addClass('required-field');
              }              
           });
        return false;
    }); 
    $("#goRegister").on('click',function(e) {   
        e.preventDefault();
        window.location.href = '/register';
        return false;
    });
    $(".goAuth").on('click',function(e) {   
        e.preventDefault();
        window.location.href = '/login';
        return false;
    });           
    $("#remBtn").on('click',function(e) {   
        e.preventDefault();
        $('#resultForgot').text('');
        var email = $('#useremail').val();
        $.post(
           thisUrl,
           {
              is_email_reminder: "Y",  
              email: email                                                                                  
           }, function(data) {
              if (data === '1') {
                 $('#resultForgot').text('Контрольная строка для смены пароля выслана на ваш E-mail.');
              } else {
                 $('#resultForgot').text('Указанный E-mail не найден.');
              }              
           });
        return false;
    });   
    
    $("#changePWDBtn").on('click',function(e) {   
        e.preventDefault();
        var userpassword = $('#userpassword').val();
        var repeatpassword = $('#repeatpassword').val();
        var login = $('#login').val();
        var key = $('#key').val();        
        var flag = true;
        $('.fx-line-4').removeClass('required-field1')
        $('.fx-line-4').removeClass('required-field2');
        $('#Resulttext').text('');
        if ((userpassword.length < 6)) {
           flag = false;
           $('.fx-line-4').addClass('required-field1');
           return false;
        }  
        if  (userpassword != repeatpassword) {
           flag = false;
           $('.fx-line-4').addClass('required-field2');
           return false;       
        }
        if ((login.length < 3) || (key.length < 3)) {
           flag = false;
           $('#Resulttext').text('Неверное контрольное слово');
        }          
        if (flag) {
          $.post(
             thisUrl,
             {
                is_change_password: "Y",  
                userpassword: userpassword,
                repeatpassword: repeatpassword, 
                login: login,
                key: key,                 
             }, function(data) {
                console.log(data);
                if (data === '1') {
                   window.location.href = "/login";                 
                } else if(data === '0')  {
                   $('#Resulttext').text('Неверно заполнены поля');
                }  else if(data === '-1')  {
                   $('#Resulttext').text('Неверный проверочный код');
                }  else if(data === '-2')  {
                   $('#Resulttext').text('Неверный логин');
                }

             });
        }                                    
        return false;
    });   
    function IsEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }      
    $('.nextstep').on('click', function(e){
        e.preventDefault();
        $('.required-field3').removeClass('required-field3');
        $('.required-field4').removeClass('required-field4');
        $('.required-field5').removeClass('required-field5');
        $('.required-field6').removeClass('required-field6');   
        $('.required-field7').removeClass('required-field7');                              
        var email = $('#salon_useremail').val();
        var salonname = $('#salonname').val();
        var adminname = $('#salon_adminname').val();
        var adminlastname = $('#salon_adminlastname').val();        
        var userpass = $('#salon_userpass').val();
        var repeatpass = $('#salon_repeatpass').val(); 
        var flag = true;
        if (salonname.length < 3) {
           $('#salonname').parent().addClass('required-field3');
           flag = false;
        } 
        if (adminname.length < 3) {
           $('#salon_adminname').parent().addClass('required-field3');
           flag = false;
        } 
        if (adminlastname.length < 3) {
           $('#salon_adminlastname').parent().addClass('required-field3');
           flag = false;
        } 
        if (userpass.length < 6) {
           $('#salon_userpass').parent().addClass('required-field4');
           flag = false;
        }                         
        if  (userpass != repeatpass) {
           $('#salon_repeatpass').parent().addClass('required-field5');
           flag = false;      
        }
        if  (!IsEmail(email)) {
           $('#salon_useremail').parent().addClass('required-field6');
           flag = false;      
        }        
        if (flag) {      
            var is_type = $('.fx-slider').find('i');
            if (is_type.hasClass('left')) {
              $.post(
                 thisUrl,
                 {
                    is_email_check: "Y",  
                    email: email,                                                                                           
                 }, function(data) {
                    if (data != '1') {
                       $('#salon_useremail').parent().addClass('required-field7');
                       return false;
                    } else {
                        $('.first_step').hide();
                        $('.second_step').show();                     
                    }                                                    
                 });                        
            } else {
              $.post(
                 thisUrl,
                 {
                    is_master_register: "Y",  
                    email: email,
                    salonname: salonname,
                    adminname: adminname,
                    adminlastname: adminlastname,              
                    userpass: userpass,
                    repeatpass: repeatpass                                                                                            
                 }, function(data) {
                    if (data === '1') {
                       window.location.href = "/login";                 
                    } else if (data == '-1') {
                       $('#salon_useremail').parent().addClass('required-field7');
                    } 
                    else {
                       $('#errortext').show();
                    }                                                       
                 });
            }            
        }
        return false;
    }); 
    $("#salonReg").click(function (e) {
        e.preventDefault();
        $('.required-field3').removeClass('required-field3');                            
        var email = $('#salon_useremail').val();
        var salonname = $('#salonname').val();
        var adminname = $('#salon_adminname').val();
        var adminlastname = $('#salon_adminlastname').val();        
        var userpass = $('#salon_userpass').val();
        var repeatpass = $('#salon_repeatpass').val(); 
        
        var biztype = $('#biztype').val() ;
        var company_name = $('#company_name').val();
        var company_address = $('#company_address').val();        
        var company_phone = $('#company_phone').val();
        var previus_system_manual = $('#previus_system_manual').val(); 
        var why_manual = $('#why_manual').val();
       
        var flag = true; 
        if (biztype == '-1') {
           $('.biztype').addClass('required-field3');
           flag = false;
        }
        if (company_name.length < 3) {
             $('.biztype').addClass('required-field3');
             flag = false;
        }         
        if (company_address.length < 3) {
           $('.company_address').addClass('required-field3');
           flag = false;
        } 
        if (company_phone.length < 3) {
           $('.company_phone').addClass('required-field3');
           flag = false;
        }
        var oferta = 'Y';
        if (!$("#oferta_check").hasClass("checked")) {
          alert('Для продолжения регистрации, Вам необходимо принять условия договора-оферты');
          flag = false; 
          oferta = 'N';
        }     
        if (flag) { 
              $.post(
                 thisUrl,
                 {
                    is_salon_register: "Y",  
                    email: email,
                    salonname: salonname,
                    adminname: adminname,
                    adminlastname: adminlastname,              
                    userpass: userpass,
                    repeatpass: repeatpass, 
                    biztype: biztype,
                    company_name: company_name,
                    company_address: company_address,
                    company_phone: company_phone,              
                    previus_system_manual: previus_system_manual,
                    why_manual: why_manual,
                    oferta:oferta                                                                                                                
                 }, function(data) {
                    console.log(data);
                    if (data === '1') {
                       window.location.href = "/login";                 
                    } else {
                       $('#errortext2').show();
                    }                                                       
                 });
        }              
      return false;
    });    
       	  
});

$('#send').click(function () {
    $.ajax({
        "type": 'POST',
        "url": "https://mandrillapp.com/api/1.0/messages/send.json",
        "data": {
            "key": "N7oAnirHRJC6CZBp7DgAKQ",
            "message": {
                "text": "Поступила новая заявка с GetBAM Landing \nИмя: " + $('.name').val() + "\nТелефон: "+ $('.phoneNumber').val(),
                "to": [
                    {
                        "email": "igor@yadadya.com"
                    }
                ],
                "auto_text": "true"
            }
        }
    }).done(function (response) {
        console.log(response);
    });
    
});


