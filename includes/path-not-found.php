<?php
if ( !defined( 'LOAD_INC' ) || !isset( $path ) ) {
	require_once ( 'index.php' );
	exit ( 0 );
}
?>

<!DOCTYPE html>
<html><head><title>404 Not Found</title><style type="text/css">
body { text-align: center; }

h1 { margin-top: 10%; color: #7f7f7f; font-size: 50px; font-weight: normal; font-family: "Helvetica", sans-serif; }

p { width: 50%; margin: 20px auto 20px auto; padding: 20px;
	color: #ffffff; background-color: #2D8EB6; border: 1px solid transparent; border-radius: 12px;
	font-size: 26px; font-weight: normal; font-family: "Helvetica", sans-serif; }

</style>
</head>

<body>
<h1>Sorry, There was an error!!!</h1>
<p>Steelcode could not find the file <br> <?php echo $path; ?></p>
</body>
</html>