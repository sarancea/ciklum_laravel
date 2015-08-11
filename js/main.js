var signs = [];
(function () {
    $.get('./server.php', function (result) {
        signs[result.playerId] = 'X';
        signs[result.opponentId] = 'O';

        drawMatrix(result.ySize, result.xSize);
        updateMatrixData(result.currentMatrix, result.isFinished);
        if (result.isFinished)
            processFinishedGame(result);

    }, 'json');
    $('#reload').hide();
})();


function drawMatrix(ySize, xSize) {
    $('#matrix').empty();
    $('#matrix').append($('<table id="matrix-table"/>'));

    for (var y = 0; y < ySize; y++) {
        $('#matrix-table').append($('<tr id="row_' + y + '">'));

        for (var x = 0; x < xSize; x++) {
            $('#row_' + y).append($('<td id="col_' + y + '_' + x + '">&nbsp</td>'))
        }
    }
}

function updateMatrixData(data, finished) {

    for (var y  in data) {
        if (!data.hasOwnProperty(y)) {
            continue;
        }
        for (var x in data[y]) {

            if (!data[y].hasOwnProperty(x)) {
                continue;
            }
            $('#col_' + y + '_' + x).unbind();

            if (data[y][x] == null) {
                if (!finished) {
                    $('#col_' + y + '_' + x).bind('click', function () {
                        $('#col_' + this.y + '_' + this.x).html('X');
                        $.post('./server.php', this, function (response) {
                            updateMatrixData(response.currentMatrix, response.isFinished);
                            if (response.isFinished)
                                processFinishedGame(response);

                        }, 'json');
                    }.bind({x: x, y: y}));
                }
            } else {
                $('#col_' + y + '_' + x).html(signs[data[y][x]])
            }
        }
    }
}

function processFinishedGame(response) {
    if (signs[response.isFinished]) {
        alert('Game is finished "' + signs[response.isFinished] + '" have won!');
    } else {
        alert('Game is finished. ' + response.isFinished + '!');
    }

    //show reload button
    $('#reload').show();
}
