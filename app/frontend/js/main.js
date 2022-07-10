$(document).ready(function () {
	$("#datepicker-departure-filter").datepicker({
		dateFormat: "yy-mm-dd",
		showButtonPanel: true
	});

	$("#datepicker-arrival-filter").datepicker({
		dateFormat: "yy-mm-dd",
		showButtonPanel: true
	});

	$("#departure-time-datepicker").datepicker({
		dateFormat: "yy-mm-dd",
		showButtonPanel: true
	});

	$.ajax({
		url: '/listCouriers.php',
		method: 'get',
		dataType: 'json',
		success: function (data) {
			console.log("successfully get couriers");
			couriersHtml = data.map(function (val) {
				return `<option value="${val.id_courier}">${val.courier_name}</option>`
			})
			couriersHtml.forEach(element => {
				$("#courier-select").append(element)
			});
		}
	});

	$.ajax({
		url: '/listRegions.php',
		method: 'get',
		dataType: 'json',
		success: function (data) {
			console.log("successfully get regions");
			regionsHtml = data.map(function (val) {
				regionsMap[val.id_region] = val.time
				return `<option value="${val.id_region}">${val.region}</option>`
			})
			regionsHtml.forEach(element => {
				$("#region-select").append(element)
			});
		}
	});

	var departureDay = null;
	var arrivalDay = null;
	var regionsMap = {};
	var table = new Tabulator("#races-table", {
		height: 205,
		layout: "fitColumns",
		ajaxURL: "/listRaces.php",
		columns: [
			{ title: "Регион", field: "region", width: 150 },
			{
				title: "Дата выезда из Москвы", field: "departure_time", hozAlign: "left", sorter: "datetime", sorterParams: {
					format: "yyyy-MM-dd HH:mm:ss",
					alignEmptyValues: "top",
				}
			},
			{ title: "ФИО курьера", field: "courier_name" },
			{
				title: "Дата прибытия в регион", field: "arrival_time", hozAlign: "left", sorter: "datetime", sorterParams: {
					format: "yyyy-MM-dd HH:mm:ss",
					alignEmptyValues: "top",
				}
			},
		],
	});


	$("#datepicker-departure-filter").on("change", function (event) {
		departureDay = event.target.value
		console.log(`departure filter changed: arrivalDay = ${arrivalDay}, departureDay = ${departureDay}`);
		table.replaceData("/listRaces.php", { departure: departureDay, arrival: arrivalDay })
	})

	$("#datepicker-arrival-filter").on("change", function (event) {
		arrivalDay = event.target.value
		console.log(`arrival filter changed: arrivalDay = ${arrivalDay}, departureDay = ${departureDay}`);
		table.replaceData("/listRaces.php", { departure: departureDay, arrival: arrivalDay })
	})


	$("#region-select").on("change", function (event) {
		if ($("#departure-time-datepicker").val()) {
			start = luxon.DateTime.fromFormat($("#departure-time-datepicker").val(), "yyyy-MM-dd")
			dur = getDurationObject(regionsMap[event.target.value])
			calculated = start.plus(dur).toFormat("yyyy-MM-dd HH:mm:ss")
			$("#calculated-arrival-time").val(calculated)
		}
	})
	$("#departure-time-datepicker").on("change", function (event) {
		if (event.target.value == "") {
			$("#calculated-arrival-time").val(null)
			return
		}
		start = luxon.DateTime.fromFormat(event.target.value, "yyyy-MM-dd")
		dur = getDurationObject(regionsMap[$("#region-select").val()])
		calculated = start.plus(dur).toFormat("yyyy-MM-dd HH:mm:ss")
		$("#calculated-arrival-time").val(calculated)
	})

	// create new race
	$("#create-race").on("click", function (event) {
		if ($("#departure-time-datepicker").val() == "" || $("#calculated-arrival-time").val() == "") {
			showError("Все поля должны быть заполнены")
			return
		}
		departureTime = luxon.DateTime.fromFormat($("#departure-time-datepicker").val(), "yyyy-MM-dd").toFormat("yyyy-MM-dd HH:mm:ss")
		arrivalTime = luxon.DateTime.fromFormat($("#calculated-arrival-time").val(), "yyyy-MM-dd HH:mm:ss").toFormat("yyyy-MM-dd HH:mm:ss")
		regionID = $("#region-select").val()
		courierID = $("#courier-select").val()
		$.ajax({
			url: '/createRace.php',
			method: 'post',
			dataType: 'json',
			data: { "courier_id": courierID, "region_id": regionID, "departure_time": departureTime, "arrival_time": arrivalTime },
			success: function (data) {
				table.addData([{
					id: data.id_race,
					departure_time: data.departure_time,
					region: data.region,
					courier_name: data.courier_name,
					arrival_time: data.arrival_time,
				}], true);
				console.log("successfully created new race: ", data)
				showSuccess("Рейс успешно создан")
			},
			error: function (error) {
				console.log(error)
				if (error.responseJSON.code == "ERR_RANGE") {
					showError("Курьер может быть одновременно только в одной поездке")
				}
			}
		});
	})

	function getDurationObject(dur) {
		const regex = /^(\d{2}):(\d{2}):(\d{2})$/;
		var result = dur.match(regex)
		hours = result[1];
		minutes = result[2];
		seconds = result[3];
		return { hours: hours, minutes: minutes, seconds: seconds }
	}

	async function showError(msg) {
		$("#status").css({ color: "red" })
		$("#status").text(msg)
		await new Promise(resolve => setTimeout(resolve, 3000));
		$("#status").text(null)
	}

	async function showSuccess(msg) {
		$("#status").css({ color: "green" })
		$("#status").text(msg)
		await new Promise(resolve => setTimeout(resolve, 3000));
		$("#status").text(null)
	}



});
