<?php
    $profile = $_SESSION['webapp']['user'];
    $avatar  = $_SESSION['webapp']['avatar'] ?? 'webapp/assets/img/avatar.jpg';
?>
<section class="d-hide" id="user-avatar">
    <div id="profile-avatar" class="section mt-2 text-center">
        <img src="<?= $avatar ?>" alt="avatar" class="avatar rounded">
        <hr>
        <form>
            <div class="custom-file-upload">
                <input type="file" id="file-upload" accept=".png, .jpg, .jpeg">
                <label for="file-upload">
                    <span>
                        <strong>
                            <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                            <i><?= $_L->T('Upload_Photo','webapp') ?></i>
                        </strong>
                    </span>
                </label>
            </div>
        </form>
        <br>
        <button class="d-hide btn btn-secondary shadowed btn-lg btn-block my-2 do-selectAvatar"><?= $_L->T('Crop_Image','webapp') ?></button>
        <div class="example"></div>
    </div>
</section>
<section class="d-hide" id="user-profile">
    <div id="profile-avatar" class="section mt-2 text-center">
        <img src="<?= $avatar ?>" alt="avatar" class="avatar rounded">
        <br>
        <button type="button" class="show-section mt-2 btn btn-outline-secondary" screen="user" section="avatar"><i class="fa fa-edit"></i> <?= $_L->T('Edit_Avatar','webapp') ?></button>
        <button type="button" class="show-section mx-2 mt-2 btn btn-outline-secondary" screen="user" section="docs"><i class="fa fa-edit"></i> <?= $_L->T('Documents','doc') ?></button>
    </div>
    <div class="section mt-2">
        <div class="accordion accordion-flush my-5" id="accordionProfile">
            <div class="accordion-item">
                <h2 class="accordion-header" id="general-details-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general-details" aria-expanded="false" aria-controls="general-details">
                        <i class="fas fa-user-alt text-secondary me-2"></i>  <?= $_L->T('General_Details','webapp') ?>
                    </button>
                </h2>
                <div id="general-details" class="accordion-collapse collapse" aria-labelledby="general-details" data-bs-parent="#accordionProfile">
                    <div class="accordion-body">
                        <table class="table table-sm">
                            <tbody>
                            <?php if(CUSTOM_PROFILE['First_Name']){ ?><tr>
                                <td><?= $_L->T('First_Name','webapp') ?></td>
                                <td ><span class="c-fname"><?= $profile['user_extra']['fname'] ?? '-' ?></span></td>
                            <?php } ?></tr>
                            <?php if(CUSTOM_PROFILE['Last_Name']){ ?><tr>
                                <td><?= $_L->T('Last_Name','webapp') ?></td>
                                <td><span class="c-lname"><?= $profile['user_extra']['lname'] ?? '-' ?></span></td>
                            <?php } ?></tr>
                            <?php if(CUSTOM_PROFILE['E_mail']){ ?><tr>
                                <td><?= $_L->T('E_mail','webapp') ?></td>
                                <td><span class="c-email"><?= $profile['email'] ?? '-' ?></span></td>
                            <?php } ?></tr>
                            <?php if(CUSTOM_PROFILE['Phone']){ ?><tr>
                                <td><?= $_L->T('Phone','webapp') ?></td>
                                <td><span class="c-phone">+<?= $profile['user_extra']['phone'] ?? '-' ?></span></td>
                            <?php } ?></tr>
                            <?php if(CUSTOM_PROFILE['Location']){ ?><tr>
                                <td><?= $_L->T('Location','webapp') ?></td>
                                <td><span class="c-country"><?= $profile['user_extra']['country'] ?? '-' ?></span></td>
                            <?php } ?></tr>

                            <?php if(CUSTOM_PROFILE['Business_Unit']){ ?><tr>
                                <td><?= $_L->T('Business_Unit','webapp') ?></td>
                                <td><span class="c-unit"><?= $profile['unit'] ?? '-' ?></span></td>
                            <?php } ?></tr>

                            <!--
                            <tr>
                                <td colspan="2" class="text-center">
                                    <button type="button" data-form-name="profile_edit_general" title="Edit Profile" class="doM-form btn btn-primary col-8"><i class="fa fa-edit"></i> Edit </button>
                                </td>
                            </tr>
                            -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--
            <div class="accordion-item">
                <h2 class="accordion-header" id="docs-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#docs" aria-expanded="false" aria-controls="docs">
                        <i class="fas fa-id-card-alt text-secondary me-2"></i> Documents
                    </button>
                </h2>
                <div id="docs" class="accordion-collapse collapse small" aria-labelledby="docs" data-bs-parent="#accordionProfile">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-6">
                                <div>
                                            <span class="c-doc-id-status">
                                                <?php if($profile->IdCard['verify']): ?>
                                                    <i class="fa fa-check text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-minus-circle text-warning"></i>
                                                <?php endif; ?>
                                            </span>
                                    <small class="text-secondary ms-1">ID Card</small>
                                </div>
                                <div class="btn-group my-2" role="group">
                                    <?php if( $profile->IdCard['src'] ): ?>
                                        <a href="<?= $profile->IdCard['src'] ?>" target="_blank" role="button" class="btn btn-sm btn-outline-primary"><i class="fa fa-download"></i> Old</a>
                                    <?php endif; ?>
                                    <button type="button" class="doA-upload-idcard btn btn-sm btn-primary"><i class="fa fa-upload"></i> New</button>
                                </div>
                                <input type="file" name="idcard-file" id="idcard-file" class="d-hide">
                            </div>
                            <div class="col-6">
                                <div>
                                            <span class="c-doc-id-status">
                                                <?php if($profile->Bill['verify']): ?>
                                                    <i class="fa fa-check text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-minus-circle text-warning"></i>
                                                <?php endif; ?>
                                            </span>
                                    <small class="text-secondary ms-1">Proof of Residence</small>
                                </div>
                                <div class="btn-group my-2" role="group">
                                    <?php if( $profile->Bill['src'] ): ?>
                                        <a href="<?= $profile->Bill['src'] ?>" target="_blank" role="button" class="btn btn-sm btn-outline-primary"><i class="fa fa-download"></i> Old</a>
                                    <?php endif; ?>
                                    <button type="button" class="doA-upload-bill btn btn-sm btn-primary"><i class="fa fa-upload"></i> New</button>
                                </div>
                                <input type="file" name="bill-file" id="bill-file" class="d-hide">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="extra-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#extra" aria-expanded="false" aria-controls="extra">
                        <i class="fas fa-id-card-alt text-secondary me-2"></i> Extra Information
                    </button>
                </h2>
                <div id="extra" class="accordion-collapse collapse" aria-labelledby="extra" data-bs-parent="#accordionProfile">
                    <div class="accordion-body">
                        <table class="table table-sm table-dark">
                            <tbody>
                            <tr>
                                <td>City</td>
                                <td class="table-active"><span class="c-city"><?= $profile->Extra['city'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td class="table-active"><span class="c-address"><?= $profile->Extra['address'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Interests</td>
                                <td class="table-active"><span class="c-interests"><?= $profile->Extra['interests'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Hobbies</td>
                                <td class="table-active"><span class="c-hobbies"><?= $profile->Extra['hobbies'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Job Category</td>
                                <td class="table-active"><span class="c-job-category"><?= $profile->Extra['job_cat']['name'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Job Title</td>
                                <td class="table-active"><span class="c-job-title"><?= $profile->Extra['job_title'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>FX Experience</td>
                                <td class="table-active"><span class="c-exp_fx"><?= $profile->Extra['exp_fx_year'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>CFD Experience</td>
                                <td class="table-active"><span class="c-exp_cfd"><?= $profile->Extra['exp_cfd_year'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Income</td>
                                <td class="table-active"><span class="c-income"><?= $profile->Extra['income']['name'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Investment Amount</td>
                                <td class="table-active"><span class="c-investment"><?= $profile->Extra['investment']['name'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td>Trading Strategy</td>
                                <td class="table-active"><span class="c-strategy"><?= $profile->Extra['strategy'] ?? '-' ?></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="table-active text-center">
                                    <button type="button" data-form-name="profile_edit_extra"  title="Edit Profile" class="doM-form btn btn-dark col-8"><i class="fa fa-edit"></i> Edit</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            -->
        </div>
    </div>
</section>
<section class="d-hide" id="user-logout">


</section>

<section class="d-hide" id="user-docs">
    <div id="docs-manager" class="section mt-2 text-center">
        <form>
            <div class="id-uploader">
                <p class="text-left"><?= $_L->T('Upload_Valid_ID','profile') ?>:</p>
                <hr>
                <select class="form-select" id="id-type">
                    <option selected><?= $_L->T('Passport_Copy','profile') ?></option>
                    <option><?= $_L->T('National_ID','profile') ?></option>
                    <option><?= $_L->T('Driving_License','profile') ?></option>
                </select>
                <div class="custom-file-upload">
                    <input type="file" id="id-upload" accept=".png, .jpg, .jpeg">
                    <label for="id-upload">
                        <span>
                            <strong>
                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                <i><?= $_L->T('SELECT_FILE','webapp') ?></i>
                            </strong>
                        </span>
                    </label>
                </div>
            </div>

            <hr>

            <div class="addr-uploader">
                <p class="text-left"><?= $_L->T('Upload_Valid_Residence','profile') ?>:</p>
                <select class="form-select" id="addr-type">
                    <option selected><?= $_L->T('Electricity_Bill','profile') ?></option>
                    <option><?= $_L->T('Gas_Bill','profile') ?></option>
                    <option><?= $_L->T('Water_Bill','profile') ?></option>
                    <option><?= $_L->T('Mobile_Bill','profile') ?></option>
                </select>
                <div class="custom-file-upload">
                    <input type="file" id="addr-upload" accept=".png, .jpg, .jpeg, .pdf">
                    <label for="addr-upload">
                        <span>
                            <strong>
                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                <i><?= $_L->T('SELECT_FILE','webapp') ?></i>
                            </strong>
                        </span>
                    </label>
                </div>
            </div>
            <br>
            <button type="button" class="btn btn-secondary shadowed btn-lg btn-block my-2 do-upload-file"><?= $_L->T('Upload_File','webapp') ?></button>
        </form>

        <span class="loading"></span>
    </div>
</section>

<script>
    /* Avatar */
    let img_c = {
        destroy: ()=>{}
    };
    if(typeof elmFileUpload !== 'object'){
        let elmFileUpload;
    }
    elmFileUpload = document.getElementById('file-upload');
    elmFileUpload.addEventListener('change',onFileUploadChange,false);
    function onFileUploadChange(e) {
        let file = e.target.files[0];
        let fr = new FileReader();
        fr.onload = onFileReaderLoad;
        fr.readAsDataURL(file);
        img_c.destroy();
        $('.do-selectAvatar').fadeIn();
        $('#profile-avatar .avatar').fadeOut();
    }
    function onFileReaderLoad(e) {
        let bgStyle = e.target.result;
        img_c = new ImageCropper(
            ".example",
            bgStyle,
            {
                /* callbacks */
                update_cb : () => {},
                create_cb : () => {},
                destroy_cb : () => {},
                /* width & height options */
                min_crop_width : 150,
                min_crop_height : 150,
                max_width : 500,
                max_height : 500,
                /* constrain the size of the cropped area to be fixed or not */
                fixed_size : true,
                /* 'square' or 'circular' */
                mode : 'square',
            }
        );
        $('body').on('click', '#profile-avatar .do-selectAvatar', function(e) {
            /* mime_type: 'image/jpeg' or 'image/png' */
            /* quality: 0 ~ 1 */
            uploadAvatar(img_c.crop('image/jpeg', 1));
        });
    }

    /* Docs Manager */
    $('body').on('click', '#user-docs .do-upload-file', function(e) {
        $('#user-docs form').fadeOut();
        preLoader('#user-docs .loading');
        let idType = document.getElementById("id-type").value;
        let id_file = document.getElementById("id-upload").files[0];
        let addrType = document.getElementById("addr-type").value;
        let addr_file = document.getElementById("addr-upload").files[0];
        if(typeof id_file !== 'undefined' && typeof addr_file !== 'undefined'){
            uploadDoc('id', idType, id_file);
            setTimeout(()=>{
                uploadDoc('addr', addrType, addr_file);
            },1000);
        } else{
            flyNotify('danger',LanguageT.Error, 'No File Selected !', 'close-circle-outline');
        }

    });



</script>
