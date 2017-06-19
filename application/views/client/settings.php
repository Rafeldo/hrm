<!-- Content Header (Page header) -->
<?php echo message_box('success') ?>
<div class="row">    
    <!-- Start Form -->

    <div class="col-sm-9">   
        <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>client/settings/update_settings/<?php
        if (!empty($client_info)) {
            echo $client_info->client_id;
        }
        ?>" method="post" class="form-horizontal  ">
            <section class="panel panel-custom">
                <header class="panel-heading  "><?= lang('settings') ?></header>
                <div class="panel-body">                    
                    <div class="row bg-box" >

                        <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3">

                            <div class="form-group">
                                <label class="col-lg-6 control-label">
                                    <select class="form-control" name="client_status" id="client_stusus">
                                        <option value="1" <?php
                                        if (!empty($client_info) && $client_info->client_status == 1) {
                                            echo 'selected';
                                        }
                                        ?>>Person</option>
                                        <option value="2" <?php
                                        if (!empty($client_info) && $client_info->client_status == 2) {
                                            echo 'selected';
                                        }
                                        ?>>Company</option>
                                    </select>
                                </label>                    
                            </div>                
                        </div>                
                    </div>  
                    <!--- /************ Person Start ***************/ --->
                    <div class="person" style="<?php
                    if (!empty($person)) {
                        echo 'display:block';
                    } else {
                        echo 'display:none';
                    }
                    ?>">
                        <div class="nav-tabs-custom">
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#general" data-toggle="tab"><?= lang('general') ?></a></li>
                                <li><a href="#contact" data-toggle="tab"><?= lang('contacts') ?></a></li>                                
                                <li><a href="#web" data-toggle="tab"><?= lang('web') ?></a></li>                                
                                <li><a href="#hosting" data-toggle="tab"><?= lang('hostname') ?></a></li>                                
                            </ul>
                            <div class="tab-content bg-white">
                                <!-- ************** general *************-->
                                <div class="chart tab-pane active" id="general">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('full_name') ?><span class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" required="" value="<?php
                                            if (!empty($client_info->name)) {
                                                echo $client_info->name;
                                            }
                                            ?>" name="name">
                                        </div>
                                    </div>                
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('email') ?><span class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="email" class="form-control person" required="" value="<?php
                                            if (!empty($client_info->email)) {
                                                echo $client_info->email;
                                            }
                                            ?>" name="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><?= lang('language') ?></label>
                                        <div class="col-sm-5">
                                            <select name="language" class="form-control person select_box" style="width: 100%">
                                                <?php foreach ($languages as $lang) : ?>
                                                    <option value="<?= $lang->name ?>"<?= ($this->config->item('language') == $lang->name ? ' selected="selected"' : '') ?>><?= ucfirst($lang->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('currency') ?></label>
                                        <div class="col-lg-5">
                                            <select  name="currency" class="form-control person select_box" style="width: 100%">                                                 

                                                <?php if (!empty($currencies)): foreach ($currencies as $currency): ?>
                                                        <option value="<?= $currency->code ?>"<?= ($this->config->item('default_currency') == $currency->code ? ' selected="selected"' : '') ?>><?= $currency->name ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>                                                
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('short_note') ?></label>
                                        <div class="col-lg-5">
                                            <textarea class="form-control person" name="short_note"><?php
                                                if (!empty($client_info->short_note)) {
                                                    echo $client_info->short_note;
                                                }
                                                ?></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('profile_photo') ?></label>
                                        <div class="col-lg-7" >                                                        
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 100px;" >
                                                    <?php if (!empty($client_info->profile_photo)) : ?>
                                                        <img src="<?php echo base_url() . $client_info->profile_photo; ?>" >  
                                                    <?php else: ?>
                                                        <img src="http://placehold.it/350x260" alt="Please Connect Your Internet">     
                                                    <?php endif; ?>                                 
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 100px;" ></div>
                                                <div>
                                                    <span class="btn btn-default btn-file">
                                                        <span class="fileinput-new">
                                                            <input class="person" type="file" name="profile_photo" data-buttonText="<?= lang('choose_file') ?>" id="myImg">
                                                            <span class="fileinput-exists"><?= lang('change') ?></span>    
                                                        </span>
                                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"><?= lang('remove') ?></a>

                                                </div>

                                                <div id="valid_msg" style="color: #e11221"></div>

                                            </div>    
                                        </div>
                                    </div>  
                                </div><!-- ************** general *************-->

                                <!-- ************** Contact *************-->
                                <div class="chart tab-pane" id="contact">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('phone') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" required="" value="<?php
                                            if (!empty($client_info->phone)) {
                                                echo $client_info->phone;
                                            }
                                            ?>" name="phone">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('mobile') ?><span class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" required="" value="<?php
                                            if (!empty($client_info->mobile)) {
                                                echo $client_info->mobile;
                                            }
                                            ?>" name="mobile">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('fax') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->fax)) {
                                                echo $client_info->fax;
                                            }
                                            ?>" name="fax">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('city') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->city)) {
                                                echo $client_info->city;
                                            }
                                            ?>" name="city">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('country') ?></label>
                                        <div class="col-lg-5">
                                            <select  name="country" class="form-control person select_box" style="width: 100%"> 
                                                <optgroup label="Default Country"> 
                                                    <option value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                                </optgroup> 
                                                <optgroup label="<?= lang('other_countries') ?>"> 
                                                    <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                                            <option value="<?= $country->value ?>"><?= $country->value ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </optgroup> 
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('zipcode') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->zipcode)) {
                                                echo $client_info->zipcode;
                                            }
                                            ?>" name="zipcode">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('address') ?></label>
                                        <div class="col-lg-5">
                                            <textarea class="form-control person" name="address"><?php
                                                if (!empty($client_info->address)) {
                                                    echo $client_info->address;
                                                }
                                                ?></textarea>
                                        </div>
                                    </div>
                                </div><!-- ************** Contact *************-->
                                <!-- ************** Web *************-->
                                <div class="chart tab-pane" id="web" >
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('website') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->website)) {
                                                echo $client_info->website;
                                            }
                                            ?>" name="website">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('skype_id') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->skype_id)) {
                                                echo $client_info->skype_id;
                                            }
                                            ?>" name="skype_id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('facebook_profile_link') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->facebook)) {
                                                echo $client_info->facebook;
                                            }
                                            ?>" name="facebook">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('twitter_profile_link') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->twitter)) {
                                                echo $client_info->twitter;
                                            }
                                            ?>" name="twitter">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('linkedin_profile_link') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->linkedin)) {
                                                echo $client_info->linkedin;
                                            }
                                            ?>" name="linkedin">
                                        </div>
                                    </div>
                                </div><!-- ************** Web *************-->
                                <!-- ************** Hosting *************-->
                                <div class="chart tab-pane" id="hosting" >
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('hosting_company') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->hosting_company)) {
                                                echo $client_info->hosting_company;
                                            }
                                            ?>" name="hosting_company">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('hostname') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->hostname)) {
                                                echo $client_info->hostname;
                                            }
                                            ?>" name="hostname">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('username') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->username)) {
                                                echo $client_info->username;
                                            }
                                            ?>" name="username">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('password') ?></label>
                                        <div class="col-lg-5">
                                            <input type="password" class="form-control person" value="<?php
                                            if (!empty($client_info->password)) {
                                                echo $client_info->password;
                                            }
                                            ?>" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('port') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control person" value="<?php
                                            if (!empty($client_info->port)) {
                                                echo $client_info->port;
                                            }
                                            ?>" name="port">
                                        </div>
                                    </div>                                
                                </div><!-- ************** Hosting *************-->
                            </div>
                        </div><!-- /.nav-tabs-custom -->                    

                    </div><!--- /************ Person End ***************/ --->

                    <!--- /************ Company Start ***************/ --->
                    <div class="company" style="<?php
                    if (!empty($company)) {
                        echo 'display:block';
                    } else {
                        echo 'display:none';
                    }
                    ?>">
                        <div class="nav-tabs-custom">
                            <!-- Tabs within a box -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#general_compnay" data-toggle="tab">General</a></li>
                                <li><a href="#contact_compnay" data-toggle="tab">Contact</a></li>                                
                                <li><a href="#web_compnay" data-toggle="tab">Web</a></li>                                
                                <li><a href="#hosting_compnay" data-toggle="tab">Hosting</a></li>                                
                            </ul>
                            <div class="tab-content bg-white">
                                <!-- ************** general *************-->
                                <div class="chart tab-pane active" id="general_compnay">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_name') ?><span class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" required="" value="<?php
                                            if (!empty($client_info->name)) {
                                                echo $client_info->name;
                                            }
                                            ?>" name="name">
                                        </div>
                                    </div>                
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_email') ?><span class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="email" class="form-control company" required="" value="<?php
                                            if (!empty($client_info->email)) {
                                                echo $client_info->email;
                                            }
                                            ?>" name="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_vat') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->vat)) {
                                                echo $client_info->vat;
                                            }
                                            ?>" name="vat">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><?= lang('language') ?></label>
                                        <div class="col-sm-5">
                                            <select name="language" class="form-control company select_box" style="width: 100%">
                                                <?php foreach ($languages as $lang) : ?>
                                                    <option value="<?= $lang->name ?>"<?= ($this->config->item('language') == $lang->name ? ' selected="selected"' : '') ?>><?= ucfirst($lang->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('currency') ?></label>
                                        <div class="col-lg-5">
                                            <select  name="currency" class="form-control company select_box" style="width: 100%">                                                 

                                                <?php if (!empty($currencies)): foreach ($currencies as $currency): ?>
                                                        <option value="<?= $currency->code ?>"<?= ($this->config->item('default_currency') == $currency->code ? ' selected="selected"' : '') ?>><?= $currency->name ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>                                                
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('short_note') ?></label>
                                        <div class="col-lg-5">
                                            <textarea class="form-control company" name="short_note"><?php
                                                if (!empty($client_info->short_note)) {
                                                    echo $client_info->short_note;
                                                }
                                                ?></textarea>
                                        </div>
                                    </div>
                                </div><!-- ************** general *************-->

                                <!-- ************** Contact *************-->
                                <div class="chart tab-pane" id="contact_compnay">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_phone') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->phone)) {
                                                echo $client_info->phone;
                                            }
                                            ?>" name="phone">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_mobile') ?><span class="text-danger"> *</span></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" required="" value="<?php
                                            if (!empty($client_info->mobile)) {
                                                echo $client_info->mobile;
                                            }
                                            ?>" name="mobile">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_fax') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->fax)) {
                                                echo $client_info->fax;
                                            }
                                            ?>" name="fax">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_city') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->city)) {
                                                echo $client_info->city;
                                            }
                                            ?>" name="city">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_country') ?></label>
                                        <div class="col-lg-5">
                                            <select  name="country" class="form-control company select_box" style="width: 100%"> 
                                                <optgroup label="Default Country"> 
                                                    <option value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                                </optgroup> 
                                                <optgroup label="<?= lang('other_countries') ?>"> 
                                                    <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                                            <option value="<?= $country->value ?>"><?= $country->value ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </optgroup> 
                                            </select> 
                                        </div>
                                    </div>                                  
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_address') ?></label>
                                        <div class="col-lg-5">
                                            <textarea class="form-control company" name="address"><?php
                                                if (!empty($client_info->address)) {
                                                    echo $client_info->address;
                                                }
                                                ?></textarea>
                                        </div>
                                    </div>
                                </div><!-- ************** Contact *************-->
                                <!-- ************** Web *************-->
                                <div class="chart tab-pane" id="web_compnay" >
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('company_domain') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->website)) {
                                                echo $client_info->website;
                                            }
                                            ?>" name="website">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('skype_id') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->skype_id)) {
                                                echo $client_info->skype_id;
                                            }
                                            ?>" name="skype_id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('facebook_profile_link') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->facebook)) {
                                                echo $client_info->facebook;
                                            }
                                            ?>" name="facebook">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('twitter_profile_link') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->twitter)) {
                                                echo $client_info->twitter;
                                            }
                                            ?>" name="twitter">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('linkedin_profile_link') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->linkedin)) {
                                                echo $client_info->linkedin;
                                            }
                                            ?>" name="linkedin">
                                        </div>
                                    </div>
                                </div><!-- ************** Web *************-->
                                <!-- ************** Hosting *************-->
                                <div class="chart tab-pane" id="hosting_compnay" >
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('hosting_company') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->hosting_company)) {
                                                echo $client_info->hosting_company;
                                            }
                                            ?>" name="hosting_company">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('hostname') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->hostname)) {
                                                echo $client_info->hostname;
                                            }
                                            ?>" name="hostname">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('username') ?> </label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->username)) {
                                                echo $client_info->username;
                                            }
                                            ?>" name="username">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('password') ?></label>
                                        <div class="col-lg-5">
                                            <input type="password" class="form-control company" value="<?php
                                            if (!empty($client_info->password)) {
                                                echo $client_info->password;
                                            }
                                            ?>" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('port') ?></label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control company" value="<?php
                                            if (!empty($client_info->port)) {
                                                echo $client_info->port;
                                            }
                                            ?>" name="port">
                                        </div>
                                    </div>                                
                                </div><!-- ************** Hosting *************-->
                            </div>
                        </div><!-- /.nav-tabs-custom -->
                    </div><!--- /************ Company End ***************/ --->
                    <div class="form-group">
                        <label class="col-lg-3"></label>
                        <div class="col-lg-5">
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                        </div>
                    </div>
            </section>
        </form>
    </div>
    <!-- End Form -->
</div>

