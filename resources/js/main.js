var aElem = document.getElementsByTagName('a');
var modalObj = document.getElementById('modal');

for(var i=0; i < aElem.length; i++){
    if(aElem[i].matches('a[href="#modal"]')){
        aElem[i].addEventListener('click', (e)=>{
            e.preventDefault();
            modalObj.style.top = '0';
        });
    }
}

modalObj.addEventListener('click', ()=>{
    modalObj.style.top = '-200vh';
});

document.querySelector('#modal > div > div > div.col-12.col-sm-12.col-md-12.col-lg-7.col-xl-7 > div')
.addEventListener('click', (e)=>{
    e.stopPropagation();
});

$(document).ready(function(){
    $('input[type="tel"]').inputmask("+7 (999)999-99-99");
});

var forms = document.getElementsByTagName("form");
for (let index = 0; index < forms.length; index++) {
    forms[index].addEventListener('submit', (e)=>{
        e.preventDefault();
        window.location.href = window.location.href + "/thanks-page.html";
    })    
} 

// $("form").on("submit", function(){ 
//     let url = $(this).attr("action");
//     $this = $(this);

//     window.location.href = $this.data("thanks");

//     $.ajax({
//         url: url,
//         type: "post",
//         dataType: "json",
//         success: function(response){
//             window.location.href = $this.data("thanks");
//             if(response.hasOwnProperty("status")){
//                 if(response.status == "success"){
//                     window.location.href = $this.data("thanks");
//                 }
//             }
//             else{
//                 alert("Error!!!");
//             }
//         },
//         error: function(response){
//             console.warn(response);
//         },
//     })

//     return false;
// })