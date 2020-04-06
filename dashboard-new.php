<?php
   require_once("inc/config.php");
   require_once("session_check.php");
   require_once("inc/classes/class.survey.php");

   $com_id = COM_ID;
   $surObj = new SurveyFunctions($prop, $common);
   $cdat = date('Y-m-d', strtotime($_SESSION['U']['cdate']));
   $lastmonths  = $surObj->getLastMonthsNew(4, 'array', $cdat);
   $sql_join_year = 'select created_date from sur_companies where id = '.$com_id;
   $join_year  = $prop->get_Disp($sql_join_year);
   $join_year = $join_year['created_date'];
   $join_year = explode('-',$join_year);
   //COLUM 1
   $dates = $common->getMonthStartEndDate();//getLastMonthDates(6);

   if(isset($_GET['action']) && $_GET['action']=='chart' && $_GET['d']!='' && $_GET['d']!='undefined'){
   	$start_date = $_GET['d'];
	if($start_date != 'all' and $start_date != 'YTD' and $start_date != '3months' and $start_date != '6months'){
		
		$date_from = $start_date.'-01-01';
		$date_to = $start_date.'-12-31';
	  	$cond = " AND DATE(ES.add_date) BETWEEN DATE('" . $date_from . "') AND DATE('" . $date_to . "') ";
	  	$sql = "SELECT COUNT(ES.ref_id) as tot_sent,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '2' THEN 1 ELSE 0 END), 0) email,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '1' THEN 1 ELSE 0 END), 0) sms,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '3' THEN 1 ELSE 0 END), 0) guest,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '4' THEN 1 ELSE 0 END), 0) link,
				 COALESCE(SUM(CASE WHEN ES.is_open = 1 THEN 1 ELSE 0 END), 0) open_cnt,
				 COALESCE(SUM(CASE WHEN ES.response = 1 THEN 1 ELSE 0 END), 0) complete,
				 COALESCE(SUM(CASE WHEN ES.response = 2 THEN 1 ELSE 0 END), 0) partial,
				 DATE(ES.add_date),DATE_FORMAT(ES.add_date, '%b') as monthname FROM sur_email_share AS ES INNER JOIN sur_survey AS S ON
				 ES.sur_id = S.sur_id AND S.is_deleted = 0 AND ES.com_id = S.com_id
			   WHERE ES.com_id = $com_id $cond GROUP BY MONTH(ES.add_date) ORDER BY ES.add_date ASC";
			   $TotalSurveySent  = $prop->getAll_Disp($sql);
			   $graph_title  = $start_date." Report";
	}
	else if($start_date == 'YTD')
	{
		$date_from = date('Y-m-d', strtotime('first day of january this year'));
		$date_to = date('Y-m-d');
	  	$cond = " AND DATE(ES.add_date) BETWEEN DATE('" . $date_from . "') AND DATE('" . $date_to . "') ";
	  	$sql = "SELECT COUNT(ES.ref_id) as tot_sent,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '2' THEN 1 ELSE 0 END), 0) email,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '1' THEN 1 ELSE 0 END), 0) sms,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '3' THEN 1 ELSE 0 END), 0) guest,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '4' THEN 1 ELSE 0 END), 0) link,
				 COALESCE(SUM(CASE WHEN ES.is_open = 1 THEN 1 ELSE 0 END), 0) open_cnt,
				 COALESCE(SUM(CASE WHEN ES.response = 1 THEN 1 ELSE 0 END), 0) complete,
				 COALESCE(SUM(CASE WHEN ES.response = 2 THEN 1 ELSE 0 END), 0) partial,
				 DATE(ES.add_date),DATE_FORMAT(ES.add_date, '%b') as monthname FROM sur_email_share AS ES INNER JOIN sur_survey AS S ON
				 ES.sur_id = S.sur_id AND S.is_deleted = 0 AND ES.com_id = S.com_id
			   WHERE ES.com_id = $com_id $cond GROUP BY MONTH(ES.add_date) ORDER BY ES.add_date ASC";
			   $TotalSurveySent  = $prop->getAll_Disp($sql);
			   $graph_title  = "YTD Report";
	}
	else if($start_date == '3months')
	{
		$date_from = date('Y-m-d', strtotime('-2 month'));
		$date_to = date('Y-m-d');
	  	$cond = " AND DATE(ES.add_date) BETWEEN DATE('" . $date_from . "') AND DATE('" . $date_to . "') ";
	  	$sql = "SELECT COUNT(ES.ref_id) as tot_sent,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '2' THEN 1 ELSE 0 END), 0) email,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '1' THEN 1 ELSE 0 END), 0) sms,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '3' THEN 1 ELSE 0 END), 0) guest,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '4' THEN 1 ELSE 0 END), 0) link,
				 COALESCE(SUM(CASE WHEN ES.is_open = 1 THEN 1 ELSE 0 END), 0) open_cnt,
				 COALESCE(SUM(CASE WHEN ES.response = 1 THEN 1 ELSE 0 END), 0) complete,
				 COALESCE(SUM(CASE WHEN ES.response = 2 THEN 1 ELSE 0 END), 0) partial,
				 DATE(ES.add_date),DATE_FORMAT(ES.add_date, '%b') as monthname FROM sur_email_share AS ES INNER JOIN sur_survey AS S ON
				 ES.sur_id = S.sur_id AND S.is_deleted = 0 AND ES.com_id = S.com_id
			   WHERE ES.com_id = $com_id $cond GROUP BY MONTH(ES.add_date) ORDER BY ES.add_date ASC";
			   $TotalSurveySent  = $prop->getAll_Disp($sql);
			   $graph_title  = "3 Months Report";
	}
	else if($start_date == '6months')
	{
		$date_from = date('Y-m-d', strtotime('-5 month'));
		$date_to = date('Y-m-d');
	  	$cond = " AND DATE(ES.add_date) BETWEEN DATE('" . $date_from . "') AND DATE('" . $date_to . "') ";
	  	$sql = "SELECT COUNT(ES.ref_id) as tot_sent,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '2' THEN 1 ELSE 0 END), 0) email,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '1' THEN 1 ELSE 0 END), 0) sms,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '3' THEN 1 ELSE 0 END), 0) guest,
				 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '4' THEN 1 ELSE 0 END), 0) link,
				 COALESCE(SUM(CASE WHEN ES.is_open = 1 THEN 1 ELSE 0 END), 0) open_cnt,
				 COALESCE(SUM(CASE WHEN ES.response = 1 THEN 1 ELSE 0 END), 0) complete,
				 COALESCE(SUM(CASE WHEN ES.response = 2 THEN 1 ELSE 0 END), 0) partial,
				 DATE(ES.add_date),DATE_FORMAT(ES.add_date, '%b') as monthname FROM sur_email_share AS ES INNER JOIN sur_survey AS S ON
				 ES.sur_id = S.sur_id AND S.is_deleted = 0 AND ES.com_id = S.com_id
			   WHERE ES.com_id = $com_id $cond GROUP BY MONTH(ES.add_date) ORDER BY ES.add_date ASC";
			   $TotalSurveySent  = $prop->getAll_Disp($sql);
			   $graph_title  = "6 Months Report";
	}
	else{
		$sql = "SELECT COUNT(ES.ref_id) as tot_sent,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '2' THEN 1 ELSE 0 END), 0) email,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '1' THEN 1 ELSE 0 END), 0) sms,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '3' THEN 1 ELSE 0 END), 0) guest,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '4' THEN 1 ELSE 0 END), 0) link,
			 COALESCE(SUM(CASE WHEN ES.is_open = 1 THEN 1 ELSE 0 END), 0) open_cnt,
			 COALESCE(SUM(CASE WHEN ES.response = 1 THEN 1 ELSE 0 END), 0) complete,
			 COALESCE(SUM(CASE WHEN ES.response = 2 THEN 1 ELSE 0 END), 0) partial,
			 YEAR(ES.add_date) as monthname FROM ".EMAIL." AS ES INNER JOIN ".SURVEY." AS S ON
			 ES.sur_id = S.sur_id AND S.is_deleted = 0 AND ES.com_id = S.com_id
		   WHERE ES.com_id = $com_id GROUP BY YEAR(ES.add_date) ORDER BY ES.add_date ASC";
		   $TotalSurveySent  = $prop->getAll_Disp($sql);
		   $graph_title  = "Overall Report";
	}
	$total = $email = $sms = $guest = $link = $dateSeries = array();
	for ($i = 0; $i < count($TotalSurveySent); $i++) {
		/*if($start_date == 'YTD' or $start_date == '3months' or $start_date == '6months')
		$dateSeries[] = $TotalSurveySent[$i]['monthname'];
		else
		$dateSeries[] = $TotalSurveySent[$i]['DATE(ES.add_date)'];*/
		
		$dateSeries[] = $TotalSurveySent[$i]['monthname'];
		$total[] = 1*$TotalSurveySent[$i]['tot_sent'];
		$email[] = 1*$TotalSurveySent[$i]['email'];
		$sms[] = 1*$TotalSurveySent[$i]['sms'];
		$guest[] = 1*$TotalSurveySent[$i]['guest'];
		$link[]  = 1*$TotalSurveySent[$i]['link'];
	}
	echo json_encode(array('xAxis'=>$dateSeries,'email'=>$email,'sms'=>$sms,'guest'=>$guest,'link'=>$link,'graph_title'=>$graph_title,'sql'=>$sql)); exit;
  }
    $date_from = date('Y-m-d', strtotime('first day of january this year'));
	$date_to = date('Y-m-d');
	$cond = " AND DATE(ES.add_date) BETWEEN DATE('" . $date_from . "') AND DATE('" . $date_to . "') ";
	$sql = "SELECT COUNT(ES.ref_id) as tot_sent,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '2' THEN 1 ELSE 0 END), 0) email,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '1' THEN 1 ELSE 0 END), 0) sms,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '3' THEN 1 ELSE 0 END), 0) guest,
			 COALESCE(SUM(CASE WHEN ES.mode_of_sent = '4' THEN 1 ELSE 0 END), 0) link,
			 COALESCE(SUM(CASE WHEN ES.is_open = 1 THEN 1 ELSE 0 END), 0) open_cnt,
			 COALESCE(SUM(CASE WHEN ES.response = 1 THEN 1 ELSE 0 END), 0) complete,
			 COALESCE(SUM(CASE WHEN ES.response = 2 THEN 1 ELSE 0 END), 0) partial,
			 DATE(ES.add_date),DATE_FORMAT(ES.add_date, '%b') as monthname FROM sur_email_share AS ES INNER JOIN sur_survey AS S ON
			 ES.sur_id = S.sur_id AND S.is_deleted = 0 AND ES.com_id = S.com_id
		   WHERE ES.com_id = $com_id $cond GROUP BY DATE(ES.add_date) ORDER BY ES.add_date ASC";
		   $TotalSurveySent  = $prop->getAll_Disp($sql);
   $total = $email = $sms = $guest = $link = $dateSeries = array();
   for ($i = 0; $i < count($TotalSurveySent); $i++) {
   	
	if($TotalSurveySent[$i]['email'] != '' or $TotalSurveySent[$i]['sms'] != '' or $TotalSurveySent[$i]['guest'] != '' or $TotalSurveySent[$i]['link'] != '' )
   	   $dateSeries[] = $TotalSurveySent[$i]['DATE(ES.add_date)'];
       $total[] = $TotalSurveySent[$i]['tot_sent'];
       $email[] = $TotalSurveySent[$i]['email'];
       $sms[] = $TotalSurveySent[$i]['sms'];
       $guest[] = $TotalSurveySent[$i]['guest'];
       $link[]  = $TotalSurveySent[$i]['link'];
	   $graph_title  = "YTD Report";
   }
   $xAxis = "'" . implode("','", $dateSeries) ."'";
   // COLUMN 2
   $months = $common->getYrStartEndMonth();
   $start_date = date("Y-m-d", strtotime( date( 'Y-m-d' )." -5 months"));
   $end_date = date( 'Y-m-d' );
   //echo $months['start_date'].'-'.$start_date.'<br>';
   //echo $months['end_date'].'-'.$end_date.'<br>';
   $btwn_months = $common->getAllMonthsBetweenDates($start_date, $end_date);

   $data = '';
   $latest_half_year = '';
   $latest_half_year_value = '';
   $total_survey_responses = 0;
   $total_survey_tot_sent = 0;
   for ($i = 0; $i < count($btwn_months); $i++) {
       $dates = $common->getStartEndDate($btwn_months[$i]);
       $TotalAttnSurvey  = $surObj->getTotalAttnSurvey($com_id, $dates['start_date'], $dates['end_date']);
	   if($i == (count($btwn_months)-1))
	   {
	   		$latest_half_year .= "'".date("M", strtotime($btwn_months[$i]))."'";
			$latest_half_year_value .=  $TotalAttnSurvey['percentage'];
			$total_survey_responses = intval( $total_survey_responses ) + intval( $TotalAttnSurvey['attend'] );
			$total_survey_tot_sent = intval( $total_survey_tot_sent ) + intval( $TotalAttnSurvey['tot_sent'] );
		}
	   else
	   {	   		
			$latest_half_year .= "'".date("M", strtotime($btwn_months[$i]))."',";
			$latest_half_year_value .=  $TotalAttnSurvey['percentage'].',';
			$total_survey_responses = intval( $total_survey_responses ) + intval( $TotalAttnSurvey['attend'] );
			$total_survey_tot_sent = intval( $total_survey_tot_sent ) + intval( $TotalAttnSurvey['tot_sent'] );
		}
	   
   }
   $total_survey_pending = $total_survey_tot_sent - $total_survey_responses;
   /* Package Survey Restriction Start */
   $survey_restiction = packageFeaturesValidate($prop,PK_SURVEY);
   /* Package Survey Restriction End */

   ?>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
      <meta name="HandheldFriendly" content="true">
      <title>Survey 360 - Dashboard</title>
      <link rel="shortcut icon" type="image/png" href="<?php echo IMAGES; ?>favicon.png"/>
      <!-- Default  start here-->
      <!--bootstrap.css-->
      <link href="<?php echo ADMIN; ?>css/bootstrap.css" rel="stylesheet" type="text/css">
 
      <link href="<?php echo ADMIN; ?>css/menu1.css" rel="stylesheet" type="text/css">
      <!-- font awesome -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" media="all">
      <link rel="stylesheet" type="text/css" href="<?php echo ADMIN; ?>css/apexcharts.css">
      <link href="<?php echo ADMIN; ?>css/animate.css" rel="stylesheet">
      <link href="<?php echo ADMIN; ?>css/dash_style.css" rel="stylesheet">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800,900&display=swap" rel="stylesheet">
 <link href="<?php echo ADMIN; ?>css/custom-new.css?now=<?php echo time(); ?>" rel="stylesheet">
 <style>
     #cssmenu>ul>li>a {
    padding: 22px 14px;
}
 </style>
   </head>
   <body>
     <div class="stick">
  <!-- menu and top bar section -->
