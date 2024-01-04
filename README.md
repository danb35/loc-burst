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
