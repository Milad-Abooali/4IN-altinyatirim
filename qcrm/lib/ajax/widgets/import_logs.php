<?php
    global $db, $userManager;
    $imports = $db->selectAll('import_logs');

?>

<div id="w-logs" class="row">

    <div class="col">

        <table id="import-log" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File</th>
                    <th>Completion</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Rows</th>
                    <th>Errors</th>
                    <th>Success</th>
                    <th>Email (D)</th>
                    <th>Phone (D)</th>
                    <th>By</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($imports) foreach ($imports as $import){ ?>
                    <tr>
                        <td><?= $import['id'] ?></td>
                        <td><?= $import['file_name'] ?>
                            <?php if($import['note']) { ?>
                                <i class="mdi mdi-clipboard-text text-warning" data-toggle='popover' title='Note'  data-content='<?= $import['note'] ?>'></i>
                            <?php } ?>
                            <br>
                            <span class="px-3 rounded-pill text-light bg-dark"><?= $import['type'] ?></span>
                        </td>
                        <td>
                            <div class="progress" style="height: 25px;">
                                <?php $progress = ($import['imported_rows']*100) / $import['file_rows'];
                                      $color = ($progress>99) ? 'success' : 'info progress-bar-striped progress-bar-animated';
                                ?>
                                <div class="progress-bar bg-<?= $color ?>" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress ?>%">
                                    <?= GF::nf($progress) ?> %
                                </div>
                            </div>
                            <span class="text-success"><?= GF::nf($import['imported_rows']) ?></span></td>
                        <td><?= GF::nf( ($import['success']*100) / $import['file_rows']  )  ?> %</td>
                        <td><?= $import['status'] ?></td>
                        <td><?= GF::nf($import['file_rows']) ?></td>
                        <td class="text-danger"><?= GF::nf($import['errors']) ?></td>
                        <td class="text-success"><?= GF::nf($import['success']) ?></td>
                        <td class="text-warning"><?= GF::nf($import['email_d']) ?></td>
                        <td class="text-warning"><?= GF::nf($import['phone_d']) ?></td>
                        <td><?= $userManager->getCustom($import['created_by'],'username')['username'] ?></td>
                        <td><?= $import['created_at'] ?></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>

    </div>

</div>
<script>
$(document).ready( function () {
    $('[data-toggle="popover"]').popover({
        trigger: 'hover',
        placement: 'right'
    });

    const dtImportLogs = $('#import-log').DataTable({
        "responsive": false,
        "deferRender": true,
        "lengthMenu": [ [10,25,-1], [10,25,"All"] ],
        'order': [[0, 'desc']]
    });
});
</script>