<section>

   <div class="blue-strip">
      <div class="container-fluid">
         <header>
            <div class=" ">
               <nav id="cssmenu">
                  <div class="logo"><a href="index.php"><img src="images/logo-s.png"/>  </a>
                  </div>
                  <div id="head-mobile"></div>
                  <div class="button"></div>
                  <ul class="herder-right-menu">
                     <li><a class="b-n" href="#">Welcome Username!as</a> </li>
                     <li><a href="#"><img src="images/users-new.png"></a> </li>
                     <li><a href="#"><img src="images/settings-new.png"></a> </li>
                     <li><a href="#"><img src="images/logout-new.png"></a></li>
                    
                  
                  </ul>
               </nav>
            </div>
         </header>
      </div>
   </div>
   <div class="row bg-title">
      <div class="col-md-9 col-sm-8">
       <ul class="nav customtab nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">Dashboard</a></li>
              <li role="presentation" class=""><a href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">Survey</a></li>
              <li role="presentation" class=""><a href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">Participants</a></li>
              <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">Reports</a></li>
               <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">Contact Us</a></li>
                <li role="presentation" class=""><a href="#settings1" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false">FAQ</a></li>
            </ul>
         </div>
         <div class="col-md-3 col-sm-4">
          <ul class="nav-tabs link-menus">
            <li><a href="#"><img src="images/create-group.png"> Add Group</a></li>
             <li><a href="#"><img src="images/add-survey.png"> Create Survey</a></li>
          </ul>
         </div>
   </div>
