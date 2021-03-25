<div class="col-md-12 col-sm-12 col-xs-12 sppage">
    <div class="row">
        <p class="sp_title">
            <?php _e( 'Enrolled Courses', 'student-portal' ); ?>
            <a href='<?php echo admin_url('admin.php?page=enrolled_courses_page'); ?>' class='btn btn-info btn-sm'><?php _e( 'Add', 'student-portal' ); ?></a>
            
        </p>
        <div class="container-fluid">
            <div class="row ors-columns-outer">
                <div class="col-md-12">
                    <table class="wp-list-table widefat fixed striped ">
                        <thead>
                            <tr>
                                <td><?php _e( 'S.no', 'student-portal' ); ?></td>
                                <td><?php _e( 'Enrolled Course', 'student-portal' ); ?></td>
                                <td><?php _e( 'Infusionsoft Tag id', 'student-portal' ); ?></td>
                                
                                <td><?php _e( 'Wishlist Level', 'student-portal' ); ?></td>
                                <td><?php _e( 'Learndash Courses', 'student-portal' ); ?></td>
                                <td><?php _e( 'Action', 'student-portal' ); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($enrolled_courses)
                            {
                                $i = 0;
                                $sp_levels = $sp_membership_levels['levels'];
                                // echo "<pre>";print_r($sp_levels);echo "</pre>";

                                foreach($enrolled_courses as $enrolled_course)
                                {
                                    $i++;
                                    $leanrdsk_ids = $enrolled_course->leanrdsk_id;
                                    $leanrdsk_txt = array();
                                    if($leanrdsk_ids !='')
                                    {
                                        $leanrdsk_ids = explode(',',$leanrdsk_ids);
                                        foreach($leanrdsk_ids as $leanrdsk_id)
                                        {
                                            $leanrdsk_txt[] = get_the_title($leanrdsk_id);
                                        }

                                    }
                                    $leanrdsk_txt = implode(', ',$leanrdsk_txt);

                                    $wh_mlevel_ids = $enrolled_course->wh_mlevel_id;
                                    $wh_mlevel_txt = array();
                                    

                                    if($wh_mlevel_ids !='')
                                    {
                                        $wh_mlevel_ids = explode(',',$wh_mlevel_ids);
                                        foreach($wh_mlevel_ids as $wh_mlevel_id)
                                        {
                                            $level_data = $wlm_api_methods->get_level($wh_mlevel_id);
                                            if($level_data)
                                            {
                                                $wh_mlevel_txt[] = $level_data['level']['name'];
                                            }
                                            // $wh_mlevel_txt[] = 

                                        }

                                    }
                                    // echo "<pre>";print_r($wh_mlevel_txt);echo "</pre>";
                                    $wh_mlevel_txt = implode(', ',$wh_mlevel_txt);
                                    $enroll_id = $enrolled_course->enroll_id;

                                    $path = 'admin.php?page=enrolled_courses_page&enroll_id='.$enroll_id;
                                    $url = admin_url($path);
                                    $link = "<a href='{$url}' class='btn btn-success btn-sm'>Edit</a> / <a href='javascript:void(0)' data-enroll_id='{$enroll_id}' class='btn btn-danger btn_encdelete btn-sm'>Delete</a>";

                                    echo "<tr><td>".$i."</td>";
                                    echo "<td>".$enrolled_course->enroll_name."</td>";
                                    echo "<td>".$enrolled_course->infusionsoft_tagid."</td>";
                                    echo "<td>".$wh_mlevel_txt."</td>";
                                    echo "<td>".$leanrdsk_txt."</td>";
                                    echo "<td>".$link."</td></tr>";

                                }
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
               
            </div>
        </div>

    </div>
</div>
<script>
    jQuery(document).on('click','.btn_encdelete',function(){
        if (confirm('Are you sure want to delete this enrolled course?')) {
        var enroll_id = jQuery(this).data('enroll_id');
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    jQuery.ajax({
            url: ajaxurl,
            // data: jQuery('#sp_usercreate').serialize()+ "&action=sp_save_userdata",
            data: {'enroll_id':enroll_id,action:'enc_delete'},
            type: "post",
            beforeSend: function(){
            jQuery('.preloader').show();
            },
            success:function(data) {

                alert("Enrolled course deleted successfully");
                location.reload();
          // This outputs the result of the ajax request
                // console.log(data);

             },
             error: function(errorThrown){
                 console.log(errorThrown);
             }
         });
                }
    })
</script>

