<div class="col-md-12 col-sm-12 col-xs-12 sppage">
    <div class="row">
        <p class="sp_title"><?php _e( 'Add New Enrolled Courses', 'student-portal' ); ?></p>
        <div class="container-fluid">
            <div class="row ors-columns-outer">
                <form action="" class="form-horizontal" method="post" id="sp_encoursescreate">
                    <input type="hidden" name="enroll_id" value="<?php echo $uenroll_id; ?>">

                    <div class="col-md-12">
                        <?php echo $course_saved; ?>    
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departmentReg" id="label" class="col-md-4 control-label"><?php _e( 'Enrolled Course Name', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="enroll_name" type="text" id="enroll_name" value="<?php echo $uenroll_name; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departmentReg" id="label" class="col-md-4 control-label"><?php _e( 'Infusionsoft Tag id', 'student-portal' ); ?></label>
                                <div class="col-md-8">
                                    <input class="form-control" name="infusionsoft_tagid" type="text" id="infusionsoft_tagid" value="<?php echo $uinfusionsoft_tagid; ?>" required>
                                </div>
                            </div>
                        </div>

                        <h4 class="col-md-12 sp_subtitle"><?php _e( 'Wishlist Member Levels', 'student-portal' ); ?></h4>
                        
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
                                            $checked = (in_array($sp_mlevel['id'], $uwh_mlevel_ids))?'checked':'';
                                            // echo "<pre>";print_r($sp_mlevel);echo "</pre>";
                                            echo "<div class='col-md-4'><label><input type='checkbox' name='wh_mlevel_id[]' value='".$sp_mlevel['id']."' ".$checked."> ".$sp_mlevel['name']."</div>";
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
                                <?php while ( $query->have_posts() ) : $query->the_post(); 
                                    $postid = get_the_ID();
                                    $checked = (in_array($postid, $uleanrdsk_ids))?'checked':'';

                                    ?>   
                                    <div class="col-md-6">
                                        <label><input type='checkbox' name='leanrdsk_id[]' value='<?php echo  get_the_ID(); ?>' <?php echo $checked; ?>> <?php the_title(); ?></label>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>

                            <?php else : ?>

                            <?php endif; ?>
                        </div>


                       
                        
                        <div class="form-group col-md-12 text-center">
                            <br><input type="submit" name="encourse_submit" class="btn btn-primary btn-lg btn-block1" value="Save">
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">

// jQuery(document).ready(function($){

// var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";


// $("#sp_encoursescreate").validate({
//             rules: {
//                 firstname: "required",
//                 lastname: "required",
//                 // username: {
//                 //     required: true,
//                 //     minlength: 2
//                 // },
//                 enroll_name: {
//                     required: true,
//                     minlength: 2,
//                     // remote: {
//                     // url: ajaxurl,
//                     // type: "post",
//                     // data: {
//                     //     action:'check_unique_enroll_name',
//                     //     enroll_name: function() {
//                     //         return $( "#enroll_name" ).val();
//                     //         }
//                     //     }
//                     // }
                    
//                 },
                
          
//             },
//             messages: {

//                 enroll_name: {
//                     required: "Please enter a Enrolled Course name",
//                     minlength: "Your enrolled Course name must consist of at least 2 characters",
//                     // remote : "Enrolled Course name already taken"
//                 }
                
//             },
//              submitHandler: function (form) {
//                      console.log('test');
//                      // form.submit();
//                      var myData = $('#sp_encoursescreate').serialize();
//                      // myData['action'] = 'sp_save_userdata';
//                               $.ajax({
//             url: ajaxurl,
//             // data: jQuery('#sp_usercreate').serialize()+ "&action=sp_save_userdata",
//             data: myData,
//             type: "post",
//             beforeSend: function(){
//             $('.preloader').show();
//             },
//             success:function(data) {

//                 alert("Course created successfully");
//                 location.reload();
//           // This outputs the result of the ajax request
//                 // console.log(data);

//              },
//              error: function(errorThrown){
//                  console.log(errorThrown);
//              }
//          }); 


//                      return false;
//                           }
                      
//         });





 

// });
</script>

