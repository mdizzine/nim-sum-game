<?php
	$human = 0;
	$ai = 0;
	function takeMatch($row, $cnt, $inRow){
		if($row==="error"){
			echo "You must choose a, b, c, or d" . PHP_EOL;
			return false;
		}elseif(!is_numeric($cnt)){
			echo "You entered a non numeric value of matches" . PHP_EOL;
			return false;
		}elseif($cnt!=intval($cnt)){
			echo "You cannot take fractions of matches" . PHP_EOL;
			return false;
		}elseif($cnt < 1){
			echo "You must take at least one match from the playing area" . PHP_EOL;
			return false;
		}elseif($row==0){
			echo "There are no matches in that row" . PHP_EOL;
			return false;
		}elseif($cnt > $row){
			echo "There aren't that many matches in that row" . PHP_EOL;
			return false;
		}else{
			$GLOBALS[$inRow] = $row - $cnt;
			return true;
		}
	}
	
	function aiTakeMatch($passNim){
		$nimSum = false;
		while(!$nimSum){		
			$rowExists = false;
			while(!$rowExists){
				$row = rand(0,3);
				switch($row){
					case 0:
						if($GLOBALS["a"]>0){
							$rowExists = true;
							$row = "a";
						}
						break;
					case 1:
						if($GLOBALS["b"]>0){
							$rowExists = true;
							$row = "b";
						}
						break;
					case 2:
						if($GLOBALS["c"]>0){
							$rowExists = true;
							$row = "c";
						}
						break;					
					case 3:
						if($GLOBALS["d"]>0){
							$rowExists = true;
							$row = "d";
						}
						break;
				}
			}
			$cntValid = false;
			while(!$cntValid){
				$cnt = rand(1,7);
				if($GLOBALS[$row]>=$cnt){				
					$cntValid = true;
				}
			}
			$GLOBALS[$row] = $GLOBALS[$row] - $cnt;
			if(!$passNim){
				if(!testNim()){
					$nimSum = true;
				}else{
					$GLOBALS[$row] = $GLOBALS[$row] + $cnt;
				}
			}else{
				$nimSum = true;
			}
		}
		printStatement($cnt, $row);
	}
	
	function aiTakeMatchNim(){
		$zeroCount = 0;
		$nonZeros = array();
		if($GLOBALS["a"]==0){
			$zeroCount++;
		}else{
			$notZero = "a";
			$nonZeros[] = "a";
		}
		if($GLOBALS["b"]==0){
			$zeroCount++;
		}else{
			$notZero = "b";
			$nonZeros[] = "b";
		}
		if($GLOBALS["c"]==0){
			$zeroCount++;
		}else{
			$notZero = "c";
			$nonZeros[] = "c";
		}
		if($GLOBALS["d"]==0){
			$zeroCount++;
		}else{
			$notZero = "d";
			$nonZeros[] = "d";
		}	
		
		if($zeroCount==3){
			$cnt = $GLOBALS[$notZero] - 1;
			if($cnt!=0){
				$GLOBALS[$notZero] = $GLOBALS[$notZero] - $cnt;
				printStatement($cnt, $notZero);
			}else{
				if(!testNim()){
					$passNim = true;
				}else{
					$passNim = false;
				}				
				aiTakeMatch($passNim);
			}
		}elseif($zeroCount==2){
			if($GLOBALS[$nonZeros[0]]==1){
				$cnt = $GLOBALS[$nonZeros[1]];
				$GLOBALS[$nonZeros[1]] = 0;
				printStatement($cnt,$nonZeros[1]);
			}elseif($GLOBALS[$nonZeros[1]]==1){
				$cnt = $GLOBALS[$nonZeros[0]];
				$GLOBALS[$nonZeros[0]] = 0;
				printStatement($cnt,$nonZeros[0]);
			}else{
				aiTakeMatch(!testNim());
			}
		}else{
			$nimSum = ($GLOBALS["a"] ^ $GLOBALS["b"] ^ $GLOBALS["c"] ^ $GLOBALS["d"]);
			switch($nimSum){
				case 0:
					aiTakeMatch(true);
					break;
				case 1:
					if(($GLOBALS["a"] % 2)!=0){
						$GLOBALS["a"] = $GLOBALS["a"] - 1;
						printStatement(1,"a");
					}elseif(($GLOBALS["b"] % 2)!=0){
						$GLOBALS["b"] = $GLOBALS["b"] - 1;
						printStatement(1,"b");
					}elseif(($GLOBALS["c"] % 2)!=0){
						$GLOBALS["c"] = $GLOBALS["c"] - 1;
						printStatement(1,"c");
					}elseif(($GLOBALS["d"] % 2)!=0){
						$GLOBALS["d"] = $GLOBALS["d"] - 1;
						printStatement(1,"d");
					}
					break;
				case 2:
					if($GLOBALS["a"]+$GLOBALS["b"]+$GLOBALS["c"]+$GLOBALS["d"]==4){
						if($GLOBALS["a"]==2){
							$GLOBALS["a"] = 1;
							printStatement(1,"a");
						}elseif($GLOBALS["b"]==2){
							$GLOBALS["b"] = 1;
							printStatement(1,"b");
						}elseif($GLOBALS["c"]==2){
							$GLOBALS["c"] = 1;
							printStatement(1,"c");
						}elseif($GLOBALS["d"]==2){
							$GLOBALS["d"] = 1;
							printStatement(1,"d");
						}
					}elseif(($GLOBALS["b"] % 2)==0 && $GLOBALS["b"]!=0){
						$GLOBALS["b"] = $GLOBALS["b"] - 2;
						printStatement(2,"b");
					}elseif(($GLOBALS["c"] % 2)==0 && $GLOBALS["c"]!=4 && $GLOBALS["c"]!=0){
						$GLOBALS["c"] = $GLOBALS["c"] - 2;
						printStatement(2,"c");
					}elseif(($GLOBALS["d"] % 2)==0 && $GLOBALS["d"]!=4 && $GLOBALS["d"]!=0){
						$GLOBALS["d"] = $GLOBALS["d"] - 2;
						printStatement(2,"d");
					}else{
						foreach($nonZeros as $nz){
							if($GLOBALS[$nz]>1){
								$GLOBALS[$nz] = $GLOBALS[$nz] - 2;
								printStatement(2,$nz);
								break;
							}
						}
					}
					break;
				case 3:
					$finish = false;
					if($GLOBALS["b"]==3){
						if(($GLOBALS["a"] ^ ($GLOBALS["b"]-3) ^ $GLOBALS["c"] ^ $GLOBALS["d"])==0){
							$GLOBALS["b"] = $GLOBALS["b"] - 3;
							printStatement(3,"b");
							$finish = true;
						}
					}
					if(!$finish){
						if($GLOBALS["c"]>=3){
							if(($GLOBALS["a"] ^ $GLOBALS["b"] ^ ($GLOBALS["c"]-3) ^ $GLOBALS["d"])==0){
								$GLOBALS["c"] = $GLOBALS["c"] - 3;
								printStatement(3,"c");
								$finish = true;
							}	
						}
					}
					if(!$finish){
						if($GLOBALS["d"]>=3){
							if(($GLOBALS["a"] ^ $GLOBALS["b"] ^ $GLOBALS["c"] ^ ($GLOBALS["d"]-3))==0){
								$GLOBALS["d"] = $GLOBALS["d"] - 3;
								printStatement(3,"d");
								$finish = true;
							}
						}
					}
					if(!$finish){
						aiTakeMatch(false);
					}
					break;
				case 4:
					if($GLOBALS["c"]>=4){
						$GLOBALS["c"] = $GLOBALS["c"] - 4;
						printStatement(4,"c");
					}elseif($GLOBALS["d"]>=4){
						$GLOBALS["d"] = $GLOBALS["d"] - 4;
						printStatement(4,"d");
					}
					break;
				case 5:
					aiTakeMatch(false);
					break;
				case 6:
					aiTakeMatch(false);
					break;
				case 7:
					aiTakeMatch(false);
					break;
			}
		}
	}
	
	function printMatches(){
		echo '      ';
		for($cx = 0; $cx < $GLOBALS['a']; $cx++){
			echo '1 ';
		}
		echo PHP_EOL;
		echo '    ';
		for($cx = 0; $cx < $GLOBALS['b']; $cx++){
			echo '1 ';
		}
		echo PHP_EOL;
		echo '  ';
		for($cx = 0; $cx < $GLOBALS['c']; $cx++){
			echo '1 ';
		}
		echo PHP_EOL;
		for($cx = 0; $cx < $GLOBALS['d']; $cx++){
			echo '1 ';
		}
		echo PHP_EOL;
	}
	
	function printStatement($count, $row){
		echo "I take " . $count . " match";
		if($count>1){
			echo "es";
		}
		echo " from row " . $row . "." . PHP_EOL;
	}
	
	function testNim(){
		if(($GLOBALS["a"] ^ $GLOBALS["b"] ^ $GLOBALS["c"] ^ $GLOBALS["d"])==0){
			return false;
		}else{
			return true;
		}
	}
	
	echo "Greetings." . PHP_EOL;
	do{		
		echo "Would you like to play a game?" . PHP_EOL;
		$startGame = stream_get_line(STDIN,1024,PHP_EOL);
		switch(strtolower($startGame)){
			case 'yes':
			case 'y':
				$go = true;
				$end = false;
				break;
			case 'no':
			case 'n':
				$go = false;
				$end = true;
				break;
			default:
				echo "I do not understand..." . PHP_EOL;
				$go = false;
				$end = false;
				break;
		}
		if($go){
			do{
				do{
					echo "Would you like to go first or second?" . PHP_EOL;
					$response = stream_get_line(STDIN,1024,PHP_EOL);
					switch(strtolower($response)){
						case "first":
						case "1":
						case "1st":
							$i = 0;
							$start = true;
							break;
						case "second":
						case "2":
						case "2nd":
							$i = 1;
							$start = true;
							break;
						default:
							echo "I do not understand..." . PHP_EOL;
							$start = false;
							break;
					}
				}while(!$start);
				$a = 1;
				$b = 3;
				$c = 5;
				$d = 7;
				while(($a + $b + $c + $d)>0){
					printMatches();
					if(($i%2)==0){
						echo "Which row would you like to take from?" . PHP_EOL;
						$inRow = stream_get_line(STDIN,1024,PHP_EOL);
						switch($inRow){
							case 'a':
							case 'A':
								$row = $a;
								break;
							case 'b':
							case 'B':
								$row = $b;
								break;
							case 'c':
							case 'C':
								$row = $c;
								break;
							case 'd':
							case 'D':
								$row = $d;
								break;
							default:
								$row = "error";
								break;
						}
						echo "How many matches would you like to take?" . PHP_EOL;
						$cnt = stream_get_line(STDIN,1024,PHP_EOL);
						$continue = takeMatch($row, $cnt, $inRow);
					}else{
						aiTakeMatchNim();
						$continue = true;
					}
					if($continue){
						$i++;
					}
					sleep(1);
				}
				if($i%2){
					echo PHP_EOL . "I win." . PHP_EOL . PHP_EOL;
					$ai++;
					echo "Score" . PHP_EOL;
					echo "Joshua: " . $ai . PHP_EOL;
					echo "You: " . $human . PHP_EOL . PHP_EOL;
				}else{
					echo PHP_EOL . "You win!" . PHP_EOL . PHP_EOL;
					$human++;
					echo "Score" . PHP_EOL;
					echo "Joshua: " . $ai . PHP_EOL;
					echo "You: " . $human . PHP_EOL . PHP_EOL;
				}
				do{
					echo "Would you like to play again?" .PHP_EOL;
					$resp = stream_get_line(STDIN,1024,PHP_EOL);
					switch(strtolower($resp)){
						case "yes":
						case "y":
							$valid = true;
							$gameOn = true;
							break;
						case "no":
						case "n":
							$valid = true;
							$gameOn = false;
							$end = true;
							break;
						default:
							echo "Invalid response..." . PHP_EOL;
							$valid = false;
							break;
					}
				}while(!$valid);
			}while($gameOn);
		}
	}while(!$end);
?>