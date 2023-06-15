const html = document.getElementById('body');
var xhr = new XMLHttpRequest();

function toggletGenero(element) {
	console.log(element.checked);
	let operation = element.getAttribute('action');

	$.ajax({
		url: '/gamesphere/ajax/genero.php',
		method: 'POST',
		dataType: 'json',
		data: {
			genID: element.getAttribute('genero'),
			gameID: element.getAttribute('game'),
			action: operation,
		},
		beforeSend: function () {
			$('#loadingModal').modal('show');
			html.style.overflowY = 'hidden';
			console.log('Enviando requisição...');
			console.log('data: ', this.data);
		},
		success: function (response) {
			console.log('Requisição bem-sucedida');
			console.log(response);
			if (operation == 'inc')
				element.setAttribute('action', 'rem');
			else
				element.setAttribute('action', 'inc');
		},
		error: function (xhr, response, status, error) {
			console.error('Erro na requisição');
			console.error('Status:', status);
			console.error('Erro:', error);
			console.error('Response', response);
		},
		complete: function () {
			console.log('Requisição concluída');
			$('#loadingModal').modal('hide');
			html.style.overflowY = 'auto';
		}
	});
}

function toggleConsole(element) {
	console.log(element.checked);
	let operation = element.getAttribute('action');

	$.ajax({
		url: '/gamesphere/ajax/console.php',
		method: 'POST',
		dataType: 'json',
		data: {
			genID: element.getAttribute('console'),
			gameID: element.getAttribute('game'),
			action: operation,
		},
		beforeSend: function () {
			// modal.style.display = 'initial';
			$('#loadingModal').modal('show');
			html.style.overflowY = 'hidden';
			console.log('Enviando requisição...');
			console.log('data: ', this.data);
		},
		success: function (response) {
			console.log('Requisição bem-sucedida');
			console.log(response);
			if (operation == 'inc')
				element.setAttribute('action', 'rem');
			else
				element.setAttribute('action', 'inc');
		},
		error: function (xhr, response, status, error) {
			console.error('Erro na requisição');
			console.error('Status:', status);
			console.error('Erro:', error);
			console.error('Response', response);
		},
		complete: function () {
			console.log('Requisição concluída');
			$('#loadingModal').modal('hide');
			html.style.overflowY = 'auto';
		}
	});
}