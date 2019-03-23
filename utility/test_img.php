<?php
session_start();
/* temp session value */
$_SESSION['ref'] = 'spike'; 

if(isset($_POST['button'])) {
    print_r($_FILES);    
    $dir = "./uploads/";
    $file = "ref_" . $_SESSION['ref'];
    $path = $dir . $file . ".jpg";
    
    move_uploaded_file($_FILES['myFile']['tmp_name'], $path);
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