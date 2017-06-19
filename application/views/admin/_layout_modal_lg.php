<!-- Modal -->
<style type="text/css">
    .datepicker {
        z-index: 1050 !important;
    }

    .bootstrap-timepicker-widget.dropdown-menu.open {
        display: inline-block;
        z-index: 99999 !important;
    }
</style>
<div class="modal fade" id="myModal_lg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>
<!-- SELECT2-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2-bootstrap.css">
<script src="<?= base_url() ?>assets/plugins/select2/dist/js/select2.js"></script>

<!-- =============== Datepicker ===============-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.css">
<?php include_once 'assets/js/bootstrap-datepicker.php'; ?>
<!-- =============== timepicker ===============-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/timepicker.css">
<script src="<?= base_url() ?>assets/js/timepicker.js"></script>


<script type="text/javascript">
    $(function () {
        $('.select_box').select2({
            theme: 'bootstrap',
        });
        $('.select_multi').select2({
            theme: 'bootstrap',
        });
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayBtn: "linked",
        });
        $('.timepicker2').timepicker({
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false,
            defaultTime: false
        });
        $('.textarea_2').summernote({
            height: 100,
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
        $('.note-toolbar .note-fontsize,.note-toolbar .note-help,.note-toolbar .note-fontname,.note-toolbar .note-height,.note-toolbar .note-table').remove();

        $('.textarea').summernote({
            height: 200,
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
        $('.note-toolbar .note-fontsize,.note-toolbar .note-help,.note-toolbar .note-fontname,.note-toolbar .note-height,.note-toolbar .note-table').remove();

        $('input.select_one').on('change', function () {
            $('input.select_one').not(this).prop('checked', false);
        });
    });
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
        location.reload();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#permission_user').hide();
        $("div.action").hide();
        $("input[name$='permission']").click(function () {
            $("#permission_user").removeClass('show');
            if ($(this).attr("value") == "custom_permission") {
                $("#permission_user").show();
            } else {
                $("#permission_user").hide();
            }
        });

        $("input[name$='assigned_to[]']").click(function () {
            var user_id = $(this).val();
            $("#action_" + user_id).removeClass('show');
            if (this.checked) {
                $("#action_" + user_id).show();
            } else {
                $("#action_" + user_id).hide();
            }

        });
    });
</script>