</section>
    <div class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="home1">
               <!-- content section dashboard -->
 <section>
     <div class="container-fluid">
      <div class="row">
         <div class="col-md-12 p-l-0 p-r-0">
            <div class="col-md-3 col-sm-6">
               <div class="box">
              <div class="box-header with-border">
                <h5 class="box-title">Total Surveys</h5>
        <div class="box-tools pull-right">
          <ul class="card-controls">
            <li class="dropdown">
            <a data-toggle="dropdown" href="#"><span class="material-icons">more_vert</span></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item active" href="#">Edit</a>
              <a class="dropdown-item" href="#">Delete</a>
          
            </div>
            </li>
           
          </ul>
        </div>
              </div>

              <div class="box-body">
                <div id="chart1">
                </div>
                
                <ul class="list-inline text-center list-count">
                                <li><h5><i class="fa fa-circle m-r-5 list-cir" style="color: #4b96e3;"></i><?php echo $total_survey_responses; ?><br><span class="complt">Complete</span></h5> </li>
                                <li><h5><i class="fa fa-circle m-r-5 list-cir1" style="color: #fbc747;"></i><?php echo $total_survey_pending; ?><br><span class="complt">Pending</span></h5> </li>
                                <!--<li><h5><i class="fa fa-circle m-r-5 list-cir2" style="color: #fb6f70;"></i>123<br><span class="complt">Reject</span></h5> </li>-->
                            </ul>
              </div>
            </div>
            </div>
              <div class="col-md-3 col-sm-6">
               <div class="box">
              <div class="box-header with-border">
                <h5 class="box-title">Survey Responses  <span>Half Yearly</span></h5>
        <div class="box-tools pull-right">
          <ul class="card-controls">
            <li class="dropdown">
            <a data-toggle="dropdown" href="#"><span class="material-icons">more_vert</span></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item active" href="#">Edit</a>
              <a class="dropdown-item" href="#">Delete</a>
   
            </div>
            </li>
           
          </ul>
        </div>
              </div>

              <div class="box-body">
                <div class="cont-tot">
                  <h3><?php echo $total_survey_responses; ?></h3>
                  <p>Online & Offline</p>
                </div>
               <div id="container-area"></div>
              </div>
            </div>
            </div>
            <div class="col-md-3 col-sm-6">
                        <div class="box">
              <div class="box-header with-border">
                <h5 class="box-title">Surveys</h5>
    
              </div>
              <div class="form-selects padd-sty">
                <div class="form-group">
                   <label for="value" class="floating">Month</label>
                  <select class="form-control" id="d_c_fliter">
                    <?php
					echo '<option value="'.date('Y-m-d').'" selected>'.date('F Y').'</option>';
					  foreach ($lastmonths as $k => $va) {
						  echo '<option value="'.$va['value'].'">'.$va['name'].'</option>';
					  }
						echo '<option value="all">All</option>';
					?>
                  </select>
                  <i class="fa fa-angle-down" aria-hidden="true"></i>
                </div>
              </div>

              <div class="box-body">
          <div class="col-md-12 p-l-0 p-r-0">
            <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
              <div class="mail-sec">
                <div class="text-right">
                  <img src="images/mail-survey.png">
                </div>
                <p><small>Mail</small></p>
                <h5 id="e_survey">1,23,456</h5>
              </div>
            </div>
            <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
               <div class="phone-sec">
                <div class="text-right">
                  <img src="images/phone-survey.png" class="mg-o">
                </div>
                <p><small>Phone</small></p>
                <h5 id="s_survey">1,23,456</h5>
              </div>
            </div>
            <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
                <div class="link-sec">
                <div class="text-right">
                  <img src="images/link-survey.png">
                </div>
                <p><small>Link</small></p>
                <h5 id="l_survey">1,23,456</h5>
              </div>
            </div>
            <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
               <div class="offline-sec">
                <div class="text-right">
                  <img src="images/offline-survey.png">
                </div>
                <p><small>Offline</small></p>
                <h5 id="o_survey">1,23,456</h5>
              </div>
            </div>
          </div>
              </div>
            </div>
            </div>
          
            <div class="col-md-3 col-sm-6">
                    <div class="box bg-clr">
              <div class="box-header with-border">
                <h5 class="box-title">Completed Surveys</h5>
        
              </div>

              <div class="box-body">
                <img src="images/total-survey.png" class="surv-img">
                <div class="cont-set">
                  <h4><span id="c_s_percentage"></span>%</h4>
                  <p>of your surveys were completed</p>
                </div>
              </div>
            </div>
            </div>
         </div>
      </div>
      <!-- total survey sent section -->
      <div class="row">
      <div class="col-md-12">
                    <div class="box m-t-0">
             <div class="box-header with-border">
                <h5 class="box-title">Total Surveys Sent</h5>
        <div class="box-tools pull-right">
          <ul class="card-controls">
            <li class="dropdown">
            <a data-toggle="dropdown" href="#"><span class="material-icons">more_vert</span></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item active" href="#">Edit</a>
              <a class="dropdown-item" href="#">Delete</a>
             
            </div>
            </li>
           
          </ul>
        </div>
              </div>

              <div class="box-body">
                  <div id="container"></div>
              </div>
            </div>
            </div>
          </div>
      <!-- total survey sent section end -->
      <!-- survey list section -->
      <div class="row mb-50">
        <div class="col-md-12">
        <h4 class="m-t-0">Recent Surveys</h4>
      </div>
       <div class="col-sm-12 col-md-12 p-r-0 p-l-0">
    <div class="col-md-2 col-md-offset-1 section-5 p-l-0 col-sm-6">
      <div class="white-box p-0">
      <div class="create-survey">
    <svg 
 xmlns="http://www.w3.org/2000/svg"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 width="55px" height="52px">
<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
 d="M46.527,44.169 C41.453,49.014 34.695,51.683 27.499,51.683 C20.304,51.683 13.546,49.014 8.472,44.169 C-2.020,34.151 -2.020,17.850 8.472,7.831 C13.546,2.985 20.304,0.317 27.499,0.317 C34.695,0.317 41.453,2.986 46.527,7.831 C57.019,17.849 57.019,34.151 46.527,44.169 ZM44.447,9.817 C39.927,5.502 33.908,3.124 27.499,3.124 C21.090,3.124 15.071,5.501 10.552,9.816 C1.207,18.739 1.207,33.258 10.552,42.183 C15.071,46.498 21.090,48.875 27.499,48.875 C33.910,48.875 39.927,46.498 44.447,42.183 C53.791,33.260 53.791,18.740 44.447,9.817 ZM40.530,27.404 L28.970,27.404 L28.969,38.442 C28.970,38.817 28.817,39.170 28.539,39.436 C28.261,39.701 27.892,39.846 27.499,39.846 L27.499,39.846 C26.688,39.845 26.029,39.215 26.028,38.442 L26.028,27.404 L14.469,27.404 C14.076,27.404 13.707,27.258 13.429,26.992 C13.151,26.726 12.998,26.374 12.999,25.999 C12.998,25.625 13.151,25.272 13.429,25.007 C13.706,24.742 14.074,24.596 14.466,24.596 L26.028,24.595 L26.028,13.558 C26.028,12.783 26.688,12.154 27.499,12.153 C27.892,12.153 28.261,12.299 28.538,12.564 C28.817,12.829 28.970,13.182 28.970,13.557 L28.970,24.595 L40.529,24.595 C41.338,24.595 42.000,25.225 42.001,25.999 C42.000,26.376 41.847,26.728 41.570,26.993 C41.293,27.257 40.924,27.403 40.530,27.404 Z"/>
