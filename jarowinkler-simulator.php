<form method="Post" action="">
	<input type="text" name="s1" required placeholder="Input String 1">
	<input type="text" name="s2" required placeholder="Input String 2">
	<input type="submit" name="cek" value="Check Kemiripan">
</form>
<?php
if(isset($_POST['cek'])){
$string1=$_POST['s1'];
$string2=$_POST['s2'];

echo "Menghitung nilai kemiripan string <b>$string1</b> & <b>$string2</b><hr><br>";

echo "Mencari Jaro Distance: <hr>";

//Panjang String
$str1_len = strlen( $string1 );
$str2_len = strlen( $string2 );
echo "Panjang (s1): $str1_len [$string1]<br>";
echo "Panjang (s2): $str2_len [$string2]<br>";

// theoretical distance
$distance = (int) floor(min( $str1_len, $str2_len ) / 2.0); 
// get common characters
$commons1 = getCommonCharacters( $string1, $string2, $distance );
$commons2 = getCommonCharacters( $string2, $string1, $distance );
if( ($commons1_len = strlen( $commons1 )) == 0) return 0;
if( ($commons2_len = strlen( $commons2 )) == 0) return 0;

// calculate transpositions
$transpositions = 0;
$upperBound = min( $commons1_len, $commons2_len );
for( $i = 0; $i < $upperBound; $i++){

	if( $commons1[$i] != $commons2[$i] ) $transpositions++;
}
$tra=$transpositions;
$transpositions /= 2.0; 
$kt="";if($transpositions==0){$kt="(Tidak ada karakter yang berbeda)";}

echo "Batas atas karakter: $upperBound<br>Karakter umum s1: $commons1<br>Karakter umum s2: $commons2<br>";
echo "Jumlah karakter yang tidak cocok dg index yg sama (t): $tra/2 = $transpositions $kt<br><br>";

$jaro=($commons1_len/($str1_len) + $commons2_len/($str2_len) + ($commons1_len - $transpositions)/($commons1_len)) / 3.0;

echo "Rumus &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: d_j =  1/3  × (m/(|s_1|)  + m/(|s_2|)+ (m-t)/m)<br>";
echo "Jaro Distance: d_j =  1/3  × ($commons1_len/$str1_len  + $commons2_len/$str2_len+ ($commons1_len - $transpositions)/$commons1_len)<br>";
echo "Jaro Distance: d_j = $jaro<br>";


$prefixLength = getPrefixLength( $string1, $string2 );
$PREFIXSCALE = 0.1;
$winkler = $jaro + $prefixLength * $PREFIXSCALE * (1.0 - $jaro);
echo "<br><br>Mencari Jaro-Winkler Distance: <hr>";
echo "Prefix length (l): $prefixLength<br>";
echo "Konstanta scaling faktor (p): $PREFIXSCALE<br>";
echo "Rumus JWD &nbsp; &nbsp;: d_w =  d_j  +(lp (1- d_j ))<br>";
echo "Jaro Winkler D: d_w =  $jaro  + ($prefixLength × $PREFIXSCALE (1- $jaro))<br>";
echo "Jaro Winkler D: d_w = $winkler (".number_format(($winkler*100),2)."%)<br>";



}

function getCommonCharacters( $string1, $string2, $allowedDistance ){
  
  $str1_len = strlen($string1);
  $str2_len = strlen($string2);
  $temp_string2 = $string2;
   
  $commonCharacters='';

  for( $i=0; $i < $str1_len; $i++){
    
    $noMatch = True;

    // compare if char does match inside given allowedDistance
    // and if it does add it to commonCharacters
    for( $j= max( 0, $i-$allowedDistance ); $noMatch && $j < min( $i + $allowedDistance + 1, $str2_len ); $j++){
      if( $temp_string2[$j] == $string1[$i] ){
        $noMatch = False;

	$commonCharacters .= $string1[$i];

	$temp_string2[$j] = 0;
      }
    }
  }

  return $commonCharacters;
}

function getPrefixLength( $string1, $string2, $MINPREFIXLENGTH = 4 ){
  
  $n = min( array( $MINPREFIXLENGTH, strlen($string1), strlen($string2) ) );
  
  for($i = 0; $i < $n; $i++){
    if( $string1[$i] != $string2[$i] ){
      // return index of first occurrence of different characters 
      return $i;
    }
  }

  // first n characters are the same   
  return $n;
}
?>
