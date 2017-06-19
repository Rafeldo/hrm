<script type="text/javascript">
    (function (window, document, $, undefined) {

        $(function () {

            if (!$.fn.select2) return;

            // Select 2

            $('.select_box').select2({
                theme: 'bootstrap',
            });
            $(".select_2_to").select2({
                tags: true,
                theme: 'bootstrap',
                allowClear: true,
                placeholder: 'To : Select or Write',
                tokenSeparators: [',', ' ']
            });
            $('.select_multi').select2({
                theme: 'bootstrap',
            });

            $('table tbody tr td:last-child').addClass('hidden-print');

            var total_header = ($('table#DataTables th:last').index());
            var testvar = [];
            for (var i = 0; i < total_header; i++) {
                testvar[i] = i;
            }
            $("[id^=DataTables]").dataTable({

                'paging': true,  // Table pagination
                'ordering': true,  // Column ordering
                'info': true,  // Bottom left status text
                'dom': 'Bfrtip',  // Bottom left status text
                buttons: [

                    {
                        extend: 'print',
                        text: "<i class='fa fa-print'> </i>",
                        className: 'btn btn-danger btn-xs mr',
                        exportOptions: {
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        }
                    },
                    {
                        extend: 'print',

                        text: '<i class="fa fa-print"> </i> &nbsp;<?= lang('selected')?>',
                        className: 'btn btn-success mr btn-xs',
                        exportOptions: {
                            modifier: {
                                selected: true,
                                columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                            }
                        }

                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel-o"> </i>',
                        className: 'btn btn-purple mr btn-xs',
                        exportOptions: {
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-excel-o"> </i>',
                        className: 'btn btn-primary mr btn-xs',
                        exportOptions: {
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf-o"> </i>',
                        className: 'btn btn-info mr btn-xs',
                        exportOptions: {
                            columns: [testvar[0], testvar[1], testvar[2], testvar[3], testvar[4], testvar[5]]
                        }
                    },
                ],
                select: true,
                // Text translation options
                // Note the required keywords between underscores (e.g _MENU_)
                oLanguage: {
                    sSearch: '<?= lang('search_all_column')?>',
                    sLengthMenu: '_MENU_ <?= lang('record_per_page')?>',
                    info: '<?= lang('showing')?> <?= lang('page')?> _PAGE_ of _PAGES_',
                    zeroRecords: '<?= lang('nothing_found_sorry')?>',
                    infoEmpty: '<?= lang('no_record_available')?>',
                    infoFiltered: '(<?= lang('filtered_from')?> _MAX_ <?= lang('total')?> <?= lang('records')?>)'
                }

            });
            setTimeout(function () {
                $(".alert").fadeOut("slow", function () {
                    $(".alert").remove();
                });

            }, 3000);
            $('[data-ui-slider]').slider();

        });

    })(window, document, window.jQuery);
</script>