</svg>
      <h6>Create Survey</h6>
      <p>Click here to create a survey</p>
      </div>
    </div>
</div>
	<div id="survey_list">
    	
	</div>    
</div>
</div>
      <!-- survey list section -->

     </div>
 </section>
                <div class="clearfix"></div>
              </div>
              <!-- tab 2 started -->
  <div role="tabpanel" class="tab-pane fade" id="profile1"> 

    <section>
      <div class="container-fluid">
        <div class="row ">
          <div class="col-md-12 tot-list p-l-0 p-r-0">
            <div class="col-md-3 col-sm-2">
              <div class="title-list">
              <h4>Survey List</h4>
            </div>
            </div>
            <div class="col-md-9">
              <div class="text-right">
                <button class="btn btn-custom">
              <svg 
               xmlns="http://www.w3.org/2000/svg"
               xmlns:xlink="http://www.w3.org/1999/xlink"
               width="20px" height="20px">
              <image  x="0px" y="0px" width="20px" height="20px"  xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAQAAABu4E3oAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfkAwsPKS3bZYpGAAABn0lEQVQ4y5WUQWsTURSFvxnSijVUi1mV6rIgkUoXxSxUdCl01d/QjeAu4LKo+Cv0J4ihka6k1la3CqEaXAYs4qYWLRoxg/O5mGQ6mUk1vXfz3n3nvDOXuecFkotzrHKdOc7ymz22eMy3HMJsLtkyViMP/eKBkRrbspZFHS1Dnxob2fTq0CVNI2MbhnlK2Y76xrIUsuyO2hmcDRQ6xj4aAR/kA2M7iVJSWFfrOdBkbl9X1weUmvoyB1jSoZ4QN9VaQnlvz+nc8Yq6UuipZ1tCKlTZ4JD/xw82uEQl5A4B98cgAKwRcDfkFhG7Y1I+EHGzxAV+pqUFZvqrKlDla3/3nVb6cXOBHU4xC8BUhpyPM3QB2CMu8YfT/XKXK6nKDR6yxutUpZtSD0p84mJ611FP54E2OwW1Mrshr5hgYcz2LzPBNlaMbRRmatSvxIaxlZB92iwzPYbGFMt8ZP8kM/ZCvXbSSX6e98u9f/ilnvfLwJVbx7hys+jKROlZ3/uLGfji8d5Pct63I16Yd85nUUHhHZtkldvMMsMvPtPkCb1hwF8keuTg/sXB0AAAAABJRU5ErkJggg==" />
              </svg>Add Survey</button>
                <div class="form-selects form-inputs p-r-0 min-lg">
                <div class="form-group m-b-0">
                   <label for="value" class="floating">Filter by</label>
                <select class="form-control">
                     <option></option>
                    <option>February 2019</option>
                     <option>March 2019</option>
                      <option>April 2019</option>
                       <option>May 2019</option>
                  </select>
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </div>
              </div>
              <div class="form-selects form-inputs">
                <div class="form-group m-b-0">
                   <label for="value" class="floating">Search</label>
                 <input type="text" name="" class="form-control">
                </div>
              </div>
                <button class="btn btn-custom grid-list">
            <img src="images/grid.png" class="img-b1" viewim="grid" id="grid">
            <img src="images/list-icons.png" style="display: none;" class="img-b2 active" viewim="list">
          </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- menu section  end -->
      <div class="list-view view_list mb-50" view="list">
     <div class="container-fluid m-b-20">
      <div class="row ">
         <div class="col-md-12">
          
            <div class="white-box p-0 col-md-12 col-sm-12 p-sm-l-0 p-sm-r-0 m-b-sm-0">
              <div class="col-md-2 col-sm-2">
                <div class="surlist-logo">
                  <img src="images/logo-darks.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-4 b-l col-sm-3"><p class="sur-nm">Focus Group Survey</p></div>
              <div class="col-md-2 col-sm-3">
                <div class="edi-moi">
                <p>Created: Aug 1, 2018 </p>
                <p>Modified: Jan 10, 2019</p>
              </div>
              </div>
              <div class="col-md-3 col-sm-2 p-sm-l-0 p-sm-r-0"><div class="queris qirus-res">
                <ul class="list-inline">
                  <li><img src="images/queries1.png"> Queries <span>8</span></li>
                  <li><img src="images/response1.png"> Response <span>8</span></li>

                </ul>
                
              </div>
              </div>
              <div class="col-md-1 col-sm-2 p-r-0 p-l-0">
                <div class="mang-list">
                    <div class="edi-t">
                                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
                <div class="del-t">
                           <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
          
          </div>
              </div>
            </div>
            
          </div>
         </div>
    
 </div>
<div class="container-fluid m-b-20">
      <div class="row">
         <div class="col-md-12">
          
            <div class="white-box p-0 col-md-12 col-sm-12 p-sm-l-0 p-sm-r-0 m-b-sm-0">
              <div class="col-md-2 col-sm-2">
                <div class="surlist-logo">
                  <img src="images/logo-darks.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-4 b-l col-sm-3"><p class="sur-nm">Focus Group Survey</p></div>
              <div class="col-md-2 col-sm-3">
                <div class="edi-moi">
                <p>Created: Aug 1, 2018 </p>
                <p>Modified: Jan 10, 2019</p>
              </div>
              </div>
              <div class="col-md-3 col-sm-2 p-sm-l-0 p-sm-r-0"><div class="queris qirus-res">
                <ul class="list-inline">
                  <li><img src="images/queries1.png"> Queries <span>8</span></li>
                  <li><img src="images/response1.png"> Response <span>8</span></li>

                </ul>
                
              </div>
              </div>
              <div class="col-md-1 col-sm-2 p-r-0 p-l-0">
                <div class="mang-list">
                    <div class="edi-t">
                                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
                <div class="del-t">
                           <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
          
          </div>
              </div>
            </div>
            
          </div>
         </div>
    
 </div>
