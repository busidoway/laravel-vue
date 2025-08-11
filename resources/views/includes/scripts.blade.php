<script src="{{ mix('js/app.js') }}"></script>
<script type="text/javascript"  src="/js/scripts.js?1628417312255228"></script>
<script type="text/javascript"  src="/js/page_d7d8e975aea49906f92618161dd493dc_v1.js?16284173124143"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/jquery.maskedinput.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
<script src="/assets/intlTelInput/js/intlTelInput.min.js"></script>
<script src="/plugins/slick-1.8.1/slick/slick.min.js"></script>
<script>
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl, {
     html: true
  })
})
</script>
<script>
	$(function(){
		var input = document.querySelectorAll(".mask-input");
		var iti_el = document.querySelectorAll('.iti.iti--allow-dropdown.iti--separate-dial-code');
		if(iti_el.length){
			iti.destroy();
		}
		//iti.destroy();
		for(var i = 0; i < input.length; i++){
			iti = window.intlTelInput(input[i], {
				initialCountry: "auto",
				autoHideDialCode: false,
				preferredCountries: ['ru','by'],
				separateDialCode: true,
				geoIpLookup: function(callback) {
					$.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
					var countryCode = (resp && resp.country) ? resp.country : "";
					callback(countryCode);
					});
				},
				utilsScript: "/vendor/utils/utils.js"
			});
		}
	});
