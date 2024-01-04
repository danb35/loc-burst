<html>
<head>
<title>Library of Congress Call Number Utility</title>
</head>
<body>
Upload a file in .csv format with Library of Congress call numbers in the first field.
This script will break out the call number into five fields, representing the alpha
portion of the LC classification, the numeric portion, the decimal portion (if included),
the first cutter, and the second cutter.  The year, if present in the LC call number,
is ignored.
<br>The remaining fields are not processed, and are returned as they were in the uploaded file.
<br>
<br>
<FORM enctype="multipart/form-data" METHOD="POST" ACTION="uploader.php">

  <input type = "hidden" name="MAX_FILE_SIZE" value = "1000000" />
  Choose a file to upload: <input name = "uploadedfile" type = "file" />
  <input type = "submit" value = "Upload File" />
</FORM>

</body>
</html>  