<div class="container-fluid m-b-20">
      <div class="row">
         <div class="col-md-12">
          
            <div class="white-box p-0 col-md-12 col-sm-12 p-sm-l-0 p-sm-r-0 m-b-sm-0">
              <div class="col-md-2 col-sm-2">
                <div class="surlist-logo">
                  <img src="images/logo-darks.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-4 b-l col-sm-3"><p class="sur-nm">Focus Group Survey</p></div>
              <div class="col-md-2 col-sm-3">
                <div class="edi-moi">
                <p>Created: Aug 1, 2018 </p>
                <p>Modified: Jan 10, 2019</p>
              </div>
              </div>
              <div class="col-md-3 col-sm-2 p-sm-l-0 p-sm-r-0"><div class="queris qirus-res">
                <ul class="list-inline">
                  <li><img src="images/queries1.png"> Queries <span>8</span></li>
                  <li><img src="images/response1.png"> Response <span>8</span></li>

                </ul>
                
              </div>
              </div>
              <div class="col-md-1 col-sm-2 p-r-0 p-l-0">
                <div class="mang-list">
                    <div class="edi-t">
                                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
                <div class="del-t">
                           <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
          
          </div>
              </div>
            </div>
            
          </div>
         </div>
    
 </div>
 <div class="container-fluid m-b-20">
      <div class="row">
         <div class="col-md-12">
          
            <div class="white-box p-0 col-md-12 col-sm-12 p-sm-l-0 p-sm-r-0 m-b-sm-0">
              <div class="col-md-2 col-sm-2">
                <div class="surlist-logo">
                  <img src="images/logo-darks.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-4 b-l col-sm-3"><p class="sur-nm">Focus Group Survey</p></div>
              <div class="col-md-2 col-sm-3">
                <div class="edi-moi">
                <p>Created: Aug 1, 2018 </p>
                <p>Modified: Jan 10, 2019</p>
              </div>
              </div>
              <div class="col-md-3 col-sm-2 p-sm-l-0 p-sm-r-0"><div class="queris qirus-res">
                <ul class="list-inline">
                  <li><img src="images/queries1.png"> Queries <span>8</span></li>
                  <li><img src="images/response1.png"> Response <span>8</span></li>

                </ul>
                
              </div>
              </div>
              <div class="col-md-1 col-sm-2 p-r-0 p-l-0">
                <div class="mang-list">
                    <div class="edi-t">
                                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
                <div class="del-t">
                           <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
          
          </div>
              </div>
            </div>
            
          </div>
         </div>
    
 </div>
<div class="container-fluid m-b-20">
      <div class="row">
         <div class="col-md-12">
          
            <div class="white-box p-0 col-md-12 col-sm-12 p-sm-l-0 p-sm-r-0 m-b-sm-0">
              <div class="col-md-2 col-sm-2">
                <div class="surlist-logo">
                  <img src="images/logo-darks.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-4 b-l col-sm-3"><p class="sur-nm">Focus Group Survey</p></div>
              <div class="col-md-2 col-sm-3">
                <div class="edi-moi">
                <p>Created: Aug 1, 2018 </p>
                <p>Modified: Jan 10, 2019</p>
              </div>
              </div>
              <div class="col-md-3 col-sm-2 p-sm-l-0 p-sm-r-0"><div class="queris qirus-res">
                <ul class="list-inline">
                  <li><img src="images/queries1.png"> Queries <span>8</span></li>
                  <li><img src="images/response1.png"> Response <span>8</span></li>

                </ul>
                
              </div>
              </div>
              <div class="col-md-1 col-sm-2 p-r-0 p-l-0">
                <div class="mang-list">
                    <div class="edi-t">
                                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
                <div class="del-t">
                           <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
          
          </div>
              </div>
            </div>
            
          </div>
         </div>
    
 </div>
  <div class="container-fluid m-b-20">
      <div class="row">
         <div class="col-md-12">
          
            <div class="white-box p-0 col-md-12 col-sm-12 p-sm-l-0 p-sm-r-0 m-b-sm-0">
              <div class="col-md-2 col-sm-2">
                <div class="surlist-logo">
                  <img src="images/logo-darks.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-4 b-l col-sm-3"><p class="sur-nm">Focus Group Survey</p></div>
              <div class="col-md-2 col-sm-3">
                <div class="edi-moi">
                <p>Created: Aug 1, 2018 </p>
                <p>Modified: Jan 10, 2019</p>
              </div>
              </div>
              <div class="col-md-3 col-sm-2 p-sm-l-0 p-sm-r-0"><div class="queris qirus-res">
                <ul class="list-inline">
                  <li><img src="images/queries1.png"> Queries <span>8</span></li>
                  <li><img src="images/response1.png"> Response <span>8</span></li>

                </ul>
                
              </div>
              </div>
              <div class="col-md-1 col-sm-2 p-r-0 p-l-0">
                <div class="mang-list">
                    <div class="edi-t">
                                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
                <div class="del-t">
                           <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
          
          </div>
              </div>
            </div>
            
          </div>
         </div>
    
 </div>
</div>
<!-- grid-view -->
<div class="grid-view view_list" view="grid">
  <div class="container-fluid">
     <div class="row mb-50">
    <div class="col-md-12 col-sm-12 p-l-0 p-r-0">
    <div class="col-md-2 col-md-offset-1 section-5 p-l-0 col-sm-6">
      <div class="white-box p-0">
      <div class="create-survey">
    <svg 
 xmlns="http://www.w3.org/2000/svg"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 width="55px" height="52px">
<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
 d="M46.527,44.169 C41.453,49.014 34.695,51.683 27.499,51.683 C20.304,51.683 13.546,49.014 8.472,44.169 C-2.020,34.151 -2.020,17.850 8.472,7.831 C13.546,2.985 20.304,0.317 27.499,0.317 C34.695,0.317 41.453,2.986 46.527,7.831 C57.019,17.849 57.019,34.151 46.527,44.169 ZM44.447,9.817 C39.927,5.502 33.908,3.124 27.499,3.124 C21.090,3.124 15.071,5.501 10.552,9.816 C1.207,18.739 1.207,33.258 10.552,42.183 C15.071,46.498 21.090,48.875 27.499,48.875 C33.910,48.875 39.927,46.498 44.447,42.183 C53.791,33.260 53.791,18.740 44.447,9.817 ZM40.530,27.404 L28.970,27.404 L28.969,38.442 C28.970,38.817 28.817,39.170 28.539,39.436 C28.261,39.701 27.892,39.846 27.499,39.846 L27.499,39.846 C26.688,39.845 26.029,39.215 26.028,38.442 L26.028,27.404 L14.469,27.404 C14.076,27.404 13.707,27.258 13.429,26.992 C13.151,26.726 12.998,26.374 12.999,25.999 C12.998,25.625 13.151,25.272 13.429,25.007 C13.706,24.742 14.074,24.596 14.466,24.596 L26.028,24.595 L26.028,13.558 C26.028,12.783 26.688,12.154 27.499,12.153 C27.892,12.153 28.261,12.299 28.538,12.564 C28.817,12.829 28.970,13.182 28.970,13.557 L28.970,24.595 L40.529,24.595 C41.338,24.595 42.000,25.225 42.001,25.999 C42.000,26.376 41.847,26.728 41.570,26.993 C41.293,27.257 40.924,27.403 40.530,27.404 Z"/>
</svg>
      <h6>Create Survey</h6>
      <p>Click here to create a survey</p>
      </div>
    </div>
