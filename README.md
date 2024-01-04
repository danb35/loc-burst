# loc-burst

This tool arose out of a niche need, that being printing spine labels for books with Library of Congress (LC) call numbers, based on data downloaded from librarything.com.  That data contains the entire call number in a single string like "TS536.6.B6 D4 1995", but the spine label would typically have each component on its own line, like this:
```
  TS
  536
  .6
  .B6
  D4
  1994
```

This PHP tool accepts a CSV file with the LC call number in the first field, the date (year) of publication in the second, and any other desired text (e.g, title) in remaining field(s).  It returns a CSV file with the LC call number broken into five fields, the year in the sixth, and any remaining text as it was initially entered.

## Usage
Clone this repository into a directory your web server can access, and browse to it.  The `index.html` page provides an upload button to upload and process a CSV file, as well as some tips about the use of the output.

Alternatively, you can integrate `locburst.php` and `uploader.php` into your own web pages if you prefer.

## Requirements
This tool runs under PHP 5.4, and presumably any later version as well.  It does not require any additional libraries.

## Limitations
The maximum file size for upload is 1 MB.
