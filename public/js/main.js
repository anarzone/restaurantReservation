let hall_id = null;

$(document).ready(function(){
    $('input[type=radio][name=radio_inp]').on('change', function () {
        console.log($(this).val())
        let rest_id = $(this).val()

        $.ajax({
            type: 'GET',
            url: '/getHallsByRestId/' + rest_id,
            dataType: "json",
            success: function (result) {
                if(result.data){
                    $('.content-custom-select').empty().focus();
                    $.each(result.data, function(key, val){
                        let html = `
                            <div class="option" id="${val.id}">
                                <p class="text">${val.name}</p>
                                <input type="radio" hidden name="custom_selected" value="${val.id}" id="${val.id}">
                            </div>
                        `
                        $('.content-custom-select').append(html);
                        customSelected();
                    });
                }else{
                    $('.content-custom-select').empty();
                }
            }
        })

        $('.radios-container label').each(function(){
            $(this).find('input').removeAttr('checked');
            $(this).removeClass('active');
        });
        $(this).addClass('active');
        $(this).find('input').attr('checked', 'checked');
    });



   function customSelected(){
       $('.content-custom-select .option').click(function(){
           hall_id = $(this).attr('id')

           $(this).parent().find('.option').each(function(){
               $(this).removeClass('active');
               $(this).find('input').removeAttr('checked');
           });
           $(this).addClass('active');
           $(this).find('input').attr('checked', 'checked');
           let value = $(this).find('.text').text();
           $(this).parentsUntil('.custom-select-item').parent().find('.select-btn .title').html(value);
           $('.content-custom-select').removeClass('active');
       });
   }

    customSelected();
    $('.select-btn').click(function(){
        $('.content-custom-select').removeClass('active');
        $(this).parents('.custom-select-item').find('.content-custom-select').addClass('active');
    });

    $(document).click(function(event){
        let length = $(event.target).parents('.custom-select-item').length;
        if(length < 1){
            $('.content-custom-select').removeClass('active');
        }
        else{}
    });

});
$(function(){
    $('.t-datepicker').tDatePicker({

        // auto close after selection
        autoClose        : true,

        // animation speed in milliseconds
        durationArrowTop : 200,

        // the number of calendars
        numCalendar    : 2,


        // localization
        titleCheckIn   : 'Check In',
        titleCheckOut  : 'Check Out',
        titleToday     : 'Today',
        titleDateRange : '',
        titleDateRanges: '',
        titleDays      : [ 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su' ],
        titleMonths    : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'Septemper', 'October', 'November', "December"],

        // the max length of the title
        titleMonthsLimitShow : 3,

        // replace moth names
        replaceTitleMonths : null,

        // e.g. 'dd-mm-yy'
        showDateTheme   : null,

        // icon options
        iconArrowTop : false,
        iconDate     : '&#x279C;',
        arrowPrev    : '&#x276E;',
        arrowNext    : '&#x276F;',
        // https://fontawesome.com/v4.7.0/icons/
        // iconDate: '<i class="li-calendar-empty"></i><i class="li-arrow-right"></i>',
        // arrowPrev: '<i class="fa fa-chevron-left"></i>',
        // arrowNext: '<i class="fa fa-chevron-right"></i>',

        // shows today title
        toDayShowTitle       : true,

        // showss dange range title
        dateRangesShowTitle  : true,

        // highlights today
        toDayHighlighted     : false,

        // highlights next day
        nextDayHighlighted   : false,

        // an array of days
        daysOfWeekHighlighted: [0,6],

        // custom date format
        formatDate: 'dd/mm/yy',

        // dateCheckIn: '25/06/2018',  // DD/MM/YY
        // dateCheckOut: '26/06/2018', // DD/MM/YY
        dateCheckIn  : new Date(),
        dateCheckOut : null,
        startDate    : null,
        endDate      : null,

        // limits the number of months
        limitPrevMonth : 0,
        limitNextMonth : 11,

        // limits the number of days
        limitDateRanges    : 31,

        // true -> full days || false - 1 day
        showFullDateRanges : false,

        // DATA HOLIDAYS
        // Data holidays
        fnDataEvent   : null

      });
});


function validationForm(thisIs, payload){
    if(payload == 1){
        let length = $(thisIs).val().length;
        if(length < 3){
            $(thisIs).parent().addClass('validation_error');
        }
        else{
            $(thisIs).parent().removeClass('validation_error');
        }
    }
    else if(payload == 2){
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test($(thisIs).val())){
            $(thisIs).parent().addClass('validation_error');
        }
        else{
            $(thisIs).parent().removeClass('validation_error');
        }
    }
    else if(payload == 3){
        let length = parseInt($(thisIs).val().toString().length);
        console.log(length)
        if(length === 14 || length === 15){
            $(thisIs).parent().removeClass('validation_error');
        }
        else{
            $(thisIs).parent().addClass('validation_error');
        }
    }
    else if(payload === 4){
        let length = $(thisIs).val().toString().length;
        if(length <= 0){
            $(thisIs).parent().addClass('validation_error');
        }
        else{
            $(thisIs).parent().removeClass('validation_error');
        }
    }
}

function numberControl(event){
    if (event.which === 69) {
       event.preventDefault();
    }
    else{}
}



