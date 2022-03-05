require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

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
		divBlock.style = null;
	} else {
		password.value = '';
		divBlock.style.visibility = "hidden";
	}
}

window.showDataTableFunction = function () {
	var radioYes = document.getElementById("yes_with");
	var radioNo = document.getElementById("no_with");
	var divBlock = document.getElementById("dataDiv");
	if (radioYes.checked == true || radioNo.checked == true) {
		divBlock.style = null;
	} else {
		divBlock.style.visibility = "hidden";
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
