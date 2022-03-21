require('./bootstrap');

import Alpine from 'alpinejs';
import mediumZoom from 'medium-zoom';

window.Alpine = Alpine;

Alpine.start();

mediumZoom('.zoom-dark', {
	background: '#0000009b',
	margin: 90,
});

	(function () {
		'use strict'
		var forms = document.querySelectorAll('.needs-validation')
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
	var checkBox = document.getElementById("zCheck");
	var divBlock = document.getElementById("zDiv");
	var password = document.getElementById("user_password");
	if (checkBox.checked == true) {
		password.value = '';
		divBlock.style.display = "block";
	} else {
		password.value = '';
		divBlock.style.display = "none";
	}
}

window.showDataTableFunction = function () {
	var radioYes = document.getElementById("yes_with");
	var radioNo = document.getElementById("no_with");
	var divBlock = document.getElementById("dataDiv");
	if (radioYes.checked == true || radioNo.checked == true) {
		divBlock.style.display = "block";
	} else {
		divBlock.style.display = "none";
	}
}

window.showOrHide = function (hak, cat) {
	var hak = document.getElementById(hak);
	var cat = document.getElementById(cat);
	if (hak.checked) {
		cat.style = null;
	} else {
		cat.style.visibility = "hidden";
	}
}

window.enableAnonym = function () {
	var anonym = document.getElementById("anonym");
	var first_name_div = document.getElementById("first_name_div");
	var last_name_div = document.getElementById("last_name_div");
	var email_div = document.getElementById("email_div");
	var phone_call_number_div = document.getElementById("phone_call_number_div");
	var work_time_div = document.getElementById("work_time_div");
	var work_time_hr = document.getElementById("work_time_hr");
	var dataDiv = document.getElementById("dataDiv");
	var first_name = document.getElementById("first_name");
	var last_name = document.getElementById("last_name");
	var email = document.getElementById("email");
	var phone_call_number = document.getElementById("phone_call_number");
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
