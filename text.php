<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="ISO-8859-1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alcosmos - Text value calculator</title>
	<meta name="theme-color" content="#161644">
	<link rel="shortcut icon" href="data/images/text.png" type="image/png">
	<style>
		body {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			vertical-align: middle;
			background-image: url("data/tools/public/text/bg_habboboxes.png");
			background-size: cover;
			background-position: center center;
			background-attachment: fixed;
			background-repeat: no-repeat;
			font-size: 15px;
			font-family: Arial, monospace;
			color: #FFFFFF;
			/*color: #303030;
			background-color: #EAEAEA;*/
		}
		
		a {
			color: white;
		}
		
		section {
			overflow: hidden;
			margin: 5px;
			padding: 10px;
			border-radius: 15px;
			box-shadow: 0 0 20px 5px black;
			background-color: #0000334F;
			vertical-align: middle;
		}
		
		section header, section footer {
			margin: -10px;
			padding: 5px 10px;
			color: #EAEAEA;
			background-color: #2443649F;
			text-align: center;
			font-weight: bold;
		}
		
		section header {
			margin-bottom: 10px;
		}
		
		section footer {
			margin-top: 10px;
		}
		
		section textarea {
			width: calc(100% - 10px);
			background-color: #FFFFFF;
		}
		
		section div.quote {
			border: 1px solid #9E9E9E;
			background-color: #AEAEFF5F;
			color: #EEEEEE;
			text-align: center;
			font-family: Courier New, monospace;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
		if (isset($_GET['text'])) {
			$text = $_GET['text'];
		} else {
			$text = 'Sample text';
		}
		
		// Easter eggs
		switch (strtolower($text)) {
			case '':
				$text = 'Whoops!';
				break;
			case 'monke':
				$text = 'Flip';
				break;
			case 'sulake':
				$text = 'Suelake';
				break;
			case 'initial d':
			case 'initiald':
				$text = utf8_decode('Déjà vu!');
				break;
		}
		
		$text = html_entity_decode($text);
	?>
	<section>
		<header>
			Text value calculator
		</header>
		Tool I made for my obsession with "calculating" the "value" of texts.
		<br><br>
		<form method="get" action="" id="form">
			<input type="hidden" name="tool" value="text">
			<textarea name="text" id="text" rows="2"><?php echo $text;?></textarea><br>
			<input type="submit" value="Calculate"><span id="updated"></span>
			<br><br>
			<?php
				$elements = 0;
				$chars = 0;
				$spaces = 0;
				$caps = 0;
				$dotted = 0;
				$dottedMarks = 0;
				$accents = 0;
				$symbols = 0;
				$first = 0;
				$last = 0;
				
				function calcValue($char) {
					$value = 0;
					
					global $elements, $chars, $spaces, $caps, $dotted, $dottedMarks, $accents, $symbols, $first, $last;
					
					//1-per-char value
					if (isset($_GET['elements'])) {$value = $value + 1; $elements = $elements + 1;}
					if (isset($_GET['chars']) && $char != ' ') {$value = $value + 1; $chars = $chars + 1;}
					if (isset($_GET['spaces']) && $char === ' ') {$value = $value + 1; $spaces = $spaces + 1;}
					if (isset($_GET['caps']) && ctype_upper($char)) {$value = $value + 1; $caps = $caps + 1;}
					if (isset($_GET['dotted']) && strpos('ij', strtolower($char)) !== false) {$value = $value + 1; $dotted = $dotted + 1;}
					if (isset($_GET['dottedMarks']) && strpos('¿?¡!', strtolower($char)) !== false) {$value = $value + 1; $dottedMarks = $dottedMarks + 1;}
					if (isset($_GET['accents']) && strpos('áéíóúàèìòùñç', strtolower($char)) !== false) {$value = $value + 1; $accents = $accents + 1;}
					if (isset($_GET['symbols']) && strpos('ºª\!|"#$~%½&¬/{([)]=}?¿¡*+.', strtolower($char)) != false) {$value = $value + 1; $symbols = $symbols + 1;}
					
					return $value;
				}
				
				function sumValues($arrValues) {
					global $elements, $chars, $spaces, $caps, $dotted, $dottedMarks, $accents, $symbols, $first, $last;
					
					$values = 0;
					
					// Values array
					foreach ($arrValues as &$value) {
						$values = $values + $value;
					}
					
					//Single value
					if (isset($_GET['first'])) {$first = 1;}
					if (isset($_GET['last'])) {$last = 1;}
                    
					// Adding singles
					$values = $values+$first+$last;
					
					return $values;
				}
				
				$arrText = str_split($text);
				$arrValues = [];
				
				for ($i = 0; $i < count($arrText); $i = $i+1) {
					$arrValues[$i] = calcValue($arrText[$i]);
				}
				
				$total = sumValues($arrValues);
				
				echo '<div class="quote">';
				
				foreach ($arrText as &$char) {
					echo $char.'&nbsp;';
				}
				
				echo '<br>';
				
				for ($i = 0; $i < count($arrText); $i++) {
					if (($first && $i == 0) || ($last && $i == count($arrText)-1)) {
						echo ((int)$arrValues[$i]+1).'&nbsp;';
					} else {
						echo $arrValues[$i].'&nbsp;';
					}
				}
				
				echo '</div><br>';
				
				echo "Total count: ".$total."<br>";
			?>
			<input type="checkbox" name="elements"<?php echo isset($_GET['elements']) ? ' checked' : '';?>> Elements<?php echo isset($_GET['elements']) ? ': '.$elements : '';?><br>
			<input type="checkbox" name="chars"<?php echo isset($_GET['chars']) ? ' checked' : '';?>> Chars<?php echo isset($_GET['chars']) ? ': '.$chars : '';?><br>
			<input type="checkbox" name="spaces"<?php echo isset($_GET['spaces']) ? ' checked' : '';?>> Spaces<?php echo isset($_GET['spaces']) ? ': '.$spaces : '';?><br>
			<input type="checkbox" name="caps"<?php echo isset($_GET['caps']) ? ' checked' : ''; ?>> Caps<?php echo isset($_GET['caps']) ? ': '.$caps : '';?><br>
			<input type="checkbox" name="dotted"<?php echo isset($_GET['dotted']) ? ' checked' : ''; ?>> Dotted (ij)<?php echo isset($_GET['dotted']) ? ': '.$dotted : '';?><br>
			<input type="checkbox" name="dottedMarks"<?php echo isset($_GET['dottedMarks']) ? ' checked' : ''; ?>> Dotted (?!)<?php echo isset($_GET['dottedMarks']) ? ': '.$dottedMarks : '';?><br>
			<input type="checkbox" name="accents"<?php echo isset($_GET['accents']) ? ' checked' : ''; ?>> Accent marks (&aacute;&ugrave;&ntilde;)<?php echo isset($_GET['accents']) ? ': '.$accents : '';?><br>
			<input type="checkbox" name="symbols"<?php echo isset($_GET['symbols']) ? ' checked' : ''; ?>> Symbols (?![%$^)<?php echo isset($_GET['symbols']) ? ': '.$symbols : '';?><br>
			<input type="checkbox" name="first"<?php echo isset($_GET['first']) ? ' checked' : '' ?>> First<?php echo isset($_GET['first']) ? ': '.$first : '';?><br>
			<input type="checkbox" name="last"<?php echo isset($_GET['last']) ? ' checked' : '' ?>> Last<?php echo isset($_GET['last']) ? ': '.$last : '';?>
		</form>
		<footer>
			<a href="https://alcosmos.ddns.net" target="_blank">Alcosmos</a> 2021<!-- - <?php echo date('Y') ?>-->
		</footer>
	</section>
	<script>
		/*var textField = document.getElementById("text");
		textField.focus();
		var textValue = textField.value;
		textField.value = '';
		textField.value = textValue;*/
		
		var form = document.getElementById("form");
		
		form.addEventListener('input', function () {
		    document.getElementById('updated').innerHTML = ' (Input modified)';
		    //document.getElementById('form').submit();
		    //console.log('aaa');
		});
	</script>
</body>
</html>
