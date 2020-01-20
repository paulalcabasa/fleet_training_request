<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
	<meta name="viewport" content="width=600,initial-scale = 2.3,user-scalable=no">
	<link href='https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700' rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,700' rel="stylesheet">
	<!-- <![endif]-->

	<title>FLEET TRAINING SYSTEM</title>

	<style type="text/css">
		body {
			width: 100%;
			background-color: #ffffff;
			margin: 0;
			padding: 0;
			-webkit-font-smoothing: antialiased;
			mso-margin-top-alt: 0px;
			mso-margin-bottom-alt: 0px;
			mso-padding-alt: 0px 0px 0px 0px;
		}

		p,
		h1,
		h2,
		h3,
		h4 {
			margin-top: 0;
			margin-bottom: 0;
			padding-top: 0;
			padding-bottom: 0;
		}

		span.preheader {
			display: none;
			font-size: 1px;
		}

		html {
			width: 100%;
		}

		table {
			font-size: 14px;
			border: 0;
		}
		/* ----------- responsivity ----------- */

		@media only screen and (max-width: 640px) {
			/*------ top header ------ */
			.main-header {
				font-size: 20px !important;
			}
			.main-section-header {
				font-size: 28px !important;
			}
			.show {
				display: block !important;
			}
			.hide {
				display: none !important;
			}
			.align-center {
				text-align: center !important;
			}
			.no-bg {
				background: none !important;
			}
			/*----- main image -------*/
			.main-image img {
				width: 440px !important;
				height: auto !important;
			}
			/* ====== divider ====== */
			.divider img {
				width: 440px !important;
			}
			/*-------- container --------*/
			.container590 {
				width: 440px !important;
			}
			.container580 {
				width: 400px !important;
			}
			.main-button {
				width: 220px !important;
			}
			/*-------- secions ----------*/
			.section-img img {
				width: 320px !important;
				height: auto !important;
			}
			.team-img img {
				width: 100% !important;
				height: auto !important;
			}
		}

		@media only screen and (max-width: 479px) {
			/*------ top header ------ */
			.main-header {
				font-size: 18px !important;
			}
			.main-section-header {
				font-size: 26px !important;
			}
			/* ====== divider ====== */
			.divider img {
				width: 280px !important;
			}
			/*-------- container --------*/
			.container590 {
				width: 280px !important;
			}
			.container590 {
				width: 280px !important;
			}
			.container580 {
				width: 260px !important;
			}
			/*-------- secions ----------*/
			.section-img img {
				width: 280px !important;
				height: auto !important;
			}
		}
	</style>
</head>