</div>
    <div class="col-md-2 section-5 col-sm-6">
      <div class="white-box p-0">
        <div class="surve-list">
          <p>Aug 1, 2018</p>
          <h6>Focus Group Survey</h6>
          <div class="logos-n">
            <img src="images/logo-darks.png" class="img-responsive">
          </div>
        <div class="surv-bottom">
            <div class="col-md-12 col-sm-12">
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
              <div class="queris">
                <p><img src="images/queries1.png"> Queries <span>8</span></p>
              </div>
              </div>
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
                  <div class="queris">
                <p><img src="images/response1.png"> Response <span>8</span></p>
              </div>
              </div>
            </div>
            <p class="lat-md">Last modified Feb 01, 2019</p>
          </div>
          <div class="ary-del">
               <div class="edi-t">
                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
            <div class="del-t">
                        <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
         
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-2 section-5 col-sm-6">
      <div class="white-box p-0">
         <div class="surve-list">
          <p>Aug 1, 2018</p>
          <h6>Focus Group Survey</h6>
          <div class="logos-n">
            <img src="images/logo-darks.png" class="img-responsive">
          </div>
        <div class="surv-bottom">
            <div class="col-md-12 col-sm-12">
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
              <div class="queris">
                <p><img src="images/queries1.png"> Queries <span>8</span></p>
              </div>
              </div>
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
                  <div class="queris">
                <p><img src="images/response1.png"> Response <span>8</span></p>
              </div>
              </div>
            </div>
            <p class="lat-md">Last modified Feb 01, 2019</p>
          </div>
          <div class="ary-del">
               <div class="edi-t">
                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
            <div class="del-t">
                        <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
         
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-2 section-5 col-sm-6">
      <div class="white-box p-0">
         <div class="surve-list">
          <p>Aug 1, 2018</p>
          <h6>Focus Group Survey</h6>
          <div class="logos-n">
            <img src="images/logo-darks.png" class="img-responsive">
          </div>
           <div class="surv-bottom">
            <div class="col-md-12 col-sm-12">
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
              <div class="queris">
                <p><img src="images/queries1.png"> Queries <span>8</span></p>
              </div>
              </div>
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
                  <div class="queris">
                <p><img src="images/response1.png"> Response <span>8</span></p>
              </div>
              </div>
            </div>
            <p class="lat-md">Last modified Feb 01, 2019</p>
          </div>
          <div class="ary-del">
               <div class="edi-t">
                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
            <div class="del-t">
                        <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
         
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-2 section-5 col-sm-6">
      <div class="white-box p-0">
        <div class="surve-list">
          <p>Aug 1, 2018</p>
          <h6>Focus Group Survey</h6>
          <div class="logos-n">
            <img src="images/logo-darks.png" class="img-responsive">
          </div>
          <div class="surv-bottom">
            <div class="col-md-12 col-sm-12">
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
              <div class="queris">
                <p><img src="images/queries1.png"> Queries <span>8</span></p>
              </div>
              </div>
              <div class="col-md-6 p-l-0 p-r-0 col-sm-6">
                  <div class="queris">
                <p><img src="images/response1.png"> Response <span>8</span></p>
              </div>
              </div>
            </div>
            <p class="lat-md">Last modified Feb 01, 2019</p>
          </div>
          <div class="ary-del">
               <div class="edi-t">
                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
            <div class="del-t">
                        <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
         
          </div>
        </div> 
      </div>
    </div>
     <div class="col-md-2 col-md-offset-1 section-5 p-l-0 col-sm-6">
 <div class="white-box p-0">
        <div class="surve-list">
          <p>Aug 1, 2018</p>
          <h6>Focus Group Survey</h6>
          <div class="logos-n">
            <img src="images/logo-darks.png" class="img-responsive">
          </div>
          <div class="surv-bottom">
            <div class="col-md-12">
              <div class="col-md-6 p-l-0 p-r-0">
              <div class="queris">
                <p><img src="images/queries1.png"> Queries <span>8</span></p>
              </div>
              </div>
              <div class="col-md-6 p-l-0 p-r-0">
                  <div class="queris">
                <p><img src="images/response1.png"> Response <span>8</span></p>
              </div>
              </div>
            </div>
            <p class="lat-md">Last modified Feb 01, 2019</p>
          </div>
          <div class="ary-del">
               <div class="edi-t">
                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
            <div class="del-t">
                        <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
         
          </div>
        </div>
      </div>
</div>
    <div class="col-md-2 section-5 col-sm-6">
      <div class="white-box p-0">
        <div class="surve-list">
          <p>Aug 1, 2018</p>
          <h6>Focus Group Survey</h6>
          <div class="logos-n">
            <img src="images/logo-darks.png" class="img-responsive">
          </div>
          <div class="surv-bottom">
            <div class="col-md-12">
              <div class="col-md-6 p-l-0 p-r-0">
              <div class="queris">
                <p><img src="images/queries1.png"> Queries <span>8</span></p>
              </div>
              </div>
              <div class="col-md-6 p-l-0 p-r-0">
                  <div class="queris">
                <p><img src="images/response1.png"> Response <span>8</span></p>
              </div>
              </div>
            </div>
            <p class="lat-md">Last modified Feb 01, 2019</p>
          </div>
          <div class="ary-del">
               <div class="edi-t">
                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
            <div class="del-t">
                        <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
         
          </div>
        </div>
      </div>
    </div>
     <div class="col-md-2 section-5 col-sm-6">
      <div class="white-box p-0">
         <div class="surve-list">
          <p>Aug 1, 2018</p>
          <h6>Focus Group Survey</h6>
          <div class="logos-n">
            <img src="images/logo-darks.png" class="img-responsive">
          </div>
          <div class="surv-bottom">
            <div class="col-md-12">
              <div class="col-md-6 p-l-0 p-r-0">
              <div class="queris">
                <p><img src="images/queries1.png"> Queries <span>8</span></p>
              </div>
              </div>
              <div class="col-md-6 p-l-0 p-r-0">
                  <div class="queris">
                <p><img src="images/response1.png"> Response <span>8</span></p>
              </div>
              </div>
            </div>
            <p class="lat-md">Last modified Feb 01, 2019</p>
          </div>
          <div class="ary-del">
               <div class="edi-t">
                   <svg 
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="14px" height="20px">
            <path fill-rule="evenodd"  fill="rgb(251, 199, 71)"
             d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"/>
            </svg>
            </div>
            <div class="del-t">
                        <svg 
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="16px" height="18px">
          <path fill-rule="evenodd"  fill="rgb(251, 111, 112)"
           d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"/>
          </svg>
            </div>
         
          </div>
        </div>
      </div>
    </div>
</div>
</div>

<!-- row end -->
</div>
</div>

 <!-- survey list 2 end -->
</section>
                <div class="clearfix"></div>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="messages1">
                <div class="col-md-6">
                  <h3>Come on you have a lot message</h3>
                  <h4>you can use it with the small code</h4>
                </div>
                <div class="col-md-5 pull-right">
                  <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                </div>
                <div class="clearfix"></div>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="settings1">
                <div class="col-md-6">
                  <h3>Just do Settings</h3>
                  <h4>you can use it with the small code</h4>
                </div>
                <div class="col-md-5 pull-right">
                  <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
 <!-- menu and top bar section -->

 <!-- content section dashboard -->
<footer class="footer text-center"> Copyright © Survey 360. All rights reserved. </footer>

      <script src="<?php echo ADMIN; ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <?php include("footer.php"); ?>
      <script  src="<?php echo ADMIN; ?>js/bootstrap.min.js"></script>
      <script src="<?php echo ADMIN; ?>js/jquery.slimscroll.js"></script>
      <script src="<?php echo ADMIN; ?>js/menu1.js"></script>
      <script type="text/javascript" src="js/apexcharts.min.js"></script>
      <script src="<?php echo ADMIN; ?>js/common-jsFunction.js"></script>
      <script type="text/javascript">
	  /*Dynamic variable define*/
	   var com_id = '<?php echo $com_id ?>';
	   
