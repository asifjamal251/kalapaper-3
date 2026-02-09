
<script>
    var createRoute = @json(Route::has('admin.' . request()->segment(2) . '.create') ? route('admin.' . request()->segment(2) . '.create') : null);
</script>


<script type="text/javascript">
    $(document).ready(function() {
        function create(url) {

            var finalUrl = url || createRoute;
            if (!finalUrl) {
                alert('Route not defined!');
                return;
            }

            $.ajax({
                type: "GET",
                enctype: 'multipart/form-data',
                url: finalUrl,
                success: function(response) {
                    $('#addForm').html(response);
                    initializePlugins();
                    $('#dataSave').modal('show');
                }
            });
        }

        function edit(url) {
            if (!url) {
                alert('Invalid Edit URL!');
                return;
            }

            $.ajax({
                type: "GET",
                enctype: 'multipart/form-data',
                url: url,
                success: function(response) {
                    $('#addForm').html(response);
                    initializePlugins();
                    $('#dataSave').modal('show');
                }
            });
        }

        function initializePlugins() {

            if ($('.dateSelector').length > 0) {
                $(".dateSelector").flatpickr({
                    dateFormat: "d F Y",
                //defaultDate: "today"
                });
            }
            if ($('.dateSelectorRange').length > 0) {
                $(".dateSelectorRange").flatpickr({
                    mode: "range",
                    dateFormat: "d F Y",
                });
            }
            if ($('.timeInput').length > 0) {
                $(".timeInput").flatpickr({
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
            }
            if ($('.js-choice').length > 0) {
                $(".js-choice").each(function() {
                    new Choices($(this)[0], { allowHTML: true, searchEnabled:false });
                });
            }
            if ($('.js-choice-search').length > 0) {
                $(".js-choice-search").each(function() {
                    new Choices($(this)[0], { allowHTML: true });
                });
            }

            if ($('.js-choice-multiple').length > 0) {
                $(".js-choice-multiple").each(function() {
                    new Choices($(this)[0], {
                        allowHTML: true});
                });
            }

            if ($('.client').length > 0) {
                getParty('.client', true, 'Choose Client');
            }

        }

        var modalSize = '';
        var bgColor = '';
        var bgColorDefault = '#f3f3f9';
        $('body').on('click', '#create, .create', function () {
            modalSize = $(this).attr('model-size') || 'modal-xl';
            bgColor = $(this).attr('bg-color') || bgColorDefault;
            $('.modal-dialog').removeClass(modalSize);
            $('#dataSaveLabel').html($(this).attr('data-title'));
            $('.modal-size').addClass(modalSize);
            $('.modal-size .modal-body').css('background-color', bgColor);
            var url = $(this).attr('data-url') || null;
            create(url);
        });

        $('#dataSave').on('hidden.bs.modal', function () {
            $('.modal-dialog').removeClass(modalSize);
        });


        $('body').on('click', '.editData', function() {
            modalSize = $(this).attr('model-size') || 'modal-xl';
            bgColor = $(this).attr('bg-color') || bgColorDefault;
            $('.modal-dialog').removeClass(modalSize);
            $('#dataSaveLabel').html($(this).attr('data-title'));
            $('.modal-size').addClass(modalSize);
            $('.modal-size .modal-body').css('background-color', bgColor);
            var url = $(this).attr('data-url') || null;
            edit(url);
        });

    });




function getParty(selector, usePopup = true, placeholder = 'Choose Client', type='client') {
        const $elements = $(selector);
        $elements.select2({
            dropdownParent: usePopup ? $('#dataSave') : $(document.body),
            placeholder: placeholder,
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.client.list') }}?type='+type,
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                }
            }
        });
    }
   

function getPOItem(element, usePopup = true, poid = '', quality = '', gsm = '', width = '', soldTo = '') {

    const $elements = (element instanceof jQuery) ? element : $(element);

    $elements.select2({
        dropdownParent: usePopup ? $('#dataSave') : $(document.body),
        placeholder: 'Choose PO Item',
        allowClear: true,
        ajax: {
            url: '{{ route('admin.common.po.item.list') }}',
            dataType: 'json',
            cache: true,
            delay: 200,
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    poid: poid,
                    quality: quality,
                    gsm: gsm,
                    width: width,
                    soldTo: soldTo,
                };
            }
        }
    });
}



function getSoldTo(element, usePopup = true, poid = '') {
console.log(poid);
    const $select = $(element);

    $.ajax({
        url: '{{ route('admin.common.po.sold-to.list') }}',
        type: 'GET',
        data: { poid: poid },
        success: function(res){

    const selectEl = $select[0];

    // destroy previous choices instance safely
    if (selectEl.choicesInstance) {
        selectEl.choicesInstance.destroy();
        selectEl.choicesInstance = null;
    }

    // clear options
    $select.empty();

    // add default option
    $select.append('<option value="">Choose Sold To</option>');

    // append new options
    res.forEach(function(item){
        $select.append(
            `<option value="${item.id}">${item.company_name}</option>`
        );
    });

    // re-init Choices
    selectEl.choicesInstance = new Choices(selectEl, {
        searchEnabled: false,
        allowHTML: true
    });
}
    });
}

</script>
