let exit_icon=document.querySelector(".category i");
let whole_form=document.querySelector(".add_category_main ");
exit_icon.addEventListener("click",function(){
    whole_form.style.display = "none";
})

let add_category=document.querySelector(".content input");
add_category.addEventListener("click",function(){
    whole_form.style.display = "flex";
    whole_form.style.height = document.documentElement.scrollHeight+"px";
})


$(function(){
let error_messages = $(".error-message");
if(error_messages.text())
    $(error_messages).fadeIn().delay(2000).fadeOut();
})