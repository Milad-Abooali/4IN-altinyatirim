<?php

    $query['db']        = 'DB_admin';
    $query['table']     = 'user_followup';
    $query['table_html']     = 'user_followup';
    $query['key']       = 'id';
    $query['columns']   = array(
                                array(
                                    'db' => 'id',
                                    'th' => '#',
                                    'dt' => 0
                                ),
                                array(
                                    'db' => '(select username from users where id=user_id)',
                                    'th' => 'User',
                                    'dt' => 1
                                ),
                                array(
                                    'db' => 'followup',
                                    'th' => 'Time (GMT+3)',
                                    'dt' => 2
                                ),
                                array(
                                    'db' => 'user_id',
                                    'th' => 'Action',
                                    'dt' => 3,
                                    'formatter'=> true
                                )
                            );
    $option = '
          		"responsive": true,
                "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
        		"order": [ 0, "desc" ]
    ';
    $table_followup = $factory::dataTableSimple(10, $query, $option);

?>
<section class="<?= $href ?>">

    <h6 class="text-center">Waiting Follow-Ups</h6>
    <div>
        <?php echo $table_followup; ?>
    </div>

</section>

