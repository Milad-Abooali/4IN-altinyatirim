<div id="w-upload" class="row">

    <div class="col">
        <form id="import-file" name="import-file" method="post">
            <p>
                Select <span class="text-danger">.CSV</span> or <span class="text-danger">.XLSX</span> file to upload ( <a  class="btn btn-sm btn-light" href="./import/sample.xlsx"> <i class="fas fa-arrow-down"></i> Sample</a> ):
            </p>
            <div class="form-group">

                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="file" id="file" accept=".csv, .xlsx" required>
                    <label class="custom-file-label" for="file">Choose file</label>
                </div>
            </div>
            <div class="form-group">
                <label class="label" for="comment">Comment</label>
                <textarea class="form-control" name="note" id="note" placeholder="Description..."></textarea>
            </div>
            <input class="btn btn-block btn-primary" type="submit" value="Upload" name="submit">
        </form>
        <div id="loading" class="text-center text-muted my-3 h4"><i class="fas fa-spinner fa-spin text-dark"></i> Uploading File</div>
    </div>

</div>
<script>
$(document).ready( function () {
    const importForm = $(`form#import-file`);
    const fileLabel = $('label[for="file"]');
    const loading = $(`#loading`);
    loading.hide();
    loading.hide();
    $("body").on("change",'form#import-file .custom-file-input', function() {
        const fileName = $(this).val().split("\\").pop();
        fileLabel.text(fileName);
    });
    $("body").on("submit","form#import-file", function(e) {
        e.preventDefault();
        loading.show();
        importForm.fadeOut();
        let formData = new FormData(this);
        ajaxForm ('import', 'add', formData, function(response){
            let resObj = JSON.parse(response);
            if (resObj.e) toastr.error(resObj.e);
            if (resObj.res) toastr.success(`Import request added: ${resObj.res}`);
            importForm.trigger('reset');
            fileLabel.text("Choose file");
            loading.hide();
            importForm.fadeIn();
            $(`div[data-wg="import_logs.php"] .reload`).trigger(`click`);
        });
    });
});
</script>