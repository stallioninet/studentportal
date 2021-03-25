<div class="col-md-12 col-sm-12 col-xs-12 sppage">
    <div class="row">
        <p class="sp_title">
            <?php _e( 'Logs', 'student-portal' ); ?>
          
        </p>
        <div class="container-fluid">
            <div class="row ors-columns-outer">
                <div class="col-md-12">
                    <table class="wp-list-table widefat fixed striped ">
                        <thead>
                            <tr>
                                <td><?php _e( 'S.no', 'student-portal' ); ?></td>
                                <td><?php _e( 'Log Type', 'student-portal' ); ?></td>
                                <td><?php _e( 'Log Details', 'student-portal' ); ?></td>
                                <td><?php _e( 'Email', 'student-portal' ); ?></td>
                                <td><?php _e( 'Enrolled Course', 'student-portal' ); ?></td>
                                <td><?php _e( 'Date & Time', 'student-portal' ); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($log_datas)
                            {
                                $i = 0;
                                foreach($log_datas as $log_data)
                                {
                                    $enrolled_courses = $log_data->enrolled_courses;
                                    $enrolled_courses_arr = explode(',',$enrolled_courses);
                                    $exrolled_txt = array();
                                    foreach($enrolled_courses_arr as $enrolled_id)
                                    {
                                        $exrolled_txt[] = isset($enrolled_data[$enrolled_id])?$enrolled_data[$enrolled_id]:'';
                                    }
                                    // echo "<pre>";print_r($exrolled_txt);echo "</pre>";
                                    $exrolled_txt = implode(', ',$exrolled_txt);
                                    // echo "exrolled_txt = ".$exrolled_txt;
                                    $i++;                            
                                    echo "<tr><td>".$i."</td>";
                                    echo "<td>".$log_data->log_type."</td>";
                                    echo "<td>".$log_data->log_details."</td>";
                                    echo "<td>".$log_data->user_email."</td>";
                                    echo "<td>".$exrolled_txt."</td>";
                                    echo "<td>".$log_data->created_on."</td>";
                                    echo "</tr>";

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