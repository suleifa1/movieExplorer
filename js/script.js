const signInBtnLink = document.querySelector('.signInBtn-link');
const signUpBtnLink = document.querySelector('.signUpBtn-link');

const wrapper = document.querySelector('.wrapper');
const wrapper_registration = document.querySelector('.wrapper-registration');

if (wrapper === null ){
    signUpBtnLink.addEventListener('click',() => {
        wrapper_registration.classList.toggle('active');
    });
    signInBtnLink.addEventListener('click',() => {
        wrapper_registration.classList.toggle('active');
    });
}else{
    signUpBtnLink.addEventListener('click',() => {
        wrapper.classList.toggle('active');
    });
    signInBtnLink.addEventListener('click',() => {
        wrapper.classList.toggle('active');
    });
}

