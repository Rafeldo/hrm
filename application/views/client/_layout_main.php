<?php $this->load->view('admin/components/htmlheader');
$opened = $this->session->userdata('opened');
$this->session->unset_userdata('opened');
?>

<body class="<?php if (!empty($opened)) {
    echo 'offsidebar-open';
} ?> <?= config_item('aside-float') . ' ' . config_item('aside-collapsed') . ' ' . config_item('layout-boxed') . ' ' . config_item('layout-fixed') ?>">
<div class="wrapper">
    <!-- top navbar-->
    <?php $this->load->view('client/components/header'); ?>
    <!-- sidebar-->
    <?php $this->load->view('client/components/sidebar'); ?>
    <!-- offsidebar-->
    <?php $this->load->view('client/components/offsidebar'); ?>
    <!-- Main section-->
    <section>
        <!-- Page content-->
        <div class="content-wrapper">
            <div class="content-heading">
                <?php echo $breadcrumbs ?>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo $subview ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Page footer-->

    <footer>
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0
        </div>
        <strong>&copy; <a href="#">UniqueCoder</a>.</strong> All rights reserved.
    </footer>
</div>
<?php $this->load->view('admin/components/footer'); ?>

<?php $this->load->view('admin/_layout_modal'); ?>
<?php $this->load->view('admin/_layout_modal_lg'); ?>
