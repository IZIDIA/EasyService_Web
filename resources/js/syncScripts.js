
window.setTimer = function (start, time, id) {
	let yourDateToGo = new Date(start);
	yourDateToGo.setHours(yourDateToGo.getHours() + time);
	function tick() {
		let currentDate = new Date().getTime();
		let timeLeft = yourDateToGo - currentDate;
		let days = Math.floor(timeLeft / (86400000));
		if (days < 10) days = "0" +
			days;
		let hours = Math.floor((timeLeft % (86400000)) / (3600000));
		if (hours < 10) hours = "0" + hours;
		let minutes = Math.floor((timeLeft % (3600000)) / (60000));
		if (minutes < 10) minutes = "0" + minutes;
		let seconds = Math.floor((timeLeft % (60000)) / 1000);
		if (seconds < 10) seconds = "0" + seconds;
		document.getElementById(id).innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
		if (timeLeft <= 0) {
			clearInterval(timing);
			document.getElementById(id).innerHTML =
				"Время истекло...";
		}
	}
	tick();
	let timing = setInterval(tick, 1000);
}

window.charCount = function () {
	let element = document.getElementById('text').value.length;
	document.getElementById('textarea_count').innerHTML = element + "/4000";
}

window.fileValidation = function () {
	let fileInput =
		document.getElementById('file');
	let filePath = fileInput.value;
	let allowedExtensions =
		/(\.jpg|\.jpeg|\.png|\.gif)$/i;
	if (!allowedExtensions.exec(filePath)) {
		alert('Неверный тип файла');
		fileInput.value = '';
		return false;
	} else { }
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
