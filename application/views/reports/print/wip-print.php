<html>
	<head>
		<title><?php echo $meta_title;?></title>
		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap/css/bootstrap.css" />

		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/stylesheets/invoice-print.css" />

		<link rel="stylesheet"  href="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/css/print-report.css" />


	</head>
	<body>
		<?php print_wip_report( $reportEntries, $_POST['sortOrder'], @$_POST['filter'], $settings->businessName ); ?>
		<script>
			window.print();
		</script>	
		</body>
</html>