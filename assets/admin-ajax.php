<script language="javascript" type="text/javascript">
    function getXMLHTTP() { //fuction to return the xml http object
        var xmlhttp = false;
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e1) {
                    xmlhttp = false;
                }
            }
        }

        return xmlhttp;
    }
    function check_duplicate_emp_id(val) {
        var base_url = '<?= base_url() ?>';
        var strURL = base_url + "admin/global_controller/check_duplicate_emp_id/" + val;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        var result = req.responseText;
                        if (result) {
                            $("#id_exist_msg").append(result);
                            document.getElementById('sbtn').disabled = true;
                        } else {
                            document.getElementById('sbtn').disabled = false;
                        }

                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("POST", strURL, true);
            req.send(null);
        }
    }

    function check_current_password(val) {
        var base_url = '<?= base_url() ?>';
        var strURL = base_url + "admin/global_controller/check_current_password/" + val;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        var result = req.responseText;
                        if (result) {
                            $("#id_error_msg").css("display", "block");
                            document.getElementById('sbtn').disabled = true;
                        } else {
                            $("#id_error_msg").css("display", "none");
                            document.getElementById('sbtn').disabled = false;
                        }

                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("POST", strURL, true);
            req.send(null);
        }

    }

    function get_milestone_by_id(project_id) {
        var base_url = '<?= base_url() ?>';
        var strURL = base_url + "admin/global_controller/get_milestone_by_project_id/" + project_id;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        var result = req.responseText;
                        $("#milestone").html("<option value='' ><?= lang('select') . ' ' . lang('milestone')?></option>");
                        $("#milestone").append(result);
                        $("#milestone_show").show();
                        $("#milestone").show();
                        document.getElementById('milestone').disabled = false;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("POST", strURL, true);
            req.send(null);
        }

    }
    function get_related_moduleName(val) {
        var base_url = '<?= base_url() ?>';
        var strURL = base_url + "admin/global_controller/get_related_moduleName_by_value/" + val;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        var result = req.responseText;

                        $("#related_to").html('<label for="field-1" class="col-sm-3 control-label"><?= lang('select') . ' '?>' + capitalise(val) + '</label>');
                        $("#related_to").append(result);


                        if (val == 'project') {

                            $("#milestone_show").show();
                            $("#milestone").show();
                            document.getElementById('milestone').disabled = false;

                            $('.goal_tracking').hide();
                            $('.goal_tracking').prop("disabled", true);
                            $('.bugs_module').hide();
                            $('.bugs_module').prop("disabled", true);
                            $('.leads_module').hide();
                            $('.leads_module').prop("disabled", true);
                            $('.opportunities_module').hide();
                            $('.opportunities_module').prop("disabled", true);

                            $('.milestone_module').show();
                            $('.milestone_module').prop("disabled", false);
                            $('.project_module').show();
                            $('.project_module').prop("disabled", false);

                        }
                        if (val == 'opportunities') {
                            $('.goal_tracking').hide();
                            $('.goal_tracking').prop("disabled", true);
                            $('.bugs_module').hide();
                            $('.bugs_module').prop("disabled", true);
                            $('.leads_module').hide();
                            $('.leads_module').prop("disabled", true);

                            $('.opportunities_module').show();
                            $('.opportunities_module').prop("disabled", false);

                            $('.milestone_module').hide();
                            $('.milestone_module').prop("disabled", true);
                            $('.project_module').hide();
                            $('.project_module').prop("disabled", true);

                        }
                        if (val == 'leads') {
                            $('.goal_tracking').hide();
                            $('.goal_tracking').prop("disabled", true);
                            $('.bugs_module').hide();
                            $('.bugs_module').prop("disabled", true);

                            $('.leads_module').show();
                            $('.leads_module').prop("disabled", false);

                            $('.opportunities_module').hide();
                            $('.opportunities_module').prop("disabled", true);

                            $('.milestone_module').hide();
                            $('.milestone_module').prop("disabled", true);
                            $('.project_module').hide();
                            $('.project_module').prop("disabled", true);

                        }
                        if (val == 'bug') {
                            $('.goal_tracking').hide();
                            $('.goal_tracking').prop("disabled", true);

                            $('.bugs_module').show();
                            $('.bugs_module').prop("disabled", false);

                            $('.leads_module').hide();
                            $('.leads_module').prop("disabled", true);
                            $('.opportunities_module').hide();
                            $('.opportunities_module').prop("disabled", true);
                            $('.milestone_module').hide();
                            $('.milestone_module').prop("disabled", true);
                            $('.project_module').hide();
                            $('.project_module').prop("disabled", true);

                        }
                        if (val == 'goal') {
                            $('.goal_tracking').show();
                            $('.goal_tracking').prop("disabled", false);

                            $('.bugs_module').hide();
                            $('.bugs_module').prop("disabled", false);

                            $('.leads_module').hide();
                            $('.leads_module').prop("disabled", true);
                            $('.opportunities_module').hide();
                            $('.opportunities_module').prop("disabled", true);
                            $('.milestone_module').hide();
                            $('.milestone_module').prop("disabled", true);
                            $('.project_module').hide();
                            $('.project_module').prop("disabled", true);

                        }
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("POST", strURL, true);
            req.send(null);
        }
    }
    ;
    function capitalise(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }
    function check_user_name(str) {

        var user_name = $.trim(str);
        var user_id = $.trim($("#user_id").val());
        var base_url = '<?= base_url() ?>';
        var strURL = base_url + "admin/global_controller/check_existing_user_name/" + user_name + "/" + user_id;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        var result = req.responseText;
                        document.getElementById('username_result').innerHTML = result;
                        var msg = result.trim();
                        if (msg) {
                            document.getElementById('sbtn').disabled = true;
                        } else {
                            document.getElementById('sbtn').disabled = false;
                        }

                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("POST", strURL, true);
            req.send(null);


        }
    }
    function get_project_by_id(id) {

        var base_url = '<?= base_url() ?>';
        var strURL = base_url + "admin/global_controller/get_project_by_client_id/" + id;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        var result = req.responseText;
                        $('#client_project').empty();
                        $("#client_project").append(result);
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("POST", strURL, true);
            req.send(null);


        }
    }


</script>