</script>
<script type="text/javascript">
	var onloadCallback = function() {
        var mysitekey = '';

        $(document).on('click', '.btn-modal', function(e) {
            let modal = $('.form-modal');
            let card = $(this).closest('.card');
            let card_title = card.find('.header-link').prop('title');
			let card_cat = card.find('.card-header').data('cat');
			let card_price = card.find('.card-price').data('price');
            let card_date = card.find('.event-date').data('date');
			let card_time = card.find('.event-date').data('time');
            let this_id = $(this).data('id');
            let this_program_id = $(this).data('program-id');
			let this_header = $(this).data('header');
			let this_title = $(this).data('title');
			let this_date = $(this).data('date');
			let this_time = $(this).data('time');
			let this_event = $(this).data('event');
			let this_modal_title = $(this).data('modal-title');

			if(card_title){
				modal.find('.form__card-title').html(card_title);
			}else if(this_title){
				modal.find('.form__card-title').html(this_title);
			}
            modal.find('input[name=title]').val(card_title);
			modal.find('input[name=cat]').val(card_cat);
            modal.find('input[name=date]').val(card_date);
			modal.find('input[name=time]').val(card_time);
			modal.find('input[name=price]').val(card_price);

            if(this_id) modal.find('input[name=id]').val(this_id);
            if(this_id) modal.find('input[name=event_id]').val(this_id);
            if(this_program_id) modal.find('input[name=program_edu_id]').val(this_program_id);
			if(this_header) modal.find('input[name=header]').val(this_header);
			if(this_title) modal.find('input[name=title]').val(this_title);
			if(this_date) modal.find('input[name=date]').val(this_date);
			if(this_time) modal.find('input[name=time]').val(this_time);
			if(this_modal_title) modal.find('.modal-header-title').html(this_modal_title);

            modal.children('div').children('.modal-header').remove();
            modal.find('form').show();
            modal.find('form')[0].reset();

			let recaptchaId = $(this).data('recaptcha-id');
			let recaptchaRender = grecaptcha.render(recaptchaId, {
				'sitekey' : mysitekey
			});
			let form_type = "modal";
			sendForm(recaptchaRender, form_type, this_event);
			grecaptcha.reset();
        });

		$('.btn-form-modal').on('click', function(e){
			let recaptchaId = $(this).data('recaptcha-id');
			let recaptchaRender = grecaptcha.render(recaptchaId, {
				'sitekey' : mysitekey
			});
			let form_type = "modal";
			sendForm(recaptchaRender, form_type);
			grecaptcha.reset();
		});

		$('.btn-modal-video').on('click', function(e){
            let modal = $('.form-modal');
            let video_title = $(this).data('title');
            modal.find('input[name=title]').val(video_title);
			let recaptchaId = $(this).data('recaptcha-id');
			let recaptchaRender = grecaptcha.render(recaptchaId, {
				'sitekey' : mysitekey
			});
			let form_type = "modal";
			sendForm(recaptchaRender, form_type);
			grecaptcha.reset();
        });

		if($('.form-static').length > 0){
			// let recaptchaId = $('.form-static').find('.g-recaptcha');
            let recaptchaId = 'recaptcha_form_static';
			let recaptchaRender = grecaptcha.render(recaptchaId, {
				'sitekey' : mysitekey
			});
			sendForm(recaptchaRender);
			grecaptcha.reset();
		}

		// function sendForm(e, t, captcha_id){
		function sendForm(captcha_id, form_type = "static", event = false){
			$(".send_form").on('click', function(e){
				var t = $(this);

				e.preventDefault();

                var captcha = grecaptcha.getResponse(captcha_id);

				var form = t.closest('form');

				var formData = new FormData(form.get(0));

				if(event) formData.append('event', true);

				if (!captcha.length) {
					$('#recaptchaError').html('<div class="text-center mb-3">* Вы не прошли проверку "Я не робот"</div>');
				} else {
					$('#recaptchaError').html('');
				}

				if (captcha.length) {

					// msg = msg + '&g-recaptcha-response=' + captcha;

					formData.append('g-recaptcha-response', captcha);

					$.ajax({
						type: 'POST',
						url: '/api/feedback',
						contentType: false,
						processData: false,
						data: formData,
						dataType: 'json',
						beforeSend: function(xhr){
							t.find('.spinner-border').css({'display': 'inline-block'});
						},
						success: function(data) {

							t.find('.spinner-border').hide();

							if(data.captcha == 'success'){

								let mess = '<div class="modal-header justify-content-center border-0"><div class="text-success text-center fs-3 col-12 py-5">Заявка отправлена</div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>';

								let mess_modal = '<div class="modal fade form-modal" id="modalSuccess" aria-hidden="true">'+
												   '<div class="modal-dialog modal-dialog-centered">'+
    											  '<div class="tab-pane modal-content modal-dialog modal_form px-4 py-5">'+
												  '<div class="text-success text-center fs-3 col-12 py-5">Заявка отправлена</div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
												  '</div></div></div>';

								form.find('.form-control').removeClass('border border-danger');
								form.find('.invalid-feedback').hide();

								if(data.status === 'error'){
									if(data.error_input) {
										$('input[name="'+data.error_input+'"]').addClass('border border-danger');
										$('input[name="'+data.error_input+'"]').next('.invalid-feedback').show();
									}
									if(data.error_info){
										let mess_modal_error = '<div class="modal fade form-modal" id="modalSuccess" aria-hidden="true">'+
												   		'<div class="modal-dialog modal-dialog-centered">'+
    											  		'<div class="tab-pane modal-content modal-dialog modal_form px-4 py-5">'+
												  		'<div class="text-danger text-center fs-3 col-12 py-5">Ошибка отправки</div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
												  		'</div></div></div>';
										$('body').append(mess_modal_error);
                                        if(form_type === "static"){
                                            $('.form-static__error').append(data.error_info);
                                        }
									}
                                    if(data.error_text){
                                        $('.text-danger').append('<div class="text-center mb-3">' + data.error_text + '</div>');
                                        if(form_type === "static"){
                                            $('.form-static__error').append(data.error_text);
                                        }
                                    }
								}else if(data.status === 'success'){
									if(form_type == "modal"){
										form.hide();
										form.closest('div').append(mess);
									}else{
										$('body').append(mess_modal);
										var myModal = new bootstrap.Modal(document.getElementById('modalSuccess'), {
											keyboard: false
										})
										myModal.show();
										form[0].reset();
										// grecaptcha.reset();
									}

                                    // отправка данных формы для записи в лог
                                    sendLogApplication(data.data_send);

								}

							}else{

								$('#recaptchaError').text(data.msg);
							}
							grecaptcha.reset();
						},
						error:  function(xhr, str){
							t.find('.spinner-border').hide();

							let mess_modal_error = '<div class="modal fade form-modal" id="modalSuccess" aria-hidden="true">'+
												   '<div class="modal-dialog modal-dialog-centered">'+
    											  '<div class="tab-pane modal-content modal-dialog modal_form px-4 py-5">'+
												  '<div class="text-danger text-center fs-3 col-12 py-5">Ошибка отправки</div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
												  '</div></div></div>';

							$('body').append(mess_modal_error);

							// console.log('Возникла ошибка: ' + xhr.responseCode);
						}
					});

				}

			});
		}

        function sendLogApplication(data) {

            let formData = new FormData();
            const jsonData = JSON.stringify(data);

            formData.append('data', jsonData);

            $.ajax({
                type: 'POST',
                url: '/api/application_store',
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                success: function (data){
                    // console.log(data.resp);
                }
            })
        }

    };
</script>
<script>
	"use strict";
	$(function(){

		$('#form_check').on('submit', function(e){
			e.preventDefault();

			let form = $(this).get(0);

			let thisForm = $(this);

			let formData = new FormData(form);

			// let formPos = $('#form_check_content').position();

			let formPos = document.getElementById('form_check_content').getBoundingClientRect();

			let headerError = '<div class="fs-3 modal-header-title text-danger text-center mb-2 mt-0">Ошибка</div>';

			let headerSuccess = '<div class="fs-3 modal-header-title text-success text-center mb-2 mt-0">Письмо отправлено</div>';

			$('#messModal').css({'top': formPos.top, 'overflow': 'visible' });

			// console.log(formData);

			$.ajax({
				type: 'POST',
				url: '/api/check_payment',
				contentType: false,
				processData: false,
				data: formData,
				dataType: 'json',
				beforeSend: function(xhr){
					thisForm.find('#check_pay_btn').prepend('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
				},
				success: function(data) {

					// console.log(data);

					thisForm.find('#check_pay_btn').find('.spinner-border').remove();

					if(data.error === 1){
						if(data.status == 'validate'){
							let messValidate = '<div class="text-center">Все поля обязательны для заполнения!</div>';
							$('#messModal').find('.modal-body').html(headerError + messValidate);
						}else if(data.status == 'not_found'){
							let messNotFound = '<div class="">'+ data.full_name +' нет в базе, проверьте корректность указанных Вами данных или свяжитесь с нами по телефону <a href="tel:+7 925 230-15-15" class="">+7 925 230-15-15</a></div>';
							$('#messModal').find('.modal-body').html(headerError + messNotFound);
						}else{
							let messError = '<div class="">Возникла ошибка. Пожалуйста, попробуйте позднее или свяжитесь с нами по телефону <a href="tel:+7 925 230-15-15" class="">+7 925 230-15-15</a></div>';
							$('#messModal').find('.modal-body').html(headerError + messError);
						}
					}else{
						if(data.status == 'success'){
							let messSuccess = '<div class="">На вашу почту ' + data.email + ' отправлено письмо с информацией об уплате взносов. В случае смены Вами почты, просим связаться по телефону <a href="tel:+7 925 230-15-15" class="">+7 925 230-15-15</a></div>';
							$('#messModal').find('.modal-body').html(headerSuccess + messSuccess);
						}
					}

					let myModal = new bootstrap.Modal(document.getElementById('messModal'), {
						keyboard: false
					})
					myModal.show();
				},
				error:  function(xhr, str){
					console.log(xhr, str);
					let messError = '<div class="text-fpnk">Возникла ошибка. Пожалуйста, попробуйте позднее или свяжитесь с нами по телефону <a href="tel:+7 925 230-15-15" class="text-fpnk">+7 925 230-15-15</a></div>';
					$('#messModal').find('.modal-body').html(messError);
				}
			})

		})

	});
</script>
<script>
	"use strict";
	$(function(){
		var max_h = [];
		let size = 3;
		let events = $('.events').find('.card-body-person');
		let subarray = [];

		for (let i = 0; i <Math.ceil(events.length/size); i++){
			subarray[i] = events.slice((i*size), (i*size) + size);
		}

		// console.log(subarray);

		for(let i=0; i < subarray.length; i++){
			max_h[i] = 0;
			subarray[i].each(function(){
				let this_h = $(this).outerHeight();
				if(this_h > max_h[i]){
					max_h[i] = this_h;
				}
			});
			subarray[i].each(function(){
				let this_h = $(this).outerHeight();
				let this_pb = 0;
				this_pb = max_h[i] - this_h;
				$(this).css({'padding-bottom':this_pb});
			});
		}

	})
</script>
<script>
    $(function(){
        $(".mask-input").mask("(999) 999-9999");
    });
</script>
<script src="/js/datepicker.min.js"></script>
<script>
	$(function(){
	   var btn_up = $('.scroll-up');
	   $(window).scroll (function () {
			if ($(this).scrollTop () > 300) {
			   btn_up.fadeIn();
			} else {
			   btn_up.fadeOut();
			}
	   });
	   btn_up.on('click', function(e){
		  e.preventDefault();
		  $('html, body').animate({
			 scrollTop: 0
		  }, 800);
		  return false;
	   });
	})
</script>
<script>
$(function(){
	$('a.scrollto').on('click', function() {

		let href = $(this).attr('href');

		$('html, body').animate({
			scrollTop: $(href).offset().top
		}, {
			duration: 370
		});

		return false;
	});
});
</script>
<script>
	$(function(){
		const card_header = $('.cat-code-stazhirovka, .cat-code-online-course').find('.card').find('.card-header');
        const flash_text = $('.flash-text');
		text_flash(card_header);
        text_flash(flash_text);

		function text_flash(elem) {
			if(elem.length != 0){
				setInterval(() => elem.toggleClass('active'), 1000);
			}
		}
	})
</script>
<script>
	$(function(){
        let $sideElement = $('.events-side');
        let sideOffTop = 0;
		let eventInnerHeight = $('.event-inner-content').height();
		let eventSideContHeight = $('.events-side-content').height();
        let $eventInnerContent = $('.event-inner-content');
		let eventInnerOffset = 0;

        if ($sideElement.length) {
            sideOffTop = $sideElement.offset().top;
        }

        if ($eventInnerContent.length) {
            eventInnerOffset = $eventInnerContent.offset().top + eventInnerHeight - eventSideContHeight + 30;
        }

		if(eventInnerHeight > eventSideContHeight){
			$(window).scroll(function(){
				let winScrollTop = $(window).scrollTop() + 15;

				if(winScrollTop > sideOffTop && winScrollTop < eventInnerOffset){
					$('.events-side').removeClass('stick');
					$('.events-side').addClass('fixed');
				}else if(winScrollTop > eventInnerOffset){
					$('.events-side').removeClass('fixed');
					$('.events-side').addClass('stick');
				}else if(winScrollTop < sideOffTop && winScrollTop < eventInnerOffset){
					$('.events-side').removeClass('stick');
					$('.events-side').removeClass('fixed');
				}
			})
		}
	});
</script>

<script>
    $(document).ready(function () {
        $('.reviews-slider__content').slick({
            autoplay: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: true,
            prevArrow: '<div class="slick-prev slick-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#d5d5d5" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"></path></svg></div>',
            nextArrow: '<div class="slick-next slick-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#d5d5d5" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path></svg></div>',
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        arrows: false,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        slidesToShow: 1
                    }
                },
            ]
        });
    })
</script>
