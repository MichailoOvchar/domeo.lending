const { result } = require("lodash");

let aElem = document.getElementsByTagName('a');
let modalObj = document.getElementById('modal');
let closeModal = document.getElementsByClassName('close')[0];

for(let i=0; i < aElem.length; i++){
    if(aElem[i].matches('a[href="#modal"]')){
        aElem[i].addEventListener('click', (e)=>{
            e.preventDefault();
            modalObj.style.top = '0';
            document.getElementsByTagName("body")[0].style.overflow = 'hidden';
        });
    }
}

closeModal.addEventListener('click', ()=>{
    modalObj.style.top = '-200vh';
    document.getElementsByTagName("body")[0].style.overflow = 'visible';
});
modalObj.addEventListener('click', ()=>{
    modalObj.style.top = '-200vh';
    document.getElementsByTagName("body")[0].style.overflow = 'visible';
});

$(".anchore").on("click", function(){
    if(window.screen.availWidth <= 768){
        $(".navbar-toggler").attr("aria-expanded", "false");
        $(".navbar-collapse").attr("class", "navbar-collapse collapse");
    }
});

document.querySelector('#modal > div > div > div.col-12.col-sm-12.col-md-12.col-lg-7.col-xl-7 > div')
.addEventListener('click', (e)=>{
    e.stopPropagation();
});

if(window.screen.availWidth <= 768){
    $(".owl-carousel").owlCarousel({
        items: 1,
        center: true,
        loop: true,
        nav: true,
        lazyLoad: true,
        autoplaySpeed: 1500,
        autoplayHoverPause: true,
        dots: false
    }).css("margin-left", "0");

    $(window).on('scroll', function(){
        let pos = $(this).scrollTop();
        let elem = $('.owl-carousel').position();
        if(pos >= (elem.top - $(this).height())){
            $(".owl-carousel").trigger('play.owl.autoplay', 3000);
        }
    });
}

$(document).ready(function(){
    $('input[type="tel"]').inputmask("+7 (999)999-99-99");
});

// let forms = document.getElementsByTagName("form");
// for (let index = 0; index < forms.length; index++) {
//     forms[index].addEventListener('submit', (e)=>{
//         e.preventDefault();
//         window.location.href = window.location.href + "/thanks-page.html";
//     });    
// }

var verifNumber = true;

$("form").on("submit", function(){ 
    let url = $(this).attr("action");
    $this = $(this);
    let data = $(this).serialize();

    if($this.find('input[type=tel]').val().indexOf('_') > -1){
        $this.find('input[type=tel]').addClass('error');
        alert('Некоректный номер телефона');
        return false;
    }

    if($this.find('input[name="name"]').val() == ""  || $this.find('input[name="name"]').val() == null){
        $this.find('input[name="name"]').addClass('error');
        alert('Некоректное имя или фамилия пользователя');
        return false;
    }

    const reg = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!reg.test($this.find('input[type=email]').val())){
        $this.find('input[type=email]').addClass('error');
        alert('Некоректный email пользователя');
        return false;
    }

    let ageValue = +$this.find('input[type=number]').val();
    if(ageValue < 10 || ageValue > 80){
        $this.find('input[type=number]').addClass('error');
        alert('Некоректное возраст пользователя');
        return false;
    }

    if(verifNumber === true){

            $.ajax({
                url: "/ajax/verifys_sms.php",
                type: "post",
                dataType: "html",
                data: data,
                success: function(response){
                    results = $.parseJSON(response);
                    $this.find('button').css('pointer-events', 'none');
                    if(results.status === "ACTIVE"){
                        verifNumber = false;
                        $this.trigger('submit');
                    }
                    else{
                        $this.find('input[type=tel]').addClass('error');
                        if($('div').is('.temp') == false){
                            $this.find('input[type=tel]').closest('.form-50').after('<div class="form-100 temp" style="text-align: left; font-size: 12px"><span style="display: inline-block; width: 50%;color: red; text-align: left; margin: 0 auto; padding-left: 40px;">Номер телефона недействительный</span></div>');
                        }
                        alert(results.message);
                        $('.load').addClass('hidden');
                        $this.find('button').css('pointer-events', 'unset');
                    }
                },
                error: function(response){
                    toastr.error("Error: "+response);
                    $this.find('button').css('pointer-events', 'unset');
                }
            });
        return false;
    }

    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        data: data,
        success: function(response){
            results = $.parseJSON(response);
            if(results.stat == true || results.status == 1){
                window.location.href = $this.data("thanks");
            }
            else{
                alert(results.description);
                $this.find('button').css('pointer-events', 'unset');
            }
        },
        error: function(response){
            console.warn(response);
        },
    })

    return false;
})