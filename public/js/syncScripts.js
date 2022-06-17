/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************!*\
  !*** ./resources/js/syncScripts.js ***!
  \*************************************/
window.setTimer = function (start, time, id) {
  var yourDateToGo = new Date(start);
  yourDateToGo.setHours(yourDateToGo.getHours() + time);

  function tick() {
    var currentDate = new Date().getTime();
    var timeLeft = yourDateToGo - currentDate;
    var days = Math.floor(timeLeft / 86400000);
    if (days < 10) days = "0" + days;
    var hours = Math.floor(timeLeft % 86400000 / 3600000);
    if (hours < 10) hours = "0" + hours;
    var minutes = Math.floor(timeLeft % 3600000 / 60000);
    if (minutes < 10) minutes = "0" + minutes;
    var seconds = Math.floor(timeLeft % 60000 / 1000);
    if (seconds < 10) seconds = "0" + seconds;
    document.getElementById(id).innerHTML = "".concat(days, "\u0434 ").concat(hours, "\u0447 ").concat(minutes, "\u043C");

    if (timeLeft <= 0) {
      clearInterval(timing);
      document.getElementById(id).innerHTML = "Время истекло...";
    }
  }

  tick();
  var timing = setInterval(tick, 1000);
};

window.charCount = function () {
  var element = document.getElementById('text').value.length;
  document.getElementById('textarea_count').innerHTML = element + "/4000";
};

window.fileValidation = function () {
  var fileInput = document.getElementById('file');
  var filePath = fileInput.value;
  var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

  if (!allowedExtensions.exec(filePath)) {
    alert('Неверный тип файла');
    fileInput.value = '';
    return false;
  } else {}
};

window.showDataTableFunction = function () {
  var radioYes = document.getElementById("yes_with");
  var radioNo = document.getElementById("no_with");
  var divBlock = document.getElementById("dataDiv");

  if (radioYes.checked == true || radioNo.checked == true) {
    divBlock.style.display = "block";
  } else {
    divBlock.style.display = "none";
  }
};

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
};
/******/ })()
;