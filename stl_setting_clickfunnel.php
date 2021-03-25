<div class="col-md-12 col-sm-12 col-xs-12 sppage">
    <div class="row">
        <p class="sp_title">
            <?php _e( 'Clickfunnel Settings', 'student-portal' ); ?>
            <!-- <a href='<?php echo admin_url('admin.php?page=enrolled_courses_page'); ?>' class='btn btn-info btn-sm'>Add</a> -->
            <button type="button" class='btn btn-info btn-sm ' id="myBtn_clickfun"><?php _e( 'Add', 'student-portal' ); ?></button>

        </p>
        <div class="container-fluid">
            <div class="row ors-columns-outer">
                <div class="col-md-12">
                    <table class="wp-list-table widefat fixed striped ">
                        <thead>
                            <tr>
                                <td><?php _e( 'S.no', 'student-portal' ); ?></td>
                                <td><?php _e( 'Product ID', 'student-portal' ); ?></td>
                                <td><?php _e( 'Enrolled Course', 'student-portal' ); ?></td>
                                <td><?php _e( 'Action', 'student-portal' ); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($cf_datas)
                            {
                                $i = 0;
                                $sp_levels = $sp_membership_levels['levels'];
                                // echo "<pre>";print_r($sp_levels);echo "</pre>";

                                foreach($cf_datas as $cf_data)
                                {
                                    $i++;
                                 
                                    

                                    $cf_id = $cf_data->cf_id;

                                    $path = 'admin.php?page=enrolled_courses_page&enroll_id='.$cf_id;
                                    $url = admin_url($path);
                                    $link = " <a href='javascript:void(0)' data-cf_id='{$cf_id}' class='btn btn-danger btn_cfdelete btn-sm'>Delete</a>";

                                    echo "<tr><td>".$i."</td>";
                                    echo "<td>".$cf_data->product_id."</td>";
                                    echo "<td>".$cf_data->enroll_name."</td>";
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

<!-- Modal -->
<div class="modal" id="ClickfunnelModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e( 'Add Clickfunnel Product', 'student-portal' ); ?></h5>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#">
                    <div class="form-group">
                        <label class="control-label col-md-3"><?php _e('Enrolled Course'); ?></label>
                        <div class="col-md-7">
                            <select name="enroll_id" class="form-control enroll_id">
                                <option value="">Select enrolled course</option>
                                <?php
                                if($enrolled_courses)
                                {
                                    foreach($enrolled_courses as $enrolled_course)
                                    {
                                        echo "<option value='".$enrolled_course->enroll_id."'>".$enrolled_course->enroll_name."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?php _e('Product ID'); ?></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control product_id" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary clickfunclose"><?php _e( 'Close', 'student-portal' ); ?></button>
                <button type="button" class="btn btn-primary btn_cfsave"><?php _e( 'Save', 'student-portal' ); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).on('click','.btn_cfdelete',function(){
        if (confirm('Are you sure want to delete this data?')) {
        var cf_id = jQuery(this).data('cf_id');
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    jQuery.ajax({
            url: ajaxurl,
            // data: jQuery('#sp_usercreate').serialize()+ "&action=sp_save_userdata",
            data: {'cf_id':cf_id,action:'cfdata_delete'},
            type: "post",
            beforeSend: function(){
            jQuery('.preloader').show();
            },
            success:function(data) {

                alert("Clickfunnel data deleted successfully");
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

    jQuery(document).on('click','.btn_cfsave',function(){

        var enroll_id = jQuery(".enroll_id").val();
        var product_id = jQuery(".product_id").val();
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    jQuery.ajax({
            url: ajaxurl,
            // data: jQuery('#sp_usercreate').serialize()+ "&action=sp_save_userdata",
            data: {'enroll_id':enroll_id,'product_id':product_id,action:'cfdata_save'},
            type: "post",
            beforeSend: function(){
            jQuery('.preloader').show();
            },
            success:function(data) {
                alert("Clickfunnel data inserted successfully");
                location.reload();
             },
             error: function(errorThrown){
                 console.log(errorThrown);
             }
         });
                
    })

    // Get the modal
var ClickfunnelModal = document.getElementById("ClickfunnelModal");

// Get the button that opens the modal
var btn_cfun = document.getElementById("myBtn_clickfun");

// Get the <span> element that closes the modal
var span_cfclose = document.getElementsByClassName("clickfunclose")[0];

// When the user clicks on the button, open the modal 
btn_cfun.onclick = function() {
    // alert("tttttttttttttt");
  ClickfunnelModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span_cfclose.onclick = function() {
       console.log("closeee");
  ClickfunnelModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == ClickfunnelModal) {
    ClickfunnelModal.style.display = "none";
  }
}

</script>

