<?php
session_start();
/* temp session value */
//$_SESSION['ref'] = 'spike';

if(isset($_POST['button'])) {
    if (move_uploaded_file($_FILES['myFile']['tmp_name'], __DIR__.'/uploads/'. $_FILES["myFile"]['name'])) {
        echo "Uploaded";
    } else {
        echo "File was not uploaded";
    }
}
?>
<form action="?" method="post"  enctype="multipart/form-data"><fieldset>
<legend>
     <span>Images</span>        
 </legend>
<ol>
     <li>
        <label for="step1" id="step1">Step 1:</label>
        <input type="file" name="myFile" id="myFile" value="Browse..." />
    </li>
     <li>
        <label for="step2" id="step2">Step 2: Click the button to upload the picture to the server</label>
         <input type="submit" name="button" id="button" value="Upload image" />
    </li>
</ol>
</fieldset> 
</form>