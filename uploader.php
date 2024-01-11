<?php
  include 'burstloc.php';
  ini_set('auto_detect_line_endings',TRUE);
  $uptmpfile = $_FILES['uploadedfile']['tmp_name'];
  $uploaded_file = $_FILES['uploadedfile']['name'];
  $filetype = pathinfo ($uploaded_file,PATHINFO_EXTENSION);
  $uploadOk = 1;
  
  if ($_FILES['uploadedfile']['size'] > 1000000) {
    echo "Sorry, your file is too large.  Maximum size 1,000,000 bytes.";
    $uploadOk = 0;
    }
    
  if ($filetype != "csv" && $filetype != "txt") {
    echo "Sorry, only CSV and TXT files are allowed.";
    $uploadOk = 0;
    }
  
  if ($uploadOk == 0) {
    echo "<br>Your file was not processed.";
    
    } else {
    
    //process the file  
    
    $outfile = tempnam (sys_get_temp_dir, 'locoutput');
    $outfile_handle = fopen($outfile, "w");
    $infile_handle = fopen($uptmpfile, "r");
    
    if (($data = fgetcsv($infile_handle, 1000, ",")) !== FALSE) {
      $data[0] = "ClassAlpha,ClassNum,ClassNumDec,1stCutter,2ndCutter";
      fwrite($outfile_handle,implode(",", $data).PHP_EOL);
      
      } else {
      
      echo "Uploaded file empty, aborting.";
      fclose($outfile_handle);
      fclose($infile_handle);
      unlink($outfile);
      
      }
      
    while (($data = fgetcsv($infile_handle, 1000, ",")) !== FALSE) {
      $data[0] = burstloc ($data[0]);
      fwrite($outfile_handle,implode(",", $data).PHP_EOL);
      }
      
    fclose($infile_handle);
    fclose($outfile_handle);
    
    if (file_exists($outfile)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=LOC_burst.csv');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($outfile));
      readfile($outfile);
      exit;
      }
    unlink($outfile);
    } 
  ?>
<html>
<head>
<title>Library of Congress Call Number Utility</title>
</head>
<body>
     
</body>     
 