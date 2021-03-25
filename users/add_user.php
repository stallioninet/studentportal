<div class="col-md-12 col-sm-12 col-xs-12 sppage">
    <div class="row">
        <p class="sp_title"><?php _e( 'Create User', 'student-portal' ); ?></p>
        <div class="container-fluid">
            <div class="row ors-columns-outer">
                <form action="" class="form-horizontal" method="post" id="sp_usercreate" autocomplete="off">
                    <input type="hidden" name="action" value="sp_save_userdata">
                    <div class="col-md-12">
                        


                        <h4 class="sp_subtitle"><?php _e( 'Basic Information', 'student-portal' ); ?></h4>
                        <div class="email_existmsg"></div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="departmentReg" id="label" class="col-md-4 control-label"><?php _e( 'First Name', 'student-portal' ); ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="first_name" type="text" id="firstname" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Last Name', 'student-portal' ); ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="last_name" value="" type="text" id="lastname" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="companyNameReg" id="label" class="col-md-4 control-label"><?php _e( 'Username', 'student-portal' ); ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="username" name="username" type="text" id="username" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Email', 'student-portal' ); ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="email" value="" type="email" id="email" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Password', 'student-portal' ); ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="password" value="" type="password" id="password" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jobTitleReg" id="label" class="col-md-4 control-label"><?php _e( 'Confirm Password', 'student-portal' ); ?></label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="confirm_password" value="" type="password" id="confirm_password" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div  class="col-md-4"></div>
                                    <label  class="col-md-8"><input class="form-control" name="check_infusionsoft" value="1" type="checkbox" checked> <?php _e( 'Send user information to infusionsoft? ', 'student-portal' ); ?></label>
                                </div>
                            </div>
                        </div>

                        <h4 class="col-md-12 sp_subtitle"><?php _e( 'Enrolled Courses', 'student-portal' ); ?></h4>
                        
                        <div class="col-md-12">
                                <?php
                                if($enrolled_courses)
                                {
                                    
                                        foreach($enrolled_courses as $enrolled_course)
                                        {
                                            // echo "<pre>";print_r($sp_mlevel);echo "</pre>";
                                            echo "<div class='col-md-4'><label><input type='checkbox' name='enrolled_course[]' value='".$enrolled_course->enroll_id."'> ".$enrolled_course->enroll_name."</div>";
                                        }
                                    
                                    
                                }
                                ?>
                        </div>
                        <br>


                        <!-- <h4 class="col-md-12 sp_subtitle"><?php _e( 'Wishlist Member Levels', 'student-portal' ); ?></h4>
                        
                        <div class="col-md-12">
                                <?php
                                if($sp_membership_levels)
                                {
                                    $sp_levels = $sp_membership_levels['levels'];
                                    if(!empty($sp_levels))
                                    {
                                        $sp_mlevels = $sp_levels['level'];
                                        foreach($sp_mlevels as $sp_mlevel)
                                        {
                                            // echo "<pre>";print_r($sp_mlevel);echo "</pre>";
                                            echo "<div class='col-md-4'><label><input type='checkbox' name='user_memebership[]' value='".$sp_mlevel['id']."'> ".$sp_mlevel['name']."</div>";
                                        }
                                    }
                                    
                                }
                                ?>
                        </div>
                        <br>
                        <h4 class="col-md-12 sp_subtitle"><?php _e( 'Learndash Courses', 'student-portal' ); ?></h4>
                        
                        <div class="col-md-12">
                            <?php
                            $query = new WP_Query( array( 'posts_per_page'   => -1,'post_type' => 'sfwd-courses','post_status' => 'publish', 'order' => 'DESC', 'orderby' => 'ID', ) );
     
                            if ( $query->have_posts() ) : ?>
                                <?php while ( $query->have_posts() ) : $query->the_post(); ?>   
                                    <div class="col-md-6">
                                        <label><input type='checkbox' name='user_courses[]' value='<?php echo  get_the_ID(); ?>'> <?php the_title(); ?></label>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>

                            <?php else : ?>

                            <?php endif; ?>
                        </div> -->


                       
                        
                        <div class="form-group col-md-12 text-center">
                            <br><input type="submit" name="account_submit" class="btn btn-primary btn-lg btn-block1" value="Save">
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">