/*Total Surveys Chart Start*/
var options1 = {
  chart: {
    height: 250,
    type: "radialBar",
  },
  series: [<?php echo round($total_survey_responses / $total_survey_tot_sent * 100); ?>, <?php echo round($total_survey_pending / $total_survey_tot_sent * 100); ?>],
  fill: {
  colors: ['#4b96e3', '#fbc747']
},
  plotOptions: {
    radialBar: {
      dataLabels: {
        total: {
          show: false,
          label: 'TOTAL'
        }
      },
      track: {
         margin: 10, 
      },
    }
  },
  labels: ['Complete', 'Pending']
};
new ApexCharts(document.querySelector("#chart1"), options1).render();
/*Total Surveys Chart END*/
</script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
//function Total_Surveys_Sent_chart(xAxis,)
function chartFilterMonth(response){
 Highcharts.chart('container', {
    chart: {
        zoomType: 'xyas'
    },
    title: {
        text: ''
        
    },
    subtitle: {
        text: ''
      
    },
    credits: {
        enabled: false
    },
    legend: {
        enabled: false
    },
    xAxis: [{
        categories: response.xAxis,
                labels: {
          
            style: {
                color: Highcharts.getOptions().colors[1],
                color: '#000',
           fontSize:'12px',
                   fontFamily: 'Nunito'
            }
        }
        
    }],
    yAxis: [{ 
        labels: {
           text: '',
            format: '{value} k',
            style: {
                color: Highcharts.getOptions().colors[1],
                color: '#000',
           fontSize:'12px',
                   fontFamily: 'Nunito'
            }
        },
         title: {
            text: '',
            style: {
                color: Highcharts.getOptions().colors[2],
                fontSize:'15px',
                   fontFamily: 'Nunito'
            }
        },
 gridLineWidth: 0,
  minorGridLineWidth: 0,
  style: {
                color: '#000',
           fontSize:'15px',
                   fontFamily: 'Nunito',
         },
    },

     { // Secondary yAxis
        gridLineWidth: 0,
        title: {
            text: '',
            style: {
                color: Highcharts.getOptions().colors[2],
                fontSize:'15px',
                   fontFamily: 'Nunito'
            }
        },
        labels: {
           text: '',
            format: '',
            style: {
                color: Highcharts.getOptions().colors[0],
                fontSize:'15px',
                   fontFamily: 'Nunito'
            }
        }

    }],
    tooltip: {
        shared: true
    },
    legend: {
        enabled: false,
    },
    series: [{
        name: 'Email',
        type: 'column',
       
        data: response.email
        
        

    }, {
        name: 'SMS',
        type: 'column',
        data: response.sms,
         color: "#fbc747",
        
    }, {
        name: 'Link',
        type: 'spline',
        data: response.link,
         color: "#fb6f70",
        
    }, {
        name: 'Guest',
        type: 'spline',
        data: response.guest,
         color: "#0b5f94",
        
    }],
    
});
}
  Highcharts.chart('container', {
    chart: {
        zoomType: 'xyas'
    },
    title: {
        text: ''
        
    },
    subtitle: {
        text: ''
      
    },
    credits: {
        enabled: false
    },
    legend: {
        enabled: false
    },
    xAxis: [{
        categories: [<?php echo $xAxis; ?>],
                labels: {
          
            style: {
                color: Highcharts.getOptions().colors[1],
                color: '#000',
           fontSize:'12px',
                   fontFamily: 'Nunito'
            }
        }
        
    }],
    yAxis: [{ 
        labels: {
           text: '',
            format: '{value} k',
            style: {
                color: Highcharts.getOptions().colors[1],
                color: '#000',
           fontSize:'12px',
                   fontFamily: 'Nunito'
            }
        },
         title: {
            text: '',
            style: {
                color: Highcharts.getOptions().colors[2],
                fontSize:'15px',
                   fontFamily: 'Nunito'
            }
        },
 gridLineWidth: 0,
  minorGridLineWidth: 0,
  style: {
                color: '#000',
           fontSize:'15px',
                   fontFamily: 'Nunito',
         },
    },

     { // Secondary yAxis
        gridLineWidth: 0,
        title: {
            text: '',
            style: {
                color: Highcharts.getOptions().colors[2],
                fontSize:'15px',
                   fontFamily: 'Nunito'
            }
        },
        labels: {
           text: '',
            format: '',
            style: {
                color: Highcharts.getOptions().colors[0],
                fontSize:'15px',
                   fontFamily: 'Nunito'
            }
        }

    }],
    tooltip: {
        shared: true
    },
    legend: {
        enabled: false,
    },
    series: [{
        name: 'Email',
        type: 'column',
       
        data: [<?php echo implode(", ", $email) ?>]
        
        

    }, {
        name: 'SMS',
        type: 'column',
        data: [<?php echo implode(", ", $sms) ?>],
         color: "#fbc747",
        
    }, {
        name: 'Link',
        type: 'spline',
        data: [<?php echo implode(", ", $link) ?>],
         color: "#fb6f70",
        
    }, {
        name: 'Guest',
        type: 'spline',
        data: [<?php echo implode(", ", $guest) ?>],
         color: "#0b5f94",
        
    }],
    
});


 var colors = ['#8cbe7b'];
  Highcharts.chart('container-area', {
    chart: {
        type: 'area',
         marginLeft: 0,
         marginRight: 0,
        spacingLeft: 0,
        spacingRight: 0,

      
    },
    title: {
        text: ''
    },
     credits: {
        enabled: false
    },
    legend: {
        enabled: false
    },
    colors:colors,
    subtitle: {
        text: ''
    },

    xAxis: {
      
        categories: [<?php echo $latest_half_year; ?>],
        labels: {
            formatter: function () {
                return this.value; // clean, unformatted number for year
            },
              style: {
                color: '#000',
           fontSize:'11px',
                   fontFamily: 'Nunito',
                   fontWeight: 'bold'
         }
        },
        accessibility: {
            rangeDescription: ''
        },
        style: {
                color: '#000',
           fontSize:'14px',
                   fontFamily: 'Nunito',
                   fontWeight: 'bold'
         }
    },
    yAxis: {
        title: {
            text: ''
        },
        labels: {
            enabled: false
        },
        gridLineWidth: 0,
  minorGridLineWidth: 0,
    },
    tooltip: {
      enabled: true,
        // pointFormat: '{series.name} {point.y:,.0f}'
        pointFormat: '<span>{point.y:.f}</span>'
    },
    plotOptions: {
      series: {
           fillOpacity: 0.5
        },
        area: {
           
            marker: {
                enabled: false,
                symbol: 'circle',
                radius: 3,
                states: {
                    hover: {
                        enabled: true
                    }
                }
            }
        }
    },
    series: [{
        name: 'Survey',
        data: [
            <?php echo $latest_half_year_value ; ?>
        ]
    }]
});
$(document).ready(function(){
     $('.list-view').hide();
	 $('.grid-list').click(function(){
		 var id = $('.img-b1').attr('id');
		 if (id=='grid') {
			 $('.img-b1').attr('src', 'images/list-icons.png');
			 $('.img-b1').attr('id', 'list');
			 $('.list-view').show();
			 $('.grid-view').hide();
		 } else {
			 $('.img-b1').attr('src', 'images/grid.png');
			 $('.img-b1').attr('id', 'grid');
			 $('.list-view').hide();
			 $('.grid-view').show();
		 }

     });
	 displayRecords(4, 0, 'html', asyncv = false,'','all_survey'); 
	 /**/
	 $("#d_c_fliter").on("change", function(){
		 var $month = $(this).val();
		 //callHighChartData($(this).val());
		 XHRCall({data: {fn: 'dash-counts', com_id: com_id, month: $month}, async: true, url: 'ajax-survey-methods.php'}, LoadDashCounts);
		 callHighChartData($(this).val());
	 });
	 var $month = $("#d_c_fliter").val();
     XHRCall({data: {fn: 'dash-counts', com_id: <?php echo $com_id; ?>, month: $month}, async: true, url: 'ajax-survey-methods.php'}, LoadDashCounts);
	 /**/
});
function callHighChartData(date){
	$.ajax({url: "dashboard.php?action=chart&d="+date,dataType: "json", success: function(response){
		chartFilterMonth(response);
	}});
}
/*Recent Survey Start*/
function displayRecords(lim, off, mode, asyncv = false, s_val, showBy) {

             //var s_val   = $.trim($("#survey_search").val()); // Search Box Text
             //var showBy  = $("#show_survey_by :selected").val();

             $.ajax({
               type: "POST",
               async: (asyncv == 'true')? true : false,
               url: "ajax-survey-methods.php",
               data: {fn: showBy, com_id: com_id, cur_offset: off, limit:lim, value:s_val},
               cache: false,
               beforeSend: function() {
                 $("#loader_message").html('<div class="col-md-12 col-sm-12 col-xs-12"><div class="alert alert-info"><i class="fa fa-spinner"></i> <strong>Please wait loading...</strong></div></div>').show();
                 $('#loader_image').show();
                 console.log("loading..");
               },
               success: function(response) {
                 response = $.parseJSON(response);
                 console.log(response);
                 $('#loader_image').hide();
                 var arr = response.data;
                 var content = '';
                 if(arr.length > 0){
                     for(var i=0; i < arr.length; i++){
                       var $currObj = arr[i];
                       content += '<div class="col-md-2 section-5 col-sm-6"><div class="white-box p-0"><div class="surve-list"><p>'+$currObj['create_on']+'</p><h6><a href="survey.php?sur_id='+$currObj.sur_id+'">'+$currObj['survey_name']+'</a></h6><div class="logos-n"><img src="images/survey_logo/'+$currObj['sur_logo']+'" class="img-responsive"></div><div class="surv-bottom"><div class="col-md-12"><div class="col-md-6 p-l-0 p-r-0"><div class="queris"><p><img src="images/queries1.png"> Questions <span>'+$currObj['Qcnt']+'</span></p></div></div><div class="col-md-6 p-l-0 p-r-0"><div class="queris"><p><img src="images/response1.png"> Response <span>'+$currObj['Response']+'</span></p></div></div></div><p class="lat-md">Last modified '+$currObj['updated_on']+'</p></div> <div class="ary-del"><div class="edi-t" id="del'+$currObj['sur_id']+'">											   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="20px">	<path fill-rule="evenodd" fill="rgb(251, 199, 71)" d="M13.222,19.375 L1.197,19.375 C0.917,19.375 0.690,19.145 0.690,18.862 C0.690,18.580 0.917,18.350 1.197,18.350 L13.222,18.350 C13.502,18.350 13.729,18.580 13.729,18.862 C13.729,19.145 13.501,19.375 13.222,19.375 ZM4.882,14.576 C4.582,15.009 4.000,15.503 3.526,15.725 L1.393,16.727 C1.343,16.744 1.172,16.801 1.061,16.801 C0.918,16.801 0.781,16.759 0.666,16.677 C0.454,16.527 0.347,16.275 0.372,15.985 L0.573,13.636 C0.618,13.109 0.878,12.387 1.177,11.956 L8.247,1.752 L8.354,1.598 C8.358,1.593 8.362,1.587 8.366,1.581 L8.460,1.445 L8.466,1.450 C8.894,0.930 9.532,0.625 10.209,0.625 C10.674,0.625 11.121,0.768 11.503,1.037 C12.524,1.760 12.773,3.188 12.058,4.219 L4.882,14.576 ZM2.008,12.543 C1.815,12.822 1.613,13.385 1.583,13.724 L1.424,15.583 L3.099,14.796 C3.404,14.653 3.858,14.268 4.051,13.989 L4.305,13.622 L2.262,12.177 L2.008,12.543 ZM2.844,11.337 L4.887,12.782 L9.413,6.250 L7.370,4.805 L2.844,11.337 ZM10.921,1.877 C10.711,1.728 10.464,1.649 10.208,1.649 C9.800,1.649 9.418,1.850 9.184,2.187 L7.952,3.965 L9.995,5.411 L11.227,3.632 C11.621,3.063 11.484,2.276 10.921,1.877 Z"></path></svg></div><div class="del-t" id="del'+$currObj['sur_id']+'">													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="18px">									  <path fill-rule="evenodd" fill="rgb(251, 111, 112)" d="M14.158,6.173 L14.151,6.173 L13.611,15.184 C13.543,16.413 12.462,17.413 11.202,17.413 L4.796,17.413 C3.535,17.413 2.455,16.413 2.387,15.185 L1.847,6.174 L1.840,6.174 C1.046,6.174 0.399,5.543 0.399,4.767 L0.399,4.305 C0.399,3.530 1.046,2.899 1.840,2.899 L4.189,2.899 L4.189,1.993 C4.189,1.218 4.836,0.587 5.630,0.587 L10.368,0.587 C11.162,0.587 11.808,1.218 11.808,1.993 L11.808,2.898 L14.158,2.898 C14.952,2.898 15.598,3.529 15.599,4.305 L15.599,4.767 C15.599,5.543 14.952,6.173 14.158,6.173 ZM3.372,15.133 C3.412,15.859 4.051,16.450 4.796,16.450 L11.202,16.450 C11.947,16.450 12.586,15.859 12.626,15.133 L13.164,6.173 L2.834,6.173 L3.372,15.133 ZM10.822,1.993 C10.822,1.749 10.618,1.549 10.368,1.549 L5.630,1.549 C5.380,1.549 5.176,1.749 5.176,1.993 L5.176,2.899 L10.822,2.899 L10.822,1.993 ZM14.612,4.305 C14.612,4.060 14.408,3.861 14.158,3.861 L1.840,3.861 C1.590,3.861 1.386,4.060 1.386,4.305 L1.386,4.767 C1.386,5.012 1.590,5.211 1.840,5.211 L14.157,5.211 C14.408,5.211 14.612,5.012 14.612,4.767 L14.612,4.767 L14.612,4.305 ZM10.574,15.101 L10.558,15.101 C10.429,15.097 10.307,15.044 10.216,14.951 C10.125,14.857 10.077,14.735 10.081,14.606 L10.285,7.991 C10.292,7.728 10.509,7.523 10.778,7.523 C10.923,7.526 11.045,7.579 11.136,7.673 C11.226,7.766 11.274,7.888 11.271,8.017 L11.067,14.632 C11.059,14.895 10.843,15.101 10.574,15.101 ZM7.999,15.101 C7.727,15.101 7.506,14.885 7.506,14.620 L7.506,8.004 C7.506,7.739 7.727,7.523 7.999,7.523 C8.271,7.523 8.492,7.739 8.492,8.004 L8.492,14.620 C8.492,14.885 8.270,15.101 7.999,15.101 ZM5.437,15.101 L5.424,15.101 C5.155,15.101 4.939,14.895 4.932,14.632 L4.727,8.017 C4.720,7.751 4.935,7.530 5.207,7.523 C5.489,7.523 5.706,7.728 5.713,7.991 L5.918,14.606 C5.921,14.735 5.873,14.857 5.782,14.951 C5.691,15.044 5.569,15.097 5.437,15.101 Z"></path></svg></div></div></div></div></div>';
                     }
                     $("#loader_message").html('');
					if(mode == 'html'){
					   $("#survey_list").html(content);
					 } else {
					   $("#survey_list").append(content);
					 }
                 } else {
					 if(mode == 'html'){
					   $("#survey_list").html(content);
					 } else {
					   $("#survey_list").append(content);
					 }
					 if($("#survey_list").html().length == 0){
						 $("#loader_message").html('<div class="col-md-12 col-sm-12 col-xs-12"><div class="alert alert-danger"><i class="fa fa-info-circle"></i> <strong>No records found..</strong></div></div>').show();
					 } else{$("#loader_message").html('');}
                 }

                 window.busy = false;
		  		return false;
               }
             });
           }
/*Recent Survey END*/
/*LoadDashCounts */
function LoadDashCounts(response) {
  console.log('LoadDashCounts');
  console.log(response);
  var $dashcount = response.data;
  $('#tot_survey').html($dashcount.tot_sent);
  $('#e_survey').html($dashcount.email);
  $('#s_survey').html($dashcount.sms);
  $('#l_survey').html($dashcount.link);
  $('#c_s_count').html($dashcount.complete);
  $('#o_survey').html(Number($dashcount.guest));/*Number($dashcount.partial) +*/
  $('#c_s_percentage').html($dashcount.percentage);
 /* $('.counter').counterUp({
        delay: 1,
        time: 40,
      });*/
}
/**/
</script>
    </div>
   </body>
</html>
