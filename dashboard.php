<?php
   require_once("inc/config.php");
   require_once("session_check.php");
   require_once("inc/classes/class.survey.php");

   $com_id = COM_ID;
   $surObj = new SurveyFunctions($prop, $common);
   $cdat = date('Y-m-d', strtotime($_SESSION['U']['cdate']));
   $lastmonths  = $surObj->getLastMonthsNew(4, 'array', $cdat);
   //COLUM 1
   $dates = $common->getMonthStartEndDate();//getLastMonthDates(6);

   if(isset($_GET['action']) && $_GET['action']=='chart' && $_GET['d']!='' && $_GET['d']!='undefined'){
   	$start_date = $_GET['d'];
   	if($start_date!='all'){
   		$date = new DateTime($start_date);
   		$date->modify('first day of this month');
   		$start_date = $date->format('Y-m-d');
   		$date->modify('last day of this month');
   		$end_date = $date->format('Y-m-d');
   		$TotalSurveySent  = $surObj->getTotalSentSurvey($com_id, $start_date, $end_date);
   	}else{
   		$sql = "SELECT COUNT(ES.ref_id) as tot_sent,
             COALESCE(SUM(CASE WHEN ES.mode_of_sent = '2' THEN 1 ELSE 0 END), 0) email,
             COALESCE(SUM(CASE WHEN ES.mode_of_sent = '1' THEN 1 ELSE 0 END), 0) sms,
             COALESCE(SUM(CASE WHEN ES.mode_of_sent = '3' THEN 1 ELSE 0 END), 0) guest,
             COALESCE(SUM(CASE WHEN ES.mode_of_sent = '4' THEN 1 ELSE 0 END), 0) link,
             COALESCE(SUM(CASE WHEN ES.is_open = 1 THEN 1 ELSE 0 END), 0) open_cnt,
             COALESCE(SUM(CASE WHEN ES.response = 1 THEN 1 ELSE 0 END), 0) complete,
             COALESCE(SUM(CASE WHEN ES.response = 2 THEN 1 ELSE 0 END), 0) partial,
             DATE(ES.add_date) FROM ".EMAIL." AS ES INNER JOIN ".SURVEY." AS S ON
             ES.sur_id = S.sur_id AND S.is_deleted = 0 AND ES.com_id = S.com_id
           WHERE ES.com_id = $com_id GROUP BY DATE(ES.add_date) ORDER BY ES.add_date ASC";
           $TotalSurveySent  = $prop->getAll_Disp($sql);
   	}
   	$total = $email = $sms = $guest = $link = $dateSeries = array();
   	for ($i = 0; $i < count($TotalSurveySent); $i++) {
   		$dateSeries[] = $TotalSurveySent[$i]['DATE(ES.add_date)'];
   		$total[] = 1*$TotalSurveySent[$i]['tot_sent'];
   		$email[] = 1*$TotalSurveySent[$i]['email'];
   		$sms[] = 1*$TotalSurveySent[$i]['sms'];
   		$guest[] = 1*$TotalSurveySent[$i]['guest'];
   		$link[]  = 1*$TotalSurveySent[$i]['link'];
   	}
   	echo json_encode(array('xAxis'=>$dateSeries,'email'=>$email,'sms'=>$sms,'guest'=>$guest,'link'=>$link)); exit;
   }
   $TotalSurveySent  = $surObj->getTotalSentSurvey($com_id, $dates['start_date'], $dates['end_date']);
   $total = $email = $sms = $guest = $link = $dateSeries = array();
   for ($i = 0; $i < count($TotalSurveySent); $i++) {
   	$dateSeries[] = date("m/d/Y", strtotime($TotalSurveySent[$i]['DATE(ES.add_date)']));
       $total[] = $TotalSurveySent[$i]['tot_sent'];
       $email[] = $TotalSurveySent[$i]['email'];
       $sms[] = $TotalSurveySent[$i]['sms'];
       $guest[] = $TotalSurveySent[$i]['guest'];
       $link[]  = $TotalSurveySent[$i]['link'];
   }
   $xAxis = "'" . implode("','", $dateSeries) ."'";
   // COLUMN 2
   $months = $common->getYrStartEndMonth();
   $btwn_months = $common->getAllMonthsBetweenDates($months['start_date'], $months['end_date']);

   $data = '';
   for ($i = 0; $i < count($btwn_months); $i++) {
       $dates = $common->getStartEndDate($btwn_months[$i]);
       $TotalAttnSurvey  = $surObj->getTotalAttnSurvey($com_id, $dates['start_date'], $dates['end_date']);

       $data .= '{
                 "name": "'.date("M", strtotime($btwn_months[$i])).'",
                 "y": '.$TotalAttnSurvey['percentage'].',
               },';
   }
   $data_arr = '"data": ['.rtrim($data, ",").']';

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
      <link href="<?php echo ADMIN; ?>css/custom.css" rel="stylesheet" type="text/css">
      <link href="<?php echo ADMIN; ?>css/dwell.css" rel="stylesheet" type="text/css">
      <link href="<?php echo ADMIN; ?>css/menu1.css" rel="stylesheet" type="text/css">
      <!-- font awesome -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" media="all">
      <!--font for Dwellvo-->
      <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
      <!--menu -->
      <link href="<?php echo ADMIN; ?>css/animate.css" rel="stylesheet">
      <link href="<?php echo ADMIN; ?>css/only.css" rel="stylesheet">

      <link href="<?php echo ADMIN; ?>css/dash_style.css" rel="stylesheet">
      <!--multi Select -->
      <link href="<?php echo ADMIN; ?>plugins/bower_components/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
      <!-- Default  end here-->
      <!-- BALAKUMAR BA STYLES INCLUDE HERE -->
      <!--<link href="<?php echo ADMIN; ?>css/style-nav.css" rel="stylesheet" type="text/css" /> -->
      <!-- BALAKUMAR BA STYLES INCLUDES END HERE-->
      <style>


      .d-blk{display: block !important}
         .survey-text {
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
         width: 250px;
         }
         .survey-text .emb-spn {
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
         }
         .dot-cap .five {
         padding-left: 11px;
         color: #9732ad;
         font-size: 20px;
         position: relative;
         top: 4px;
         }
         .white-box.first1.last {
         overflow: hidden;
         min-height: 88px !important;
         }
         .dots-main.pen .dropdown-menu {
         left: 260px;
         }
         .pen .btn-primary:focus, .btn-primary.focus {background:none;border:none;}
         ul.dash-same{padding:0;    text-align: center;}
         ul.dash-same li{list-style-type: none;padding:0;display:inline-block;width:22%;text-align: -webkit-center;}
         .dash-same{margin-top: -13px;}
         @media only screen and (max-width: 991px) and (min-width: 768px){
         .blue-strip{padding-bottom:30px;}
         #cssmenu ul li:first-child{border-top:none;}
         #cssmenu ul {
         margin-top: 18px;
         }
         .tab-eclip {
         position: relative;
         top: 14px;
         right: -31px;
         }
         .own-container {
         padding: 0px 1%;
         }
         }
         .t1 {
         display: table;
         padding: 0px 0px 0px;
         margin: 1px 0px;
         width: 100%;
         font-weight: 600;
         padding-top: 0px;
         }
         .t1-img {
         width: 40px;
         display: table-cell;
         position: relative;
         margin: 0 10px 0 0;
         }
         .t1-content {
         display: table-cell;
         padding-left: 0px;
         vertical-align: top;
         }
         .m-t-10{
         margin-top: 10px;
         }

		  .btn-primary:active, .btn-primary.active, .open > .dropdown-toggle.btn-primary{background-color: transparent; border-color: transparent; }

         @media only screen and (max-width: 991px) {
         .survey-text {
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
         width: 194px;
         }
         .t1-img img {
     max-width: 100%;
     margin: 0 auto;
     float: none;
     max-height: 100%;
 }
         }
		  @media only screen and (max-width: 767px) and (min-width: 320px)
		  {
			  .second-strip.d1 h4{text-align: center;}
			  .analytics-info.inf_count.text-left ul.list-inline.two-part.run {
    text-align: center;
}
		  .white-box.first1.bg-blue {
    margin-top: 10px;
}
		  .b-box1 .run .count-black {
    font-size: 35px !important;
}

		  .dash-same i.fa {
    line-height: 30px;
    font-size: 14px;
}
		  .dot {
    height: 30px;
    width: 30px;
}
		  .dot1 {
    height: 30px;
    width: 30px;
}
		  .dot2 {
    height: 30px;
    width: 30px;
}
		  .dot2 img {
    width: 33px;
}
		  .dot-cap .two {
    font-size: 26px;
}
.white-box.first1.effect.b-comp ul.list-inline.two-part.run {
    text-align: center;
}
		  .white-box.first1.effect.b-comp h4.text-right.capt {
    text-align: center;
}
	.white-box.first1.effect.b-comp {
    margin: 0px;
    padding: 0px 25px;
}
		  select#show_survey_by {
    margin: 0px;
}
			  #carousel-example-captions .col-sm-6.col-md-6.pl-10 {
    padding-left: 0px;
    margin-top: 10px;
				  padding-right: 10px;
}
			  .b-box1 .total {
    margin: 0 0px 5px;
    text-align: center;
}
			  .analytics-info ul.list-inline.two-part.run {
    text-align: center;
}
		  }

		  @media (min-width: 768px) and (max-width: 1024px) {

  991px) and (min-width: 768px)
