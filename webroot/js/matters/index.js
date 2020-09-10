
$(document).ready(function () {

    $('#receiveMatter').click(function (e) {
        e.preventDefault();

        let formData = {
            case_number: $('input[name="case_number"]').val(),
            court_id: $('select[name="court_id"]').val(),
            court_fed_abbr: $('input[name="court_fed_abbr"]').val(),
            court_type: $('input[name="court_type"]').val()
        };

        if (formData.case_number === '' && formData.court_id === '' || formData.court_fed_abbr === '') {
            alert('Docket court not found.');
            return;
        }

        $('.pre-loader').show();

        apiClient('/api/fetch-docket-court', formData)
            .then(response => {

                if (response.error) {
                    alert(response.error);
                    $('.pre-loader').hide();
                } else {
                    $('.pre-loader').hide();
                    alert('Such a court exists');
                    $('#addCourt').show();
                    $('#receiveMatter').hide();

                    $('input[name=case_number]').val(response[0]['case_number']);
                    $('input[name=court_id]').val(response[0]['court_id']);
                }
            }, error => console.log('error:' + error))
    });
});

async function apiClient(url, data) {

    let result = await
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(data)
        });

    return await
        result.json();

}

// $('.select-beast select').selectize({
//     placeholder: 'Select Court...'
// });

$('.add-court').on('click', function(e){
    e.preventDefault();
    $('.modal').addClass('is-active');
});

$('.modal-close').on('click', function (e) {
    e.preventDefault();
    $('.modal').removeClass('is-active');
})

$('#addCourt').hide()

$('select[name=court_id]').on('change', function() {
    const fed_abbr = $('option:selected', this).attr('fed_abbr');
    const court_type = $('option:selected', this).attr('court_type');
    $('input[name=court_type]').val(court_type);
    $('input[name=court_fed_abbr]').val(fed_abbr);
});
