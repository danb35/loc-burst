<?php
  include 'burstloc.php';

  /* Prevent CSV/formula injection: if a field starts with a character that
     Excel, LibreOffice, or Google Sheets treats as the start of a formula
     (=, +, -, @) or with a tab or carriage return, prefix it with a single
     quote.  Spreadsheet applications treat a leading single quote as "this
     is text" and hide it from display, so this neutralizes the formula
     without changing what the user sees when they open the file.
  */
  function sanitizeCsvField ($value) {
    if (is_string($value) && preg_match('/^[=+\-@\t\r]/', $value)) {
      return "'" . $value;
      }
    return $value;
    }

  $uploadOk = 1;
  $errorMessage = "";

  if (!isset($_FILES['uploadedfile']) || $_FILES['uploadedfile']['error'] !== UPLOAD_ERR_OK) {
    $errorMessage = "Sorry, no file was uploaded, or the upload failed.";
    $uploadOk = 0;

    } else {

    $uptmpfile = $_FILES['uploadedfile']['tmp_name'];
    $uploaded_file = $_FILES['uploadedfile']['name'];
    $filetype = strtolower(pathinfo ($uploaded_file,PATHINFO_EXTENSION));

    if (!is_uploaded_file($uptmpfile)) {
      $errorMessage = "Sorry, the upload could not be verified.";
      $uploadOk = 0;

      } elseif ($_FILES['uploadedfile']['size'] > 1000000) {
      $errorMessage = "Sorry, your file is too large.  Maximum size 1,000,000 bytes.";
      $uploadOk = 0;

      } elseif ($filetype !== "csv" && $filetype !== "txt") {
      $errorMessage = "Sorry, only CSV and TXT files are allowed.";
      $uploadOk = 0;
      }
    }

  if ($uploadOk === 0) {
    echo $errorMessage . "<br>Your file was not processed.";

    } else {

    //process the file

    $outfile = tempnam (sys_get_temp_dir(), 'locoutput');
    $outfile_handle = fopen($outfile, "w");
    $infile_handle = fopen($uptmpfile, "r");

    $header = fgetcsv($infile_handle, 0, ",", '"', "\\");

    if ($header === FALSE) {

      echo "Uploaded file empty, aborting.";
      fclose($outfile_handle);
      fclose($infile_handle);
      unlink($outfile);

      } else {

      array_splice($header, 0, 1, array("ClassAlpha", "ClassNum", "ClassNumDec", "1stCutter", "2ndCutter"));
      fputcsv($outfile_handle, array_map('sanitizeCsvField', $header), ",", '"', "\\");

      while (($data = fgetcsv($infile_handle, 0, ",", '"', "\\")) !== FALSE) {
        $burst = burstloc ($data[0]);
        array_splice($data, 0, 1, $burst);
        fputcsv($outfile_handle, array_map('sanitizeCsvField', $data), ",", '"', "\\");
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
        unlink($outfile);
        exit;
        }
      unlink($outfile);
      }
    }
  ?>
<html>
<head>
<title>Library of Congress Call Number Utility</title>
</head>
<body>

</body>
