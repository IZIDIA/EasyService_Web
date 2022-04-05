require('./bootstrap');
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
});

//import Alpine from 'alpinejs';
//window.Alpine = Alpine;
//Alpine.start();

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
	var hak = document.getElementById(hak);
	var cat = document.getElementById(cat);
	if (hak.checked) {
		cat.style = null;
	} else {
		cat.style.visibility = "hidden";
	}
}