<body class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<!-- header -->
	<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff">

		<tr>
			<td align="center">
				<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

					<tr>
						<td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
					</tr>

					<tr>
						<td align="center">

							<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

								<tr>
									<td align="center" height="70" style="height:70px;">
										<a href="" style="display: block; border-style: none !important; border: 0 !important;">
											<img src="<?php echo $message->embed(config('app.pub_url') . '/public/images/isuzu-logo-compressor.png') ?>" alt="image not found" width="100" border="0" style="display: block; width: 100px;">
										</a>
									</td>
								</tr>

							</table>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>
	<!-- end header -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

		<tr>
			<td align="center">
				<table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
					<tr>
						<td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
					</tr>
					<tr>
						<td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
							class="main-header">
                            <div style="line-height: 35px">NOTICE OF CANCELLED TRAINING REQUEST</div>
						</td>
					</tr>

					<tr>
						<td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
					</tr>

					<tr>
						<td align="center">
							<table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
								<tr>
									<td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>


					<tr>
						<td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
					</tr>
                    
                    <tr>
                        <td style="color: #343434; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                            <strong><i><u><?php echo $content['training_request']['contact_person']; ?></u></i></strong> of <strong><i><u><?php echo $content['training_request']['company_name'];?></u></i></strong> has cancelled the training request.
                        </td>
                    </tr>

                    <tr>
						<td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
					</tr>

					<tr style="color: #343434; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; ">
                        <td style="width:100%;">	 
                            <table style="width:100%;color:#343434;">
                                <tr>
                                    <td valign="top" width="190" style="font-weight:bold;">Training Program :</td>
                                    <td valign="top">
                                        <ol style="list-style:none;padding:0;margin:0;">
											<?php
												$training_programs = $content['training_request']['training_programs'];
												foreach($training_programs as $row){
													$features = $row['training_program']['program_features'];
											?>
												<li>
												<span><?php echo $row['training_program']['program_title']; ?></span>	
												<ul>
											<?php foreach($features as $feature){ ?>
												<li><?php echo $feature['feature']?></li>
											<?php } ?>
												</ul>
												<li>
											<?php
												}
											?>
                                        </ol>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td width="200" style="font-weight:bold;">Training Venue :</td>
                                    <td><?php echo $content['training_request']['venue'];?></td>
                                </tr>

								<tr>
									<td width="200" style="font-weight:bold;">Training Address :</td>
									<td><?php echo $content['training_request']['training_address'];?></td>
								</tr>

                                
                                <tr>
									<td valign="top" width="190" style="font-weight:bold;">Participants :</td>
									<td valign="top">
										<ol style="list-style:none;padding:0;margin:0;">
											<?php 
												$total = 0;
												foreach($content['training_request']['participants'] as $row){ 
													$total += $row['quantity'];
											?>
												<li><?php echo $row['participant'] . " - " . $row['quantity']; ?></li>
											<?php } ?>
											
											<li><strong>Total - <?php echo $total; ?></strong></li>
										</ol> 
									</td>
								</tr>

                                <tr>
                                    <td  width="190" style="font-weight:bold;">Isuzu Model :</td>
                                    <td><?php echo $content['training_request']['unit_model']; ?></td>
                                </tr>

								<tr class="<?php echo ($content['training_request']['emission_standard'] == "" ?  "hide" : ""); ?>">
									<td  width="190" style="font-weight:bold;">Emission Standard :</td>
									<td><?php echo $content['training_request']['emission_standard']; ?></td>
								</tr>

								<tr class="<?php echo ($content['training_request']['body_type'] == "" ?  "hide" : ""); ?>">
									<td  width="190" style="font-weight:bold;">Body Type :</td>
									<td><?php echo $content['training_request']['body_type']; ?></td>
								</tr>

                                <tr>
                                    <td  width="190" style="font-weight:bold;">Date :</td>
                                    <td><?php echo $content['training_request']['date']; ?></td>
                                </tr>

                                   
                                <tr>
                                    <td  width="190" style="font-weight:bold;">Time :</td>
                                    <td><?php echo $content['training_request']['time']; ?></td>
                                </tr>


                                <tr>
                                    <td valign="top" width="190" style="font-weight:bold;">Assigned Trainer :</td>
                                    <td valign="top">
										<ul style="list-style:none;padding:0;margin:0;">
										<?php foreach($content['training_request']['trainors'] as $row){ ?>
											<li><?php echo $row['person']['first_name'] . " " . $row['person']['last_name']; ?></li>
										<?php } ?>
										</ul>
									</td>
                                </tr>

								<tr>
									<td  width="190" style="font-weight:bold;">Additional request</td>
									<td><?php echo $content['training_request']['additional_request']; ?></td>
								</tr>


                                <tr>
                                    <td valign="top" width="190" style="font-weight:bold;">Reason of cancellation:</td>
                                    <td valign="top"><?php echo $content['training_request']['cancellation_remarks'];?></td>
                                </tr>

                               	

                            </table>	
						</td>
					</tr>

				

				</table>
			</td>
		</tr>

		<tr class="hide">
			<td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
		</tr>
		<tr>
			<td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
		</tr>

	</table>
</body>
</html>