$('.submit-btn').click(function(){
    let component = ' <div class="success-menu"> <div class="center-logo"> <img src="/public/images/logo.svg" alt=""> </div><div class="success-image"> <img src="./images/success.svg" alt=""> </div><div class="success-text"> <p>SİZİN REZERVASİYANIZ <br>QƏBUL OLUNDU</p></div><div class="success-text-bottom"> <p>Sizin ilə yaxın zaman əməkdaşımız əlaqə saxlayacaq</p></div></div>';
    $('.input-box input').each(function(){
        $(this).blur();
    });
    let length = $('.validation_error').length;
    if(length < 1){
        let firstname = $('[name=firstname]').val()
        let lastname = $('[name=lastname]').val()
        let country_code = $('.country-box').find('span').html()
        let phone = $('[name=phone]').val()
        let people = $('[name=people]').val()
        let note = $('[name=note]').val()
        let restaurant_id = $('[name=radio_inp]:checked').val()
        let reservation_time = $('[name=reservation_date]').val()
        let reservation_date = $('[name=t-start]').val()

        $.ajax({
            type: 'POST',
            url:  '/send_form',
            data: {
                firstname,
                lastname,
                country_code,
                phone,
                people,
                note,
                restaurant_id,
                hall_id,
                reservation_time,
                reservation_date
            },
            success: function (response) {
                if ($.trim(response.message) === 'success'){
                    $('.form-box').addClass('flow')
                    $('.form-center, .submit-btn').addClass('left-animate-item');
                    $('.success-section').append(component).show(0);
                    $('.success-section').addClass('active');
                    $('.back-btn').show(1000);
                }
            }
        })



    }
    else{
        let elmnt = document.querySelector(".validation_error").closest('.form-section');
        elmnt.scrollIntoView({behavior: "smooth"});
    }
});

let hours_count = 0;
let minute_count = 0;

function minusTime(type, payload){
    const now = moment();
    let editorValue = $('.t-datepicker').tDatePicker('getDateInput');
    let day = now.format("D") < 10 ? "0" +now.format('D') :now.format('D');
    let month = now.format("M") < 10 ? "0" +now.format('M') :now.format('M');
    let createTime = day + "-" + month + "-" + now.format("yyyy");

    let hours = +$('.text-hours').html();
    let minute = +$('.text-minute').html();
    const moment_new = moment();

    if(type == 1 && payload == 1){
         if(hours_count < 23){
            hours_count++;
            $('.text-hours').text(hours_count < 10 ? "0" + hours_count: hours_count)
         }
         else{}
    }
    else if(type == 1 && payload == 2){
        if(hours_count > 0 && editorValue == createTime && hours > +moment_new.format('HH') ){
            hours_count--;
            $('.text-hours').text(hours_count < 10 ? "0" + hours_count: hours_count);
       }
       else if(hours_count > 0 && editorValue != createTime){
            hours_count--;
           $('.text-hours').text(hours_count < 10 ? "0" + hours_count: hours_count);
        }
        else{}
    }
    else{}

  if(type == 2 && payload == 1){
        if(minute_count < 59){
            minute_count++;
           $('.text-minute').text(minute_count < 10 ? "0" + minute_count: minute_count)
        }
        else{}
   }
   else if(type == 2 && payload == 2){
       if(minute_count > 0 && editorValue == createTime && hours == +moment_new.format('HH') &&  minute > +moment_new.format('mm')){
            minute_count--;
            $('.text-minute').text(minute_count < 10 ? "0" + minute_count: minute_count);
       }
       else if (minute_count > 0 && editorValue == createTime && hours > +moment_new.format('HH')){
        minute_count--;
        $('.text-minute').text(minute_count < 10 ? "0" + minute_count: minute_count);
       }
       else if(minute_count > 0 && editorValue != createTime){
           minute_count--;
           $('.text-minute').text(minute_count < 10 ? "0" + minute_count: minute_count);
        }
        else{}
   }
   else{}

   let hours_value = hours_count < 10 ? "0" + hours_count: hours_count;
   let minute_value = minute_count < 10 ? "0" + minute_count: minute_count


   $('.value-clock').html(
     hours_value + " : " + minute_value
   )

   $('.clock-value-inp').attr('value', hours_value + " : " + minute_value);
}

$(document).ready(function(){
    const now = moment();
    $('.text-hours').html(
        now.format("HH")
    );
    $('.text-minute').html(
        now.format("mm")
    );
    hours_count = +$('.text-hours').text();
    minute_count = +$('.text-minute').text();

  let hours_value = hours_count < 10 ? "0" + hours_count: hours_count;
  let minute_value = minute_count < 10 ? "0" + minute_count: minute_count


   $('.value-clock').html(
    hours_value + " : " + minute_value
   );

   $('.clock-value-inp').attr('value', hours_value + " : " + minute_value);

});


$('.custom-date-picker').click(function(){
    $(this).addClass('active');
});

$(document).click(function(event){
    let length = $(event.target).parents('.box-data-clock').length;
    if(length < 1){
        $('.custom-date-picker').removeClass('active');
    }
    else{}
});


$('#ssn').keyup(function(e) {
    let getValue = $(this).val();
    if(getValue.length < 12){
        if(getValue.length == 2){
            $(this).val($(this).val() + "-");
        }
        else if(getValue.length == 6){
            $(this).val($(this).val() + "-");
        }
        else if(getValue.length == 9){
            $(this).val($(this).val() + "-");
        }
    }
    else if(getValue.length > 12 ){
        let inp = $(this).val()
        $(this).val(inp.substring(0,12));
    }
});

$('#ssn').keydown(function(e){
    if(isNaN(e.key) && e.keyCode != 8){
        e.preventDefault();
    }
    else{}
});
