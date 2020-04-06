<?php
require_once ("inc/config.php");
require_once ("inc/classes/class.survey.php");

$surObj = new SurveyFunctions($prop, $common);
$quesObj = new Report($prop, $common);

$method = $_REQUEST['fn'];
$return = array();
$return['method'] = $method;
$requestData= $_REQUEST;
$com_id = COM_ID;

switch ($method) {
    case 'all_survey':
          $c_id = $_REQUEST['com_id'];
          $offset = $_REQUEST['cur_offset'];
          $limit = $_REQUEST['limit'];
          $value = $_REQUEST['value'];
          $sort_by = $_REQUEST['sort_by'];
          $Allsurvey = $surObj->getAllSurveys($com_id, $offset, $limit, $value,$sort_by);
          $return['data'] = $Allsurvey;
       break;
    case 'recent_survey':
          $c_id = $_REQUEST['com_id'];
          $value = $_REQUEST['value'];
          if($value == ''){
            $value = 'all';
          }
          $Allsurvey = $surObj->getRecentSurveys($c_id, $value);
          $return['data'] = $Allsurvey;
       break;
    case 'getrecent_survey':
           $c_id = $_REQUEST['com_id'];
           $value = $_REQUEST['value'];
           $sort_by = $_REQUEST['sort_by'];

           if($value == ''){$value = 'all';}
           if($sort_by=='') {$sort_by = 'DESC';}

           $Allsurvey = $surObj->getRecentSurveysNew($c_id, $value,$sort_by);
           $return['data'] = $Allsurvey;
        break;
    case 'dash-counts':
          $return['data'] = $surObj->getDashCounts($_POST);
       break;
	case 'dash-counts-survey':
          $return['data'] = $surObj->getDashCounts_survey($_POST);
       break;
    case 'survey-questions-report':
        $com_id = $_REQUEST['com_id'];
        $sur_id = $_REQUEST['sur_id'];
        $offset = $_REQUEST['cur_offset'];
        $limit = $_REQUEST['limit'];

        $getSurveyQuestions = $quesObj->ShowQuesReport($com_id, $sur_id, $offset, $limit);
        //print_r($getSurveyQuestions);
        $return['data'] =  $getSurveyQuestions;
       break;
    case 'delete-survey':
        $tid = $_REQUEST['did'];
        $prop_cond = array('sur_id'=> $tid,'com_id'=>COM_ID);
        $Dprop_details = array(
         'is_deleted'    => 1,
         'deleted_date'  =>CURRENT_DATE_TIME,
         'deleted_ip'    => CURRENT_IP,
       );
       $isupdate = $prop->update(SURVEY, $Dprop_details, $prop_cond);
       $return['status'] = 'Error';
       $return['flg'] = 'error';
       $return['msg'] = 'Failed to delete survey!';
       if ($isupdate == 1) {
           $return['status'] = 'Success';
           $return['flg'] = 'success';
           $return['msg'] = 'Survey deleted successfully!';
       }
    break;
	case 'add-subheading':
		$sb_title = $_REQUEST['sb_title'];
		$sur_id = $_REQUEST['sur_id'];
		$com_id = $_REQUEST['com_id'];
		$show_it = $_REQUEST['show_it'];
		$ques_no = $_REQUEST['ques_no'];
		$sub_details = array(
         'survey_id'    => $sur_id,
         'com_id'  =>$com_id,
         'subheading'    => $sb_title,
		 'show_it'    => $show_it,
		 'ques_no'    => $ques_no
       );
	   $sh_id = $prop->addID('sur_subheading', $sub_details);
	   $return['status'] = 'Error';
       $return['flg'] = 'error';
       $return['msg'] = 'Failed to add Subheading!'.$sh_id;
	   if($sh_id != 0)
	   {
	   	   $return['status'] = 'Success';
           $return['flg'] = 'success';
           $return['msg'] = 'Subheading added successfully!';
		   $return['last_id'] = $sh_id;
	   }
	break;  
	case 'update-subheading':
		$sb_title = $_REQUEST['sb_title'];
		$sur_id = $_REQUEST['sur_id'];
		$com_id = $_REQUEST['com_id'];
		$show_it = $_REQUEST['show_it'];
		$ques_no = $_REQUEST['ques_no'];
		$saveId = $_REQUEST['saveId'];
		$sub_details = array(
         'survey_id'    => $sur_id,
         'com_id'  =>$com_id,
         'subheading'    => $sb_title,
		 'show_it'    => $show_it,
		 'ques_no'    => $ques_no
       );
	   $prop_cond = array('id'=>$saveId);
	   $isupdate = $prop->update('sur_subheading', $sub_details,$prop_cond);
	   $return['status'] = 'Error';
       $return['flg'] = 'error';
       $return['msg'] = 'Failed to Update Subheading!';
	   if($isupdate == 1)
	   {
	   	   $return['status'] = 'Success';
           $return['flg'] = 'success';
           $return['msg'] = 'Subheading Updated successfully!';
	   }
	break;   
	case 'delete-subheading':
		$saveId = $_REQUEST['saveId'];
		$sub_details = array(
         'status'    => 2
       );
	   $prop_cond = array('id'=>$saveId);
	   $isupdate = $prop->update('sur_subheading', $sub_details,$prop_cond);
	   $return['status'] = 'Error';
       $return['flg'] = 'error';
       $return['msg'] = 'Failed to Delete Subheading!';
	   if($isupdate == 1)
	   {
	   	   $return['status'] = 'Success';
           $return['flg'] = 'success';
           $return['msg'] = 'Subheading Deleted successfully!'.$saveId;
	   }
	break;
	case 'page-show-type':
		$show_type = $_REQUEST['show_type'];
		$sur_id = $_REQUEST['sur_id'];
		$com_id = $_REQUEST['com_id'];
		$page_show_type_details = array(
         'survey_id'    => $sur_id,
         'com_id'  =>$com_id,
         'show_type'    => $show_type
	   );
	   $show_type_id = $prop->addID('sur_show_page_type', $page_show_type_details);
	   $return['status'] = 'Error';
       $return['flg'] = 'error';
       $return['msg'] = 'Failed to add page show type!'.$sh_id;
	   if($show_type_id != 0)
	   {
	   	   $return['status'] = 'Success';
           $return['flg'] = 'success';
           $return['msg'] = 'Page show type added successfully!';
		   $return['last_id'] = $show_type_id;
	   }
	break; 
	case 'page-show-type-update':
		$show_type = $_REQUEST['show_type'];
		$show_type_id = $_REQUEST['show_type_id'];		
		$sur_id = $_REQUEST['sur_id'];
		$com_id = $_REQUEST['com_id'];
		$page_show_type_details = array(
         'survey_id'    => $sur_id,
         'com_id'  =>$com_id,
         'show_type'    => $show_type
	   );
	   $prop_cond = array('id'=>$show_type_id);
	   $isupdate = $prop->update('sur_show_page_type', $page_show_type_details,$prop_cond);
	   $return['status'] = 'Error';
       $return['flg'] = 'error';
       $return['msg'] = 'Failed to Update page show type!'.$sh_id;
	   if($isupdate == 1)
	   {
	   	   $return['status'] = 'Success';
           $return['flg'] = 'success';
           $return['msg'] = 'Page show type Updated successfully!';
		   $return['last_id'] = $show_type_id;
	   }
	break;   
}
echo json_encode($return);
die();
?>
