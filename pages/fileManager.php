<?php 

?>
<div class="row-fluid">

    <div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
        <li class="nav-header">Sidebar</li>
        <li class="active"><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li class="nav-header">Sidebar</li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li class="nav-header">Sidebar</li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        <li><a href="#">Link</a></li>
        </ul>
    </div><!--/.well -->
    </div><!--/span-->

    <div class="span9">
        <div class="row-fluid">
            <div class="span3">
                <img src="download/photoDownload.php?fileId=98e63380-P8zH-zPw4-s0v5-Fg876def4490">
            </div>
            <div class="span9">
                <div class="hero-unit">
                    <h1>Welcome!</h1>
                    <p>PhotoManager is a simple way to upload and share your files online.</p>
                    <p><a class="btn btn-primary btn-large" data-toggle="modal" href="#uploadImageDialog">Upload an image &raquo;</a></p>
                </div>
            </div>
        </div>
    </div><!--/span-->
</div><!--/row-->
    
<!-------------------------------------------------------------------------->
<!----------------------------------MODALS---------------------------------->
<!-------------------------------------------------------------------------->
<div class="modal hide" id="uploadImageDialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3>Upload File</h3>
    </div>
    <div class="modal-body">
        <form id="file_upload_form" method="post" enctype="multipart/form-data" action="upload/photoUpload.php"
            <p>
                <label id="photoInput_label" for="photoInput" class=" ">Select a file</label>
                <input class="photoInput" name="file" type="file" />
            </p>
            <p>
                <textarea id="photoDescription" rows="5" cols="10" name="photoDescription"></textarea>
            </p>
            <!--<input type="hidden" id="enrollmentidFormField" name="enrollmentid" />-->
            <input id="photo-upload-submit-btn" class="hidden" type="submit" value="photoUploadSubmit">
            <iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
        </form>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
        <a id="uploadImageButton" class="btn btn-primary">Upload</a>
    </div>
</div>
<?php

?>