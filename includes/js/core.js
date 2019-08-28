

function setSearchEndDate() {
	document.getElementById('filterEndDate').value = document.getElementById('filterStartDate').value;
}

function checkAfterStartDate() {
	if ((document.getElementById('filterEndDate').value <= document.getElementById('filterStartDate').value) && document.getElementById('filterEndDate').value != "") {
		document.getElementById('filterEndDate').classList.add('uk-form-danger');
	}
	if ((document.getElementById('filterEndDate').value >= document.getElementById('filterStartDate').value) && document.getElementById('filterEndDate').value != "") {
		document.getElementById('filterEndDate').classList.remove('uk-form-danger');
	}
}

function displayAddPayment() {
	
	UIkit.modal("#modal-addPayment").show();
}

function showAddHouseModal() {
	UIkit.modal('#modal-addHouse').show();
}