jQuery(document).ready(function($){
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

    jQuery(document).on('change','#email',function(){
        jQuery(".email_existmsg").html('');
        var email = jQuery(this).val();
        var myData = $('#sp_usercreate').serialize();
         $.ajax({
            url: ajaxurl,
            // data: jQuery('#sp_usercreate').serialize()+ "&action=sp_save_userdata",
            data: {'email':email,'action':'check_unique_email'},
            type: "post",
            dataType:'json',
            beforeSend: function(){
            $('.preloader').show();
            },
            success:function(data) {

                if(data['status'])
                {
                    // console.log("iddddddddddddddd");
                    jQuery("#password").val('');
                    jQuery("#confirm_password").val('');
                    jQuery("#firstname").val('');
                    jQuery("#lastname").val('');
                    jQuery("#username").val('');

                    jQuery("#password").attr('readonly',true);
                    jQuery("#confirm_password").attr('readonly',true);
                    jQuery("#firstname").attr('readonly',true);
                    jQuery("#lastname").attr('readonly',true);
                    jQuery("#username").attr('readonly',true);
                    jQuery(".email_existmsg").html('<div class="alert alert-danger">Email id already exist</div>');
                }
                else
                {
                    // console.log("elseeeeeeeeeeee");
                    jQuery("#password").prop('readonly',false);
                    jQuery("#confirm_password").prop('readonly',false);
                    jQuery("#firstname").prop('readonly',false);
                    jQuery("#lastname").prop('readonly',false);
                    jQuery("#username").prop('readonly',false);
                    jQuery(".email_existmsg").html('');
                    // location.reload();
                }


             },
             error: function(errorThrown){
                 console.log(errorThrown);
             }
         }); 
    })




$("#sp_usercreate").validate({
            rules: {
                //firstname: "required",
                //lastname: "required",
                // username: {
                //     required: true,
                //     minlength: 2
                // },
                username: {
                   // required: true,
                    minlength: 2,
                    // remote: {
                    // url: ajaxurl,
                    // type: "post",
                    // data: {
                    //     action:'check_unique_username',
                    //     username: function() {
                    //         return $( "#username" ).val();
                    //         }
                    //     }
                    // }
                    
                },
                email: {
                    required: true,
                    email: true,
                    // remote: {
                    // url: ajaxurl,
                    // type: "post",
                    // data: {
                    //     action:'check_unique_email',
                    //     email: function() {
                    //         return $( "#email" ).val();
                    //         }
                    //     }
                    // }
                    
                },
                password: {
                    //required: true,
                    minlength: 5
                },
                confirm_password: {
                    //required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                // email: {
                //     required: true,
                //     email: true
                // }
            },
            messages: {
                firstname: "Please enter your firstname",
                lastname: "Please enter your lastname",
                username: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 2 characters",
                    // remote : "Username already taken"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                email:
                {
                    required : "Please enter a valid email address",
                    // remote : "Emailid already taken"
                }
                
            },
             submitHandler: function (form) {
                     console.log('test');
                     // form.submit();
                     var myData = $('#sp_usercreate').serialize();
                     // myData['action'] = 'sp_save_userdata';
                              $.ajax({
            url: ajaxurl,
            // data: jQuery('#sp_usercreate').serialize()+ "&action=sp_save_userdata",
            data: myData,
            type: "post",
            dataType:'json',
            beforeSend: function(){
            $('.preloader').show();
            },
            success:function(data) {

                if(data['message'] == '1')
                {
                    alert("User created successfully");
                    location.reload();
                }
                else
                {
                    alert(data['message']);
                    // location.reload();
                }
                
          // This outputs the result of the ajax request
                // console.log(data);

             },
             error: function(errorThrown){
                 console.log(errorThrown);
             }
         }); 


                     return false;
                          }
                      
        });






        //     jQuery("#sp_usercreate").submit(function(){
        //     jQuery("#page_error").hide();
        //     var username = $('#user_name').val();

        //  if (username == '0' || username =='' ) {
        //     // console.log("iffff");
        //      jQuery("#page_error").removeClass('hide').show();
        //     // alert('Please enter your phone number');
        //         return false;

        //  }
        //  else {
        //      //console.log("else");
        //      
            
        //     $.ajax({
        //     url: ajaxurl,
        //     data: jQuery('#sp_usercreate').serialize()+ "&action=Reg_acc",
        //     beforeSend: function(){
        //     $('.preloader').show();
        //     },
        //     success:function(data) {
        //   // This outputs the result of the ajax request
        //         // console.log(data);

        //      },
        //      error: function(errorThrown){
        //          console.log(errorThrown);
        //      }
        //  }); 
        
        //      return false;

        
        //  }

            
        // });

 

});
</script>

