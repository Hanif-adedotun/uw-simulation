<?php
 include '../login.php';
?>

<!doctype html>
<html lang="en">
     <head>
          <title>Test for image</title>
     </head>
     <body>
          <form action="../api/applications.php" method="post" enctype="multipart/form-data" >
               Select Image File to Upload:
               <input type="file" name="logo" accept="image/*">
               <input type="submit" name="submit" value="Upload">
          </form>
     </body>
</html>