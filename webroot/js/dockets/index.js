$(document).ready(function () {

    //fetch remote docket_entries
    $('#getNewDocBtn').click(function(e) {
        e.preventDefault();

        if (getCurrentData() == null) {
            alert('Docket not found.');
            return;
        }

        $('.pre-loader').show();

        if (getCurrentData().courtType !== 'appellate') {
            var formData = {
                documents_numbered_from_: $('input[name="documents_numbered_from_"]').val(),
                documents_numbered_to_: $('input[name="documents_numbered_to_"]').val(),
                ...getCurrentData()
            };
        } else {
            var formData = {
                documents_date_from_: $('input[name="documents_date_from_"]').val(),
                documents_date_to_: $('input[name="documents_date_to_"]').val(),
                ...getCurrentData()
            };
        }

        apiClient('/api/fetch-new-doc', formData)
            .then(response => {

                if (response.error) {
                    alert(response.error);
                    $('.pre-loader').hide();
                    return;
                }

                getDocketsHandler();
            }, error => console.log('error:' + error))
    });

    $('#receiveMatter').click(function(e) {
        e.preventDefault();

        let formData = {
            case_number: $('input[name="case_number"]').val(),
            court_id: $('select[name="court_id"]').val(),
        };

        if (formData.case_number === '' &&  formData.court_id === '') {
            alert('Docket court not found.');
            return;
        }

        $('.pre-loader').show();

        apiClient('/api/fetch-docket-court', formData)
            .then(response => {

                var content = '';

                if (response.error) {
                    alert(response.error);
                    $('.pre-loader').hide();
                } else {
                    $('.showByCaseNumber').hide();
                    $('.showMatter').show();
                    $('.pre-loader').hide();

                    if (formData.court_type !== 'appellate') {
                        content += '<div class="textarea" readonly style="width: 500px; height: 170px; margin-top: 20px">' +
                            '<p style="font-size: 12px; font-weight: bold; padding-bottom: 2px"><i>' + response[0]['case_name'] + '</i></p>' +
                            '<p style="font-size: 15px; padding-bottom: 2px">' + response[0]['court'] + ', ' + response[0]['case_number'] + '</p>' +
                            '<p style="font-size: 12px; margin-bottom: -1px"><b>Filled: </b>' + response[0]['filed'] + '</p>' +
                            '<p style="font-size: 12px; margin-bottom: -1px"><b>Judge: </b>' + response[0]['judge'] + '</p>' +
                            '<p style="font-size: 12px;"><b>Referral: </b>' + response[0]['referal'] + '</p>' +
                            '</div>';

                        var caseNum = response[0]['case_number'].split('-');

                        if (caseNum[0] !== 'undefined' && caseNum[1] !== 'undefined' && caseNum[2] !== 'undefined') {
                            $('input[name=case_number]').val(caseNum[0] + '-' + caseNum[1] + '-' + caseNum[2]);
                        }
                        if (caseNum[4]) {
                            $('input[name=fed_case_number_judges]').val('-' + caseNum[3] + '-' + caseNum[4]);
                        } else if (caseNum[3] !== 'undefined') {
                            $('input[name=fed_case_number_judges]').val('-' + caseNum[3]);
                        }
                    } else {
                        content += '<div class="textarea" readonly style="width: 500px; height: 170px; margin-top: 20px">' +
                            '<p style="font-size: 12px; font-weight: bold; padding-bottom: 2px"><i>' + response[0]['case_name'] + '</i></p>' +
                            '<p style="font-size: 15px; padding-bottom: 2px">' + response[0]['court'] + ', ' + response[0]['case_number'] + '</p>' +
                            '</div>';
                        $('input[name=case_number]').val(response[0]['case_number']);
                    }

                    $('input[name=case_name]').val(response[0]['case_name']);
                    $('input[name=court_id]').val(response[0]['court_id']);
                    $('input[name=court_fed_abbr]').val(response[0]['fed_abbr']);
                }

                $('.parseText').append(content);

            }, error => console.log('error:' + error))
    });

    //get remote attachment
    $(document).on('click', '.attachment-not-exist', function() {

        if (confirm('This document is not currently downloaded. Would you like to download it? ')) {
            $('.pre-loader').show();
            let attachmentID = $(this).attr('data-id');
            let sequenceID = $(this).attr('data-sequence-id');

            apiClient('/api/fetch-attachment-doc', {
                attachmentID,
                sequenceID,
                docketID: getCurrentData().docketID
            })
                .then(response => {

                        if (response.error) {
                            alert(response.error);
                            $('.pre-loader').hide();
                            return;
                        }

                        getDocketsHandler();

                        window.open(response.url);
                    },
                    error => {
                        console.log(error);
                    })
        }

    });

    window.getDocketsHandler = function() {

        $('.pre-loader').show();

        //get docket list
        apiClient('/api/get-dockets', getCurrentData())
            .finally(() => {
                $('.matter-courts-table tbody > *').remove();
                if (getCurrentData().courtType !== 'appellate') {
                    $('input[name="documents_numbered_from_"]').val('');
                    $('input[name="documents_numbered_to_"]').val('');
                } else {
                    $('input[name="documents_date_from_"]').val('');
                    $('input[name="documents_date_to_"]').val('');
                }
                $('.pre-loader').hide();
            })
            .then(
                response => {

                    let content = '';

                    if (response.length > 0) {
                        const uniqueObjects = [ ...new Set( response.map( obj => obj.sequenceID) ) ]
                            .map( sequenceID => { return response.find(obj => obj.sequenceID === sequenceID) } )
                        for (let item of uniqueObjects.sort(dynamicsort("sequenceID","asc"))) {
                            var attachments = '';
                            if (item.attachments !== null) {

                                for (let attachment of item.attachments) {

                                    let attachmentLink = '';

                                    if (attachment.downloaded == null && attachment.hasDownloadUrl) {
                                        attachmentLink = '<a class="attachment-not-exist not-exist" data-sequence-id="' + item.sequenceID + '" data-id="' + attachment.attachmentID + '">' + attachment.attachmentID + '</a>';
                                    } else if (attachment.downloaded && attachment.hasDownloadUrl && attachment.link != null) {
                                        attachmentLink = attachment.link;
                                    } else if (attachment.downloaded && attachment.hasDownloadUrl && attachment.link == null) {
                                        attachmentLink = '<a class="attachment-not-exist not-exist" data-sequence-id="' + item.sequenceID + '" data-id="' + attachment.attachmentID + '">' + attachment.attachmentID + '</a>';
                                    } else {
                                        attachmentLink = attachment.attachmentID;
                                    }

                                    attachments += '<tr>' +
                                        '<td class="attachment-td" style="width: 40px">' + attachmentLink + '</td>' +
                                        '<td>' + attachment.text + '</td>' +
                                        '</tr>';

                                }
                            }

                            if (item.attachments == null) {
                                attachments = '';
                            }

                            let link = '';

                            if (item.downloaded == null && item.hasDownloadUrl) {
                                link = '<a class="attachment-not-exist not-exist" data-sequence-id="' + item.sequenceID + '" data-id="0">' + item.sequenceID + '</a>';
                            } else if (item.downloaded && item.hasDownloadUrl && item.link != null) {
                                link = item.link;
                            } else if (item.downloaded && item.hasDownloadUrl && item.link == null) {
                                link = '<a class="attachment-not-exist not-exist" data-sequence-id="' + item.sequenceID + '" data-id="0">' + item.sequenceID + '</a>';
                            } else if (item.sequenceID.toString().indexOf('.') !== -1) {
                                link = '';
                            } else {
                                link = item.sequenceID;
                            }

                            content += '<tr>' +
                                '<td class="attachment-td">' +  link + '</td>' +
                                '<td>' + new Date(item.date).toLocaleDateString('en-US') + '</td>' +
                                '<td>' + item.description + '<table class="attachments-table">' + attachments + '</table></td>' +
                                '</tr>';
                        }
                    }else {
                        content += '<tr>' +
                            '<td colspan="3" style="text-align: center;">Not found.</td>' +
                            '</tr>';
                    }

                    $('.matter-courts-table tbody').append(content);
                },
                error => {
                    console.log(error);
                }
            );
    }
    getDocketsHandler();

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
window.addDocket = function () {
    $("input[name='selectCourts']").click(function () {
        if ($('#state').is(":checked")) {
            $('.showCourtsSystem').hide();
            $('.showForState').show();
        } else {
            $('.showCourtsSystem').hide();
            $('.showForFederal').show();
        }

        if ($('#case').is(":checked")) {
            $('.pre-loader').hide();
            $('.showByCaseNumber').show();
        } else if ($('#party').is(":checked")) {
            $('.pre-loader').hide();
            $('.showByCaseNumber').hide();
        } else {
            $('.pre-loader').hide();
            $('.showByCaseNumber').hide();
        }
        $('.showMatter').hide();
    });

    $('.stateLink').click(function (e) {
        e.preventDefault();
        $('.showForState').hide();
        $('.showForFederal').show();
    })

    $('.federalLink').click(function (e) {
        e.preventDefault();
        $('.showForState').show();
        $('.showForFederal').hide();
    })

    $('.select-beast select').selectize({
        placeholder: '( Select court )',
    });

    $('.showForState').hide();
    $('.showForFederal').hide();
    $('.showByCaseNumber').hide();
}
addDocket();
