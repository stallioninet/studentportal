<div class="col-md-12 col-sm-12 col-xs-12 sppage">
    <div class="row">
        <p class="sp_title"><?php _e( 'Setting', 'student-portal' ); ?></p>
        <div class="container-fluid">
            <div class="row ors-columns-outer">
<?php
if(isset($_POST['stlinf_submit']))
{
    $stl_inf_appname = (isset($_POST['stl_inf_appname']))?$_POST['stl_inf_appname']:'oa467';
    $stl_inf_encrykey = (isset($_POST['stl_inf_encrykey']))?$_POST['stl_inf_encrykey']:'3b1d8d0a4fb7194773d82b67f9ab7f4e';
    $stl_woo_apipassword = (isset($_POST['stl_woo_apipassword']))?$_POST['stl_woo_apipassword']:'btpestudentpassword';
    $stl_zen_subdoamin = (isset($_POST['stl_zen_subdoamin']))?$_POST['stl_zen_subdoamin']:'';
    $stl_zen_username = (isset($_POST['stl_zen_username']))?$_POST['stl_zen_username']:'';
    $stl_zen_password = (isset($_POST['stl_zen_password']))?$_POST['stl_zen_password']:'';
    update_option( 'stl_inf_appname', $stl_inf_appname );
    update_option( 'stl_inf_encrykey', $stl_inf_encrykey );
    update_option( 'stl_woo_apipassword', $stl_woo_apipassword );
    update_option( 'stl_zen_subdoamin', $stl_zen_subdoamin );
    update_option( 'stl_zen_username', $stl_zen_username );
    update_option( 'stl_zen_password', $stl_zen_password );
}
?>

                <form action="" class="form-horizontal" method="post" id="stl_save_infdata">
                    <div class="col-md-12">
                        

<?php
$stl_inf_appname = get_option('stl_inf_appname','oa467');
$stl_inf_encrykey = get_option('stl_inf_encrykey','3b1d8d0a4fb7194773d82b67f9ab7f4e');
$stl_woo_apipassword = get_option('stl_woo_apipassword','btpestudentpassword');
$stl_zen_subdoamin = get_option('stl_zen_subdoamin','');
$stl_zen_username = get_option('stl_zen_username','');
$stl_zen_password = get_option('stl_zen_password','');
?>
                        <h4 class="sp_subtitle"><?php _e( 'Infusionsoft Information', 'student-portal' ); ?></h4>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departmentReg" id="label" class="col-md-4 control-label"><?php _e( 'App Name', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="stl_inf_appname" type="text" id="stl_inf_appname" value="<?php echo $stl_inf_appname; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Encrypted Key', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="stl_inf_encrykey" type="text" id="stl_inf_encrykey" value="<?php echo $stl_inf_encrykey; ?>">
                                </div>
                            </div>
                        </div>
                        

                        <h4 class="sp_subtitle"><?php _e( 'Woocommerce Information', 'student-portal' ); ?></h4>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'API Password', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="stl_woo_apipassword" type="text" id="stl_woo_apipassword" value="<?php echo $stl_woo_apipassword; ?>">
                                </div>
                            </div>
                        </div>


                         <h4 class="sp_subtitle"><?php _e( 'Zendesk Information', 'student-portal' ); ?></h4>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Subdomain', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="stl_zen_subdoamin" type="text" id="stl_zen_subdoamin" value="<?php echo $stl_zen_subdoamin; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Username', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="stl_zen_username" type="text" id="stl_zen_username" value="<?php echo $stl_zen_username; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Password', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="stl_zen_password" type="text" id="stl_zen_password" value="<?php echo $stl_zen_password; ?>">
                                </div>
                            </div>
                        </div>
               
                        <br>



                       
                        
                        <div class="form-group col-md-12 text-center">
                            <br><input type="submit" name="stlinf_submit" class="btn btn-primary btn-lg btn-block1" value="Save">
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