.white-box.first1 {
    min-height: auto !important;
}
			  .second-strip .own-dash h4 {
    font-size: 20px;
}
			  .inf_count .run .count-black {
    font-size: 55px !important;
}
			  .b-box1 .run .count-black {
    font-size: 45px !important;
}
			  .white-box.first1 {
    min-height: auto !important;
    /*padding: 15px 25px !important;*/
    margin-bottom: 0px !important;
}
			  .second-strip .own-dash h4 {
    margin: 0px;
}
			  .white-box.first1.p_0_px {
    padding: 15px 0px !important;
}

		  }
      .dots-main.ben .dropdown-menu {
          left: 163px;
      }
      .dots-main {
          left: 56px;}

      .dots-main.ben{left: 33px;
          text-align: center;}

      .del-g{margin-left:14px;border: 1px solid #686868;
          padding: 4px;
          border-radius: 6px;}
      .pen-g{border: 1px solid #686868;
          padding: 4px;
          border-radius: 6px;}
      @media only screen and (min-device-width: 320px) and (max-device-width: 767px)
      {
          .dropdown.dots-main.ben {
              position: absolute;
              right: 10px;
              top: -100px;
              text-align: right;
          }
      }
      </style>
   </head>
   <body>
     <div class="stick">
      <?php include 'header.php';?>
      <?php if ($_SESSION['S']['dashboard_strip']==0 && $_SESSION['U']['31day']==0) {
             ?>
      <section>
         <div class="col-md-12 col-sm-12 col-xs-12 p-0 closepop">
            <div class="bg-first">
               <div class="container container1">
                  <div class="first-content">
                     <div class="col-md-6 col-sm-7 col-xs-6 p-0 welcome">
                      <p>Welcome back, <span class="intro-name"><?php echo $_SESSION['U']['first_name'].($_SESSION['U']['last_name']?('&nbsp;'.$_SESSION['U']['last_name']):'');?>!</span></p>
                     </div>
                     <div class="col-md-6 col-sm-5 col-xs-6 p-0 text-right fortb1">
                        <!--<button type="button" class="btn btn-upgrade">Upgrade</button>--><img id="upgrad" class="cancel" src="images/cancel.png">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
      </section>
      <?php
         } ?>
      <div class="second-strip d1 b-sec">
         <!--  PAGE CONTENT BODY-->
         <div class="col-md-12 p-0">
            <div class="own-container">
               <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="overview">
                     <h4 class="m-t-l">Overview</h4>
                  </div>
               </div>
               <div class="col-md-6 col-sm-6 col-xs-6 text-right">
				  <button type="button" onClick="location.href='survey.php';" class="btn btn-create">+ Create Survey</button>
                  <?php /*if($survey_restiction){ ?>
                  <button type="button" onclick="location.href='survey.php';" class="btn btn-create">+ Create Survey</button>
                  <?php }else{ ?>
                  <button type="button" onclick="location.href='contact-us.php?action=upgrade';" class="btn btn-create">Upgrade Package</button>
                  <?php }*/ ?>
               </div>
            </div>
            <!-- ./own-container -->
         </div>
         <!-- ./col-md-12 p-0 -->
         <div class="clearfix"></div>
         <div class="own-container own-dash">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dash_counts">
			  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0 pr-10">
			     <div class="b-box1 bg-gray">
					 <p class="capt total">Total Surveys Sent</p>
					 <div class="analytics-info">
						<div class="col-md-12 col-sm-12 col-xs-12 bd-rgt">
						   <ul class="list-inline two-part run">
							  <li> <span class="counter text-success count-black" id="tot_survey"></span></li>
						   </ul>
						</div>
					 </div>
				 </div>
			  </div>
			  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right pr-0 pl-10">
			    <div class="b-box2 bg-gray">
				 <select class="form-control cus-drop1" id="d_c_fliter">
				 <?php
					echo '<option value="'.date('Y-m-d').'" selected>'.date('F Y').'</option>';
					  foreach ($lastmonths as $k => $va) {
						  echo '<option value="'.$va['value'].'">'.$va['name'].'</option>';
					  }
						echo '<option value="all">All</option>';
					?>
				 </select>
				 <div class="dot-cap">
					<ul class="dash-same">
					   <li>
						  <span class="dot"><i class="fa fa-envelope"></i><img class="hide" src="images/images-dash/email.png"/></span><span class="two" id="e_survey"><?=$DashCounts['email']?></span>
					   </li>
					   <li> <span class="dot1"><i class="fa fa-mobile"></i><img class="hide" src="images/images-dash/phone.png"/></span><span class="two" id="s_survey"><?=$DashCounts['sms']?></span></li>
					   <li> <span class="dot2"><i class="fa fa-link"></i><img class="hide" src="images/images-dash/link.png"/></span><span class="two" id="l_survey"><?=$DashCounts['link']?></span> </li>
					   <li> <span class="dot2" id="p_survey"><img src="images/images-dash/icon-offline.png"/></span> <span id="o_survey" class="two" ><?=$DashCounts['guest']+$DashCounts['partial']?></span> </li>
					</ul>
				 </div>
				 </div>
			  </div>

               <!-- ./white-box first1 -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 dash_counts">
               <div class="white-box first1 bg-blue">
                  <h4 class="text-center capt">Number of Completed Survey</h4>
                  <div class="analytics-info inf_count text-left">
                     <ul class="list-inline two-part run">
                        <li> <span class="counter text-success count-black" id="c_s_count"><?=$DashCounts['complete']?></span></li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 dash_counts">
               <div class="white-box first1 effect b-comp">
                  <div class="analytics-info text-right">
                     <ul class="list-inline two-part run">
                        <li style="color:#fff;"><span class="counter text-success count-black three"  style="color:#fff;" id="c_s_percentage"><?php
                           $_PerCompRtio = 0;
                           if ($DashCounts['tot_sent']>0) {
                               $_PerCompRtio = round(($DashCounts['complete']/$DashCounts['tot_sent'])*100);
                           }
                           echo $_PerCompRtio;?>
                           </span><span class="perc" style="color:#fff;">%</span>
                        </li>
                     </ul>
                  </div>
                  <h4 class="text-right capt" style="color:#fff;">of Your Surveys <br/>Were Completed.</h4>
               </div>
            </div>
            <!-- ./dash_counts -->
            <div class="clearfix"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="white-box first1 p_0_px">
                  <!-- START carousel-->
                  <div id="carousel-example-captions" data-ride="carousel" class="carousel slide">
                     <div role="listbox" class="carousel-inner">
                        <div class="carousel-item active">
                           <div id="container-master" class="col-sm-6 col-md-6">
                              <div id="container" style="min-width: 310px;height: 420px; margin:0 auto"></div>
                           </div>
                           <div class="col-sm-6 col-md-6 pl-10">
                              <div id="container1" style="min-width: 310px;height: 420px; margin:0 auto"></div>
                           </div>
                        </div>
                     </div>
                     <a href="#carousel-example-captions" role="button" data-slide="prev" class="left carousel-control"> <span aria-hidden="true" class="fa fa-angle-left"></span> <span class="sr-only">Previous</span> </a>
                     <a href="#carousel-example-captions" role="button" data-slide="next" class="right carousel-control"> <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Next</span> </a>
                  </div>
               </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3 col-sm-4 col-xs-12 mb-surv1">
               <select class="form-control cus-drop b-arw" id="show_survey_by">
                  <option value="recent_survey" selected>Recent Surveys</option>
                  <option value="all_survey" >All Surveys</option>
               </select>
            </div>
            <div class="col-md-5 col-sm-2 ">
            </div>
            <div class="col-sm-6 col-md-4 col-xs-12 text-right mb-surv1">
               <input type="text" class="cus-search form-control" id="survey_search" placeholder="Search.." name="search">
            </div>
            <div class="clearfix"></div>
            <div class=" m-t-10">
               <div class="" id="survey_list" >
               </div>
               <!-- mydiv -->
               <div id="loader_image"><img src="images/loader.gif" alt="" width="24" height="24"> Loading...please wait</div>
               <div class="margin10"></div>
               <div id="loader_message"></div>
            </div>
         </div>
         <!-- PAGE CONTENT BODY END -->
      </div>
      <!--second-strip-->
      <!--Delete-Newsletter-->
      <div class="modal fade del-c newsletter-delete-modal-show" id="myModal-delete" role="dialog">
         <div class="modal-dialog modal-md">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Delete Survey</h4>
               </div>
               <div class="modal-body">
                  <h4 class="text-left sure">Are you sure that you want to delete?</h4>
               </div>
               <div class="modal-footer">
                  <input type="hidden" id="delid" value="">
                  <button type="button" class="btn btn-default clos" id="sc_delete_confirm"><i class="fa fa-times" aria-hidden="true"></i>Delete</button>
                  <button type="button" class="btn btn-default don delpop-close" data-dismiss="modal">Cancel</button>
               </div>
            </div>
         </div>
      </div>

      <script src="<?php echo ADMIN; ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <?php include("footer.php"); ?>
      <script  src="<?php echo ADMIN; ?>js/bootstrap.min.js"></script>
      <script src="<?php echo ADMIN; ?>js/jquery.slimscroll.js"></script>
      <script src="<?php echo ADMIN; ?>js/menu1.js"></script>
      <!-- BALAKUMAR BA - WEB DEVELOPER EXTERANL JS FILES -->
      <script src="<?php echo ADMIN; ?>js/surveyCommon-jsFunctions.js"></script>
      <script src="<?php echo ADMIN; ?>js/common-jsFunction.js"></script>
      <script src="<?php echo ADMIN; ?>plugins/bower_components/toast-master/js/jquery.toast.js"></script>
      <script src="<?php echo ADMIN; ?>plugins/bower_components/jquery-validation-1.17.0/dist/jquery.validate.min.js"></script>
      <script src="<?php echo ADMIN; ?>plugins/bower_components/jquery-validation-1.17.0/dist/additional-methods.min.js"></script>
      <script src="<?php echo ADMIN; ?>plugins/bower_components/jquery-validation-1.17.0/dist/custom-addtional-methods.js"></script>


      <!-- Chart JS -->
      <script src="https://code.highcharts.com/highcharts.js"></script>
      <script src="https://code.highcharts.com/modules/exporting.js"></script>
      <script src="https://code.highcharts.com/modules/export-data.js"></script>
      <!--Counter js -->
      <script src="<?php echo ADMIN; ?>plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
      <script src="<?php echo ADMIN; ?>plugins/bower_components/counterup/jquery.counterup.min.js"></script>
      <!-- BALAKUMAR BA - WEB DEVELOPER EXTERANL JS FILES END HERE-->
      <script>
         $(document).ready(function () {
             $('body').tooltip({selector: '[data-toggle="tooltip"]'});
         });
         //EVENT FOR UPGRADE
         $("#upgrad").click(function(){
            $(".closepop").hide();
         });
      </script>
      <script type="text/javascript">
         function chartFilterMonth(response){
         	console.log(response.xAxis);
         	console.log(response.email);
         	console.log(response.link);
			console.log(response.sms);
			console.log(response.guest);
         	$('#container-master').html('<div id="container" style="min-width: 310px; height: 420px; margin: 0 auto"></div>');
             Highcharts.chart('container', {
             chart: {
               type: 'column'
             },
             credits: { enabled: false },
             exporting: { enabled: false },
             navigation: {
                 buttonOptions: {
                     enabled: false
                 }
             },
             title: {
               text: 'Total Surveys Sent'
             },

             xAxis: {
               categories: response.xAxis
             },

             yAxis: {
               allowDecimals: false,
               min: 0,
               title: {
                 text: ''
               }
             },
             tooltip: {
               pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>'
             },
             plotOptions: {
               column: {
                 stacking: 'normal'
               }
             },
             series: [{
               name: 'Survey Through Email',
               data: response.email,
             }, {
               name: 'Survey Through Link',
               data: response.link,
             }, {
               name: 'Survey Through SMS',
               data: response.sms,
             }, {
               name: 'Survey Through Guest',
               data: response.guest,
              } ]
           });

         }
         function callHighChartData(date){
         	$.ajax({url: "dashboard.php?action=chart&d="+date,dataType: "json", success: function(response){
         		chartFilterMonth(response);
         	}});
         }
         	Highcharts.chart('container', {
             chart: {
               type: 'column'
             },
             credits: { enabled: false },
             exporting: { enabled: false },
             navigation: {
                 buttonOptions: {
                     enabled: false
                 }
             },
             title: {
               text: 'Total Surveys Sent'
             },

             xAxis: {
               categories: [<?php echo $xAxis; ?>]
             },

             yAxis: {
               allowDecimals: false,
               min: 0,
               title: {
                 text: ''
               }
             },
             tooltip: {
               pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>'
             },
             plotOptions: {
               column: {
                 stacking: 'normal'
               }
             },
             series: [{
               name: 'Survey Through Email',
               data: [<?php echo implode(", ", $email) ?>],
             }, {
               name: 'Survey Through Link',
               data: [<?php echo implode(", ", $link) ?>],
             }, {
               name: 'Survey Through SMS',
               data: [<?php echo implode(", ", $sms) ?>],
             }, {
               name: 'Survey Through Guest',
               data: [<?php echo implode(", ", $guest) ?>],
             }]
           });
           // Create the chart
           Highcharts.chart('container1', {
             chart: {
               type: 'column'
             },
             credits: { enabled: false },
             exporting: { enabled: false },
             navigation: {
                 buttonOptions: {
                     enabled: false
                 }
             },
             title: {
               text: 'Survey Responses'
             },
             xAxis: {
               type: 'category'
             },
             yAxis: {
               title: {
                 text: ''
               }

             },
             legend: {
               enabled: false
             },
             plotOptions: {
               series: {
                 borderWidth: 0,
                 dataLabels: {
                   enabled: true,
                   format: '{point.y:.1f}%'
                 }
               }
             },

             tooltip: {
               headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
               pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
             },

             "series": [
               {
                 "name": "Surveys",
                 "colorByPoint": true,
                 <?php echo $data_arr; ?>
               }
             ]
           });
           $("#d_c_fliter").on("change", function(){
             var $month = $(this).val();
         	callHighChartData($(this).val());
             XHRCall({data: {fn: 'dash-counts', com_id: com_id, month: $month}, async: true, url: 'ajax-survey-methods.php'}, LoadDashCounts);
           });
           var $month = $("#d_c_fliter").val();
           XHRCall({data: {fn: 'dash-counts', com_id: <?php echo $com_id; ?>, month: $month}, async: true, url: 'ajax-survey-methods.php'}, LoadDashCounts);
           var busy = false;
           var limit = 10;
           var offset = 0;

           var FunctionalKeys = [ 13, 38, 40, 37, 39, 27, 17, 18, 9, 33, 34, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123 ];
           var com_id = '<?php echo $com_id ?>';

           $(document).ready(function() {
             // start to load the first set of data
             if (busy == false) {
               busy = true;
               // start to load the first set of data
               displayRecords(limit, offset, 'html');
             }


             $(window).scroll(function() {
               // make sure u give the container id of the data to be loaded in.
               if ($(window).scrollTop() + $(window).height() >= $(document).height()-100 && !busy && $("#show_survey_by :selected").val() != 'recent_survey') {
                 busy = true;
                 offset = limit + offset;

                 // this is optional just to delay the loading of data
                 setTimeout(function() { displayRecords(limit, offset, 'append'); }, 500);

                 // you can remove the above code and can use directly this function
                 // displayRecords(limit, offset);

               }
             });

           });

           $("#show_survey_by").on("change", function(){
             offset = 0;
             displayRecords(limit, offset, 'html');
           });
           $("#survey_search").on("keyup", function(e){
             if(jQuery.inArray( e.keyCode, FunctionalKeys ) > 0){return false;}
             offset = 0;
             displayRecords(limit, offset, 'html', 'true');
           });
           function displayRecords(lim, off, mode, asyncv = false) {

             var s_val   = $.trim($("#survey_search").val()); // Search Box Text
             var showBy  = $("#show_survey_by :selected").val();

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
                       content += '<div class="clearfix"></div>'+
                       '<div class="col-md-12 col-sm-12 col-xs-12" id="del'+$currObj['sur_id']+'">'+
                           '<div class="white-box first1 last">'+
                           '<div class="only-dash">' +
                               '<div class="col-md-12 col-sm-12 col-xs-12 p-0">'+
                                   '<div class="col-md-5 col-sm-4 col-xs-12 p-0">'+
                                     '<div class="t1">'+
                                         '<div class="t1-img">'+
                                             '<img class="two-person" src="images/survey_logo/'+$currObj['sur_logo']+'"/>'+
                                         '</div>'+

                              '<div class="t1-content">'+
                                 '<div class="survey-text"><p class="emb-spn"><a href="survey.php?sur_id='+$currObj.sur_id+'">'+$currObj['survey_name']+'</a></p><p class="emb-spn1">Created '+$currObj['create_on']+'</p></div></div></div>'+
                                   '</div>'+
                                   '<div class="col-md-2 col-sm-2 col-xs-12 all-center">'+
                                     '<span class="label label-megna label-rounded act">'+$currObj['sts']+'</span>'+
                                   '</div>'+

                                   '<div class="col-md-5 col-sm-6 col-xs-12">'+
                                     '<div class="finish">'+
                                         '<p>'+$currObj['Response']+' Response /<span> '+$currObj['Qcnt']+' Questions</span> </p>'+
                                         '<h5>Last modified '+$currObj['updated_on']+'</h5>'+
                                     '</div>'+
                                       '<div class="dropdown dots-main ben">'+
                                       '<a href="survey.php?sur_id='+$currObj['sur_id']+'"><img class="pen-g" data-toggle="tooltip" data-placement="top" title="Edit"  src="images/pencil.png"/></a> '+
                                       '<a href="javascript:void(0);" class="delete-survey-modal" id="'+$currObj['sur_id']+'"><img class="del-g" data-toggle="tooltip" data-placement="top" title="Delete" src="images/del-g.png"/></a>'+
                                       '</div>'+
                                     '</div>'+
                               '</div>'+
                           '</div>'+
                           '</div>'+
                       '</div>';
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
           $(document).on("click", ".delete-survey-modal", function (e) {
                          var del_scid = $(this).attr('id');
                          $('#delid').val(del_scid);
                          $('.newsletter-delete-modal-show').modal('show');

                        });
                        $(document).on("click", "#sc_delete_confirm", function (e) {
                          var del_sid = $('#delid').val();
                          var method='delete-survey';
                          $.ajax({
                              url: 'ajax-survey-methods.php',
                              type: 'POST',
                            data:{did:del_sid, fn:method},
                              success: function(ouputData) {
                                var response = $.parseJSON(ouputData);
                                if(response.flg=="success"){
                                 $('.delpop-close').click();
                                 $('#del'+del_sid+'').remove();
                               }
                                ShowToastr(response.status,response.msg, 'top-right',response.flg);
                              }
                          });

                        });


  /*$(document).ready(function() {
    $(window).on('orientationchange', function(event) {
        if(window.orientation === 90 || window.orientation === -90)
        alert("This page is best viewed in Portrait mode");
    });
});
if(screen.width < 600 ||
 navigator.userAgent.match(/Android/i) ||
 navigator.userAgent.match(/webOS/i) ||
 navigator.userAgent.match(/iPhone/i) ||
 navigator.userAgent.match(/iPod/i)) {
alert("This is a mobile device");
}
var m_device= $.browser.device;
console.log(m_device); */

      </script>

      <?php $_SESSION['S']['dashboard_strip']=1; ?>
    </div>
   </body>
</html>
