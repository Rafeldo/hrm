<?= message_box('success'); ?>
<?= message_box('error'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/css/kendo.default.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/css/kendo.common.min.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/js/kendo.all.min.js"></script>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" style="margin-top: 1px">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#task_list"
                                                               data-toggle="tab"><?= lang('all') . ' ' . lang('department') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#assign_task"
                                                               data-toggle="tab"><?= lang('new_department') ?></a></li>
        </ul>
        <div class="tab-content bg-white">
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_list" style="position: relative;">
                <!-- NESTED-->
                <div class="box" style="" data-collapsed="0">
                    <div class="box-body">
                        <!-- Table -->
                        <div class="row">

                            <?php if (!empty($all_department_info)): foreach ($all_department_info as $akey => $v_department_info) : ?>
                                <?php if (!empty($v_department_info)):
                                    if (!empty($all_dept_info[$akey]->deptname)) {
                                        $deptname = $all_dept_info[$akey]->deptname;
                                    } else {
                                        $deptname = lang('undefined_department');
                                    }
                                    ?>
                                    <div class="col-sm-6">
                                    <div class="box-heading">
                                        <div class="box-title">
                                            <h4><?php echo $deptname ?>
                                                <div class="pull-right">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                          title="<?= lang('edit') ?>">
                                                    <a href="<?= base_url() ?>admin/departments/edit_departments/<?= $all_dept_info[$akey]->departments_id ?>"
                                                       class="btn btn-primary btn-xs" data-toggle="modal"
                                                       data-placement="top" data-target="#myModal"><span
                                                            class="fa fa-pencil-square-o"></span></a>
                                                        </span>

                                                    <?php echo btn_delete('admin/departments/delete_department/' . $all_dept_info[$akey]->departments_id); ?>
                                                </div>
                                            </h4>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-bold col-sm-1">#</td>
                                        <td class="text-bold"><?= lang('designation') ?></td>
                                        <td class="text-bold col-sm-2"><?= lang('action') ?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($v_department_info as $key => $v_department) :
                                    if (!empty($v_department->designations)) {
                                        ?>

                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td><?php echo $v_department->designations ?></td>
                                            <td>
                                                <?php echo btn_edit('admin/departments/index/' . $all_dept_info[$akey]->departments_id . '/' . $v_department->designations_id); ?>
                                                <?php echo btn_delete('admin/departments/delete_designations/' . $v_department->designations_id); ?>
                                            </td>

                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="3"><?= lang('no_designation_create_yet') ?></td>
                                        <tr></tr>
                                    <?php }
                                endforeach;
                                    ?>
                                <?php endif; ?>
                                </tbody>
                                </table>
                                </div>

                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
            <h1></h1>


            <!-- Add Stock Category tab Starts -->
            <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                <form method="post" action="<?= base_url() ?>admin/departments/save_departments/<?php
                if (!empty($department_info->departments_id)) {
                    echo $department_info->departments_id;
                }
                ?>"
                      class="form-horizontal">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><?= lang('select') . ' ' . lang('department') ?>
                                    <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <select class="form-control select_box" style="width: 100%" name="departments_id"
                                            id="new_departments">
                                        <option value=""><?= lang('new_department') ?></option>

                                        <?php $all_department = $this->db->get('tbl_departments')->result();
                                        if (!empty($all_department)) {
                                            foreach ($all_department as $v_departments) { ?>
                                                <option <?= (!empty($department_info->departments_id) && $department_info->departments_id == $v_departments->departments_id ? 'selected' : null) ?>
                                                    value="<?= $v_departments->departments_id ?>"><?php
                                                    if (!empty($v_departments->deptname)) {
                                                        $deptname = $v_departments->deptname;
                                                    } else {
                                                        $deptname = lang('undefined_department');
                                                    }
                                                    echo $deptname;
                                                    ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group new_departments"
                                 style="display: <?= (!empty($department_info->departments_id) ? 'none' : 'block') ?>">
                                <label class="col-sm-4 control-label"><?= lang('new_department') ?></label>
                                <div class="col-sm-8">
                                    <input <?= (!empty($department_info->departments_id) ? 'disabled' : '') ?>
                                        type="text" name="deptname" class="form-control new_departments"
                                        value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" col-sm-4 control-label"><?= lang('designation') ?><span
                                        class="required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="designations" required class="form-control"
                                           value="<?php if (!empty($designations_info->designations)) echo $designations_info->designations; ?>"/>
                                </div>
                            </div>
                            <input type="hidden" name="designations_id" class="form-control"
                                   value="<?php if (!empty($designations_info->designations_id)) echo $designations_info->designations_id; ?>"/>

                            <div class="form-group margin">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" id="sbtn"
                                            class="btn btn-primary btn-block"><?php echo !empty($department_info->deptname) ? lang('update') : lang('save') ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">

                            <div id="roll" class="list-group">
                                <a href="#" class="list-group-item ">
                                    <?= lang('select') . ' ' . lang('menu_permission') ?>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="k-header">
                                        <div class="box-col">
                                            <div id="treeview"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
        </div>
</div>
<script>

    $("#treeview").kendoTreeView({
        checkboxes: {
            checkChildren: true,
            template: "<input type='checkbox' #= item.check# name='menu[]' value='#= item.value #'  />"
        },
        check: onCheck,
        dataSource: [
            {
                id: 1, text: "<?= lang('all') . ' ' . lang('module')?>", expanded: true, spriteCssClass: "", items: [

                <?php foreach ($result as $parent => $v_parent): ?>
                <?php if (is_array($v_parent)): ?>
                <?php foreach ($v_parent as $parent_id => $v_child): ?>
                {

                    id: "", text: "<?php echo lang($parent); ?>", value: "<?php
                    if (!empty($parent_id)) {
                        echo $parent_id;
                    }
                    ?>", expanded: false, items: [
                    <?php foreach ($v_child as $child => $v_sub_child) : ?>
                    <?php if (is_array($v_sub_child)): ?>
                    <?php foreach ($v_sub_child as $sub_chld => $v_sub_chld): ?>
                    {
                        id: "", text: "<?php echo lang($child); ?>", value: "<?php
                        if (!empty($sub_chld)) {
                            echo $sub_chld;
                        }
                        ?>", expanded: false, items: [
                        <?php foreach ($v_sub_chld as $sub_chld_name => $sub_chld_id): ?>
                        {
                            id: "", text: "<?php echo lang($sub_chld_name); ?>",<?php
                            if (!empty($roll[$sub_chld_id])) {
                                echo $roll[$sub_chld_id] ? 'check: "checked",' : '';
                            }
                            ?> value: "<?php
                            if (!empty($sub_chld_id)) {
                                echo $sub_chld_id;
                            }
                            ?>",
                        },
                        <?php endforeach; ?>
                    ]
                    },
                    <?php endforeach; ?>
                    <?php else: ?>
                    {
                        id: "", text: "<?php echo lang($child); ?>", <?php
                        if (!is_array($v_sub_child)) {
                            if (!empty($roll[$v_sub_child])) {
                                echo $roll[$v_sub_child] ? 'check: "checked",' : '';
                            }
                        }
                        ?> value: "<?php
                        if (!empty($v_sub_child)) {
                            echo $v_sub_child;
                        }
                        ?>",
                    },
                    <?php endif; ?>
                    <?php endforeach; ?>
                ]
                },
                <?php endforeach; ?>
                <?php else: ?>
                { <?php if ($parent == lang('dashboard')) {
                    ?>
                    id: "", text: "<?php echo lang($parent) ?>", <?php echo 'check: "checked",';
                    ?>  value: "<?php
                    if (!is_array($v_parent)) {
                        echo $v_parent;
                    }
                    ?>"
                    <?php
                    } else {
                    ?>
                    id: "", text: "<?php echo lang($parent); ?>", <?php
                    if (!is_array($v_parent)) {
                        if (!empty($roll[$v_parent])) {
                            echo $roll[$v_parent] ? 'check: "checked",' : '';
                        }
                    }
                    ?> value: "<?php
                    if (!is_array($v_parent)) {
                        echo $v_parent;
                    }
                    ?>"
                    <?php }
                    ?>
                },
                <?php endif; ?>
                <?php endforeach; ?>
            ]
            },
        ]
    });
    // show checked node IDs on datasource change
    function onCheck() {
        var checkedNodes = [],
            treeView = $("#treeview").data("kendoTreeView"),
            message;
        checkedNodeIds(treeView.dataSource.view(), checkedNodes);
        $("#result").html(message);
    }
</script>


<script type="text/javascript">

    $(function () {
        $("#treeview .k-checkbox input").eq(1).hide();
        $("#treeview .k-checkbox input").eq(2).hide();
        $('form').submit(function () {
            $('#treeview :checkbox').each(function () {
                if (this.indeterminate) {
                    this.checked = true;
                }
            });
        })
    })
</script>