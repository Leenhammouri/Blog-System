const emailErrorLogin = document.getElementById('emailError');
const passwordErrorLogin = document.getElementById('passwordError');
const generalErrorLogin = document.getElementById('generalError');
const passwordMatchError = document.getElementById('passwordMatchError');
const userAlreadyError = document.getElementById('userAlreadyError');

const emailLogin = document.getElementsByName('email')[0];
const passwordLogin = document.getElementsByName('password')[0];
const emailSignup = document.getElementById('email-signup');
const passwordSignup = document.getElementById('password-signup');

emailLogin.addEventListener('focus', () => {
	emailErrorLogin.innerText = '';
	passwordErrorLogin.innerText = '';
	generalErrorLogin.innerText = '';
})

passwordLogin.addEventListener('focus', () => {
	emailErrorLogin.innerText = '';
	passwordErrorLogin.innerText = '';
	generalErrorLogin.innerText = '';
})

emailSignup.addEventListener('focus', () => {
	userAlreadyError.innerText = '';
})

passwordSignup.addEventListener('focus', () => {
	passwordMatchError.innerText = '';
})






	(function ($) {

		"use strict";


	})(jQuery);

