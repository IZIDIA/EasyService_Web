require('./bootstrap');
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

//import Alpine from 'alpinejs';
//window.Alpine = Alpine;
//Alpine.start();

import mediumZoom from 'medium-zoom';

mediumZoom('.zoom-dark', {
	background: '#0000009b',
	margin: 90,
});

(function () {
	'use strict'
	let forms = document.querySelectorAll('.needs-validation')
	Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				form.classList.add('was-validated')
			}, false)
		})
})()


window.showPasswordInputFunction = function () {
	let checkBox = document.getElementById("zCheck");
	let divBlock = document.getElementById("zDiv");
	let password = document.getElementById("user_password");
	if (checkBox.checked == true) {
		password.value = '';
		divBlock.style.display = "block";
	} else {
		password.value = '';
		divBlock.style.display = "none";
	}
}

window.showDataTableFunction = function () {
	let radioYes = document.getElementById("yes_with");
	let radioNo = document.getElementById("no_with");
	let divBlock = document.getElementById("dataDiv");
	if (radioYes.checked == true || radioNo.checked == true) {
		divBlock.style.display = "block";
	} else {
		divBlock.style.display = "none";
	}
}

window.showOrHide = function (hak, cat) {
	let hak = document.getElementById(hak);
	let cat = document.getElementById(cat);
	if (hak.checked) {
		cat.style = null;
	} else {
		cat.style.visibility = "hidden";
	}
}

window.enableAnonym = function () {
	let anonym = document.getElementById("anonym");
	let first_name_div = document.getElementById("first_name_div");
	let last_name_div = document.getElementById("last_name_div");
	let email_div = document.getElementById("email_div");
	let phone_call_number_div = document.getElementById("phone_call_number_div");
	let work_time_div = document.getElementById("work_time_div");
	let work_time_hr = document.getElementById("work_time_hr");
	let dataDiv = document.getElementById("dataDiv");
	let first_name = document.getElementById("first_name");
	let last_name = document.getElementById("last_name");
	let email = document.getElementById("email");
	let phone_call_number = document.getElementById("phone_call_number");
	if (anonym.checked) {
		first_name.required = false;
		last_name.required = false;
		email.required = false;
		phone_call_number.required = false;
		first_name_div.style.display = "none";
		last_name_div.style.display = "none";
		email_div.style.display = "none";
		phone_call_number_div.style.display = "none";
		work_time_div.style.display = "none";
		work_time_hr.style.display = "none";
		dataDiv.style.display = "none";
	} else {
		first_name.required = true;
		last_name.required = true;
		email.required = true;
		phone_call_number.required = true;
		first_name_div.style.display = "block";
		last_name_div.style.display = "block";
		email_div.style.display = "block";
		phone_call_number_div.style.display = "block";
		work_time_div.style.display = "block";
		work_time_hr.style.display = "block";
		showDataTableFunction();
	}
}
