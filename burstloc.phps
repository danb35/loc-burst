<?php

/* PHP function to burst a Library of Congress call number into its individual parts
   Input is a string containing entire call number
   Output is an array containing alpha class, numeric class (two fields to allow for decimal),
   first cutter, and second cutter.
*/

function burstloc ($lcnum) {
  $lcarray = array();
  $lcarray["ClassAlpha"] = "";
  $lcarray["ClassNum"] = "";
  $lcarray["ClassNumDec"] = "";
  $lcarray["1stCutter"] = "";
  $lcarray["2ndCutter"] = "";
  $count = 1; 
  
  $lcnum = trim($lcnum); // eliminate leading and trailing whitespace
  $length = strlen($lcnum);

/* First character is always alpha, and goes in alpha part of classification.
   Second and third character may or may not be alpha.  If so, goes in alpha part, else
   numeric part.
*/   
  
  $lcarray["ClassAlpha"] = substr ($lcnum, 0, 1); // First character will always be alpha
  while (ctype_alpha(substr($lcnum, $count, 1))) {
    $lcarray["ClassAlpha"] .= substr ($lcnum, $count, 1);
    $count++;
  }

// If the next character is a space, ignore it

  if (strcmp(substr($lcnum,$count,1)," ") == 0) {
    $count++;
    }
    
/* Next characters are numeric, but we don't know how many numeric digits there will
   be.  Add characters to the numeric portion of the classification until we reach
   a non-numeric character (which should be a decimal or space).
*/   
  
  while (ctype_digit(substr($lcnum,$count,1))) {
    $lcarray["ClassNum"] .= substr($lcnum,$count,1);
    $count++;
    }

// If character counter has reached the length of the input, we're done

  if ($count >= $length) {
    return implode(",", $lcarray);
    }
  
/* Next character should be a decimal or space.  If not, something is badly wrong.
   If it's a space, it's skipped.
*/   

  if (strcmp(substr($lcnum,$count,1)," ") == 0) {
    $count++;
    }
    
  if (strcmp(substr($lcnum,$count,1),".") !== 0) {
    return "ERROR,".$lcnum;
    }
    
/* If the character following the decimal is numeric, then the next chunk of the
   call number is the decimal portion of the numeric classification.  This chunk will
   contain only digits, and will end with another decimal signifying the beginning
   of the first cutter
*/

  if (ctype_digit(substr($lcnum,$count+1,1))) {
    $lcarray["ClassNumDec"] = '".';
    $count++;
    while (ctype_digit(substr($lcnum,$count,1))) {
      $lcarray["ClassNumDec"] .= substr($lcnum,$count,1);
      $count++;
      }
    $lcarray["ClassNumDec"] .= '"';  
    }  

// If character counter has reached the length of the input, we're done

  if ($count >= $length) {
    return implode(",", $lcarray);
    }
  
/* Next character should be a decimal or space.  If not, something is badly wrong.
   If it's a space, it's skipped.
*/   

  if (strcmp(substr($lcnum,$count,1)," ") == 0) {
    $count++;
    }
    
  if (strcmp(substr($lcnum,$count,1),".") !== 0) {
    return "ERROR,".$lcnum;
    }

/* Next block will be the first cutter.  It's separated from the classification by
   a decimal, begins with one letter, and continues with one or more numeric characters.
   There's no fixed delimiter for this block--it ends when the next character is non-
   numeric.  If the next character after the decimal
   is non-alpha, that's an error condition and the function will die.  
*/   
  
  if (ctype_alpha(substr($lcnum,$count+1,1))) {
    $lcarray["1stCutter"] = ".";
    $count++;
    $lcarray["1stCutter"] .= substr($lcnum,$count,1);
    $count++;
    while (ctype_digit(substr($lcnum,$count,1))) {
      $lcarray["1stCutter"] .= substr($lcnum,$count,1);
      $count++;
      }
    } else {
      return "ERROR,".$lcnum;
    }
    
// If character counter has reached the length of the input, we're done

  if ($count >= $length) {
    return implode(",", $lcarray);
    }
  
/* Next block will be the second cutter, if present.  It will begin with a single
   alpha character, followed by one or more alphanumeric digits.  It ends when the next
   character is not alphanumeric.  Some LC strings will separate the second cutter with
   a decimal or a space.  If either is present, it is discarded.       
*/

  if (strcmp(substr($lcnum,$count,1),".") == 0) {
    $count++;
    }
    
  if (strcmp(substr($lcnum,$count,1)," ") == 0) {
    $count++;
    }
    
  if (ctype_alpha(substr($lcnum,$count,1))) {
    $lcarray["2ndCutter"] = substr($lcnum,$count,1);
    $count++;
    while (ctype_digit(substr($lcnum,$count,1)) || ctype_alpha(substr($lcnum,$count,1))) {
      $lcarray["2ndCutter"] .= substr($lcnum,$count,1);
      $count++;
      }
    } 

// Return the array's individual parts, in order, separated by commas

  return implode(",", $lcarray);  

}
