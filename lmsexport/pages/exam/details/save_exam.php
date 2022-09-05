<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/exam/details/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local plugin "Eklavvya Moodle Proctoring"
 *
 * @package   local_lmsexport
 * @copyright 2022 Splashgain Technology Solutions Pvt. Ltd., India.
 * @license   https://www.eklavvya.com/terms-of-use/
 */

require_once("../../../../../config.php");
require_once("../../../conf.php");
require_once("../../pgnfunc.php");

require_login();

error_reporting(1);

echo json_encode($_POST);



$rv = array();

$rv['isok'] = 0;
$rv['msg1'] = '';
$rv['msg2'] = '';
$rv['test'] = '';


function strip_tags_content($text, $tags = '', $invert = false) {

    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) and count($tags) > 0) {
        if ($invert == false) {
            return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        } else {
            return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
        }
    } else if ($invert == false) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    return $text;
}

error_reporting(1);


$PAGE->set_pagelayout('admin');


global $USER, $DB;


$fullusername = $USER->firstname . ' ' . $USER->lastname;
$myipadrs = get_client_ip();

$tokres = $DB->get_record_sql("SELECT token  FROM {external_tokens} WHERE userid = " . $USER->id);
$tokenresr = $tokres->token;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;
$examapikey = $eklavyaobj->examapikey;
$timezone = $eklavyaobj->timezone1;


if (is_siteadmin()) {
    $courses = get_courses();
} else {
    $courses = enrol_get_all_users_courses($USER->id);
}

if (isset($_POST['ScheduleID'])) {
    $scheduleid = $_POST['ScheduleID'];
}

if (isset($_POST['editrec'])) {
    $iseditmode = 1;
} else {
    $iseditmode = 0;
}


$enableremoteproctoring = 0;
$iscaptureaudio = 0;
$iscapturevideo = 0;
$iscandidatephoto = 0;
$photocaptime = 0;
$mdlexmid = 0;
$flag05 = 0;
$flag06 = 0;
$flag07 = 0;
$flag08 = 0;
$flag09 = 0;
$flag10 = 0;
$flag11 = "Topic wise";
$flag12 = 0;
$flag13 = 0;
$flag14 = 0;
$flag15 = 0;
$txt2 = "0";
$txt3 = "0";
$txt4 = "0";
if (isset($_POST['associatedq1'])) {
    if ($_POST['associatedq1'] == 'Yes') {
        $enableremoteproctoring = 1;
    }
}



if (isset($_POST['associatedq2'])) {
    if ($_POST['associatedq2'] == 'Yes') {
        $iscaptureaudio = 1;
    }
}

if (isset($_POST['associatedq3'])) {
    if ($_POST['associatedq3'] == 'Yes') {
        $iscapturevideo = 1;
    }
}

if (isset($_POST['associatedq4'])) {
    if ($_POST['associatedq4'] == 'Yes') {
        $iscandidatephoto = 1;
    }
}

if (isset($_POST['flag05'])) {
    if ($_POST['flag05'] == '1') {
        $flag05 = 1;
    }
}

if (isset($_POST['flag06'])) {
    if ($_POST['flag06'] == '1') {
        $flag06 = 1;
    }
}

if (isset($_POST['flag07'])) {
    if ($_POST['flag07'] == '1') {
        $flag07 = 1;
    }
}

if (isset($_POST['flag08'])) {
    if ($_POST['flag08'] == '1') {
        $flag08 = 1;
    }
}

if (isset($_POST['flag09'])) {
    if ($_POST['flag09'] == '1') {
        $flag09 = 1;
    }
}

if (isset($_POST['flag10'])) {
    if ($_POST['flag10'] == '1') {
        $flag10 = 1;
    }
}


if (isset($_POST['flag11'])) {
    if ($_POST['flag11'] == '1') {
        $flag11 = "Course wise";
    }
}

if (isset($_POST['flag12'])) {
    if ($_POST['flag12'] == '1') {
        $flag12 = 1;
    }
}

if (isset($_POST['flag13'])) {
    if ($_POST['flag13'] == '1') {
        $flag13 = 1;
    }
}

if (isset($_POST['flag14'])) {
    if ($_POST['flag14'] == '1') {
        $flag14 = 1;
    }
}

if (isset($_POST['flag15'])) {
    if ($_POST['flag15'] == '1') {
        $flag15 = 1;
    }
}

if (isset($_POST['quiz']) && $_POST['quiz'] != '') {
    $mdlexmid = $_POST['quiz'];
} else {

    if (isset($_POST['withoutsection'])) {
        $mdlexmid = $_POST['withoutsection'];
    }
}

if (isset($_POST['txt1'])) {
    $photocaptime = (int) $_POST['txt1'];
}

if (isset($_POST['txt2'])) {
    $txt2 = (int) $_POST['txt2'];
}

if (isset($_POST['txt3'])) {
    $txt3 = (int) $_POST['txt3'];
}

if (isset($_POST['txt4'])) {
    $txt4 = (int) $_POST['txt4'];
}





$posturl = $apiurl . '/SplashService.svc/GetInstituteDetailsWebAPI';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $posturl);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$headers  = [
    'authenticationkey: ' . $examapikey,
    'Content-Type: application/json'
];


curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$flgresponse = curl_exec($ch);
curl_close($ch);

$instituteflagsresponsearray = json_decode($flgresponse, true);
$instituteflagsarray = $instituteflagsresponsearray['GetInstituteDetailsWebAPIResult'];
$packageid = $instituteflagsarray['PackageID'];



if (isset($_POST['quiz']) && $_POST['quiz'] != '') {

    require_once($CFG->libdir . '/questionlib.php');
    require_once($CFG->dirroot . '/question/format.php');
    $records = $DB->get_record('local_lmsexport', array(), $fields = '*', $ignoremultiple = false);

    $eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

    $apiurl = $eklavyaobj->apiurl;
    $examapikey = $eklavyaobj->examapikey;
    $timezone = $eklavyaobj->timezone1;


    $coursed = $DB->get_record_sql("SELECT id,fullname FROM {course} where id=" . $_POST['courseid']);
    $subjectname = $coursed->fullname;


    $topicid = $_REQUEST['section'];

    $topicd = $DB->get_record_sql("SELECT name FROM {course_sections} where id=" . $topicid);
    $topicname = $topicd->name;

    $subjectid = $coursed->id;

    $quizd = $DB->get_record_sql("SELECT name,timeopen,timeclose,timelimit,sumgrades,id FROM {quiz} where id=" . $_POST['quiz']);
    $examname = $quizd->name;
    $totalmarks = round($quizd->sumgrades);
    $iteminstance = $quizd->id;

    $grade = $DB->get_record_sql("SELECT gradepass FROM {grade_items} where courseid=" . $_POST['courseid'] .
    " AND iteminstance=" . $iteminstance . " ");
    $passinggrade = round($grade->gradepass);


    if ($quizd->timeopen == "0") {
        $startdate = date("Y-m-d");
    } else {
        $startdate = date("Y-m-d", $quizd->timeopen);
    }
    if ($quizd->timeclose == "0") {
        $enddate = date("Y-m-d");
    } else {
        $enddate = date("Y-m-d", $quizd->timeclose);
    }
    if ($quizd->timelimit == "0") {
        $duration = "60";
    } else {
        $duration = $quizd->timelimit / 60;
    }

    $questiondata = array();
    if ($questions = $DB->get_records('quiz_slots', array('quizid' => $_POST['quiz']))) {
        foreach ($questions as $question) {
            array_push($questiondata, question_bank::load_question_data($question->questionid));
        }
    }


    $contextid = $questiondata[0]->contextid;


    foreach ($questiondata as $key => $questiond) {
        $itemid = "";
        $questionusageid = "";
        $slotid = "";
        $questionkey = $key;

        if (!$data = $DB->get_record_sql("SELECT itemid FROM {files} WHERE component='question'".
        "AND filearea='questiontext' AND contextid=" . $contextid . " ORDER BY itemid DESC LIMIT 1", array($contextid))) {
            $itemid = 1;
        } else {
            $itemid = $data->itemid;
        }

        $results = $DB->get_record_sql("SELECT *  FROM {question_attempts} WHERE questionid = " .
        $questiondata[$questionkey]->id . " ORDER BY id DESC LIMIT 1");
        $questionusageid = $results->questionusageid;
        $slotid = $results->slot;
        $file = "pluginfile.php";
        $filearea = "questiontext";
        $component = "question";
        $itemid = $questionusageid . '/' . $slotid . '/' . $questiondata[$questionkey]->id;


        $str = file_rewrite_pluginfile_urls($questiondata[$questionkey]->questiontext, $file, $contextid, $component,
        $filearea, $itemid);
        $formatted = preg_replace('/(?<=src=")(.*?)(?=")/', '$1?token=' . $tokenresr, $str);
        $formatted = preg_replace('/(?<=href=")(.*?)(?=")/', '$1?token=' . $tokenresr, $formatted);
        $formatted = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $formatted);
        $questiondata[$questionkey]->questiontext = $formatted;
    }



    $subjectobj = array();


    foreach ($questiondata as $qd) {

        $qid = $qd->id;
        $qcat = $qd->category;

        $quezd = $DB->get_record_sql("SELECT maxmark FROM {quiz_slots} where questionid=" . $qid);
        $defaultmark = round($quezd->maxmark);

        $ransd = $DB->get_record_sql("SELECT rightanswer FROM {question_attempts} where questionid=" . $qid);
        $rightanswer = str_replace('&nbsp;', '', str_replace('{', '', str_replace('}', '', strip_tags($ransd->rightanswer))));

        $optarray = array();
        $cnt = 1;
        $rightanswer = preg_replace('/\s+/', ' ', $rightanswer);
        $rightanswer = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $rightanswer);

        $rightanswer = trim($rightanswer);



        if (count($qd->options->answers) > 5) {
            echo "This Exam can not be exported because " . $qd->name . " has more than 5 answers";

            exit;
        }
        if (count($qd->options->answers) == 0 && $qd->qtype != 'match') {
            echo "Sorry, This exam: " . $qd->name . " can not be exported because it has few questions which doesn't ".
            "have answers and such questions are not supported by Eklavya.";

            exit;
        }
        foreach ($qd->options->answers as $as) {



            $results = $DB->get_record_sql("SELECT *  FROM {question_attempts} WHERE questionid = " . $qd->id .
            " ORDER BY id DESC LIMIT 1");
            $questionusageid = $results->questionusageid;
            $slotid = $results->slot;
            $file = "pluginfile.php";
            $filearea = "answer";
            $component = "question";
            $itemid = $questionusageid . '/' . $slotid . '/' . $as->id;




            $str2 = file_rewrite_pluginfile_urls($as->answer, $file, $contextid, $component, $filearea, $itemid);
            $formatted2 = preg_replace('/(?<=src=")(.*?)(?=")/', '$1?token=' . $tokenresr, $str2);
            $formatted2 = preg_replace('/(?<=href=")(.*?)(?=")/', '$1?token=' . $tokenresr, $formatted2);

            $formatted2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $formatted2);
            $answrmedia = $formatted2;


            $dom2  = new \DOMDocument();
            $dom2->loadHTML($str2);
            $dom2->preserveWhiteSpace = false;

            $images2 = [];

            foreach ($dom2->getElementsByTagName('img') as $image2) {
                $images2[] = $image2->getAttribute('src');
            }




            $regex2 = '#([^,\s]+\.mp3)#';
            preg_match_all($regex2, $str2, $mp3s2);

            $regex2 = '#([^,\s]+\.mp4)#';
            preg_match_all($regex2, $str2, $mp4s2);

            $regex2 = '#([^,\s]+\.ogg)#';
            preg_match_all($regex2, $str2, $oggs2);


            $regex2 = '#([^,\s]+\.wav)#';
            preg_match_all($regex2, $str2, $wavs2);

            $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $oggs2[0][0])));
            $ax2 = explode('>', $audiov2);
            $audio2 = $ax2[0];


            $videov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp4s2[0][0])));
            $vx2 = explode('>', $videov2);
            $video2 = $vx2[0];
            if (strstr($video2, 'href')) {
                $video2 = str_replace('href="', '', $video2);





                $video2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $video2 .
                '?token=' . $tokenresr);
            }


            if ($audio2 == '') {
                $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp3s2[0][0])));
                $ax2 = explode('>', $audiov2);
                $audio2 = $ax2[0];
            }

            if ($audio2 == '') {
                $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $wavs2[0][0])));
                $ax2 = explode('>', $audiov2);
                $audio2 = $ax2[0];
            }
            if ($audio2 == '') {

                $regex = '#([^,\s]+\.m4a)#';
                preg_match_all($regex, $str, $mp4as);


                $audiov2 = str_replace("\\", "", str_replace('src="', '', $mp4as[0][0]));
                $ax2 = explode('>', $audiov2);
                $audio2 = $ax2[0];
            }


            $image2 = str_replace('u0022', '', str_replace("\\", "", $images2[0]));



            if (!strstr($image2, '?token=' . $tokenresr) && $image2 != '') {

                $image2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $image2 .
                '?token=' . $tokenresr);
            }


            if (!strstr($audio2, '?token=' . $tokenresr) && $audio2 != '') {

                $video2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $video2 .
                '?token=' . $tokenresr);
            }


            if (!strstr($video2, '?token=' . $tokenresr) && $video2 != '') {

                $video2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $video2 .
                '?token=' . $tokenresr);
            }




            $ans = str_replace('&nbsp;', '', trim(strip_tags_content($as->answer)));
            if (trim($ans) == '') {

                $ans = str_replace('&nbsp;', '', trim(strip_tags($as->answer)));
            }
            $anstext = substr($ans, 0, strpos($ans, '@@'));
            if (trim($anstext) == '') {
                $anstext = $ans;
                $nn = explode('@@PLUGINFILE', $anstext);
                $anstext = $nn[0];
            }
            if (trim($rightanswer) == $ans) {
                $raw = "1";
            } else {
                $raw = "0";
            }
            $idar = array();

            if ($gds = $DB->get_records('question_answers', array('question' => $qid))) {
                foreach ($gds as $gd) {
                    $ans2 = str_replace('&nbsp;', '', str_replace('{', '', str_replace('}', '', strip_tags($gd->answer))));
                    $ans2r = preg_replace('/\s+/', ' ', $ans2);
                    $ans2 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $ans2);

                    $ans2 = trim($ans2);
                    $idar[] = array('ag' => $gd->fraction, 'ans' => $ans2);
                }
            }

            foreach ($idar as $ii) {


                if ($ii['ans'] == $ans) {
                    $mmarks = $ii['ag'];
                }
            }

            $mmarks = round($mmarks);



            $optarray[] = array("Option" . $cnt => $anstext, "OptionImage" . $cnt => $image2, "OptionAudio" . $cnt => $audio2,
            "OptionVideo" . $cnt => $video2, "IsOption" . $cnt . "Correct" => "$raw", "OptionMarks" . $cnt => "$mmarks");

            $cnt = $cnt + 1;
        }



        $question = strip_tags($qd->questiontext);
        $regex = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";

        $question = preg_replace($regex, '', $question);




        if ($qd->qtype == 'multichoice') {

            $questiontype = 'MCQ';
        } else if ($qd->qtype == 'truefalse') {

            $questiontype = 'TRUE OR FALSE';
        } else if ($qd->qtype == 'gapselect') {

            $questiontype = 'FILL IN BLANKS';
        } else if ($qd->qtype == 'match') {

            $questiontype = 'MATCH THE PAIR';
            $ccnt = 1;
            $optarray = array();
            foreach ($qd->options->subquestions as $as) {
                $qid = $qd->id;
                $quezd = $DB->get_record_sql("SELECT maxmark FROM {quiz_slots} where questionid=" . $qid);
                $defaultmarks = round($quezd->maxmark);
                $results = $DB->get_record_sql("SELECT *  FROM {question_attempts} WHERE questionid = " . $as->questionid .
                " ORDER BY id DESC LIMIT 1");
                $questionusageid = $results->questionusageid;
                $slotid = $results->slot;
                $file = "pluginfile.php";
                $filearea = "subquestion";
                $component = "qtype_match";
                $itemid = $questionusageid . '/' . $slotid . '/' . $as->id;




                $str2 = file_rewrite_pluginfile_urls($as->questiontext, $file, $contextid, $component, $filearea, $itemid);
                $formatted2 = preg_replace('/(?<=src=")(.*?)(?=")/', '$1?token=' . $tokenresr, $str2);
                $formatted2 = preg_replace('/(?<=href=")(.*?)(?=")/', '$1?token=' . $tokenresr, $formatted2);

                $formatted2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $formatted2);

                $answrmedia = $formatted2;


                $dom2  = new \DOMDocument();
                $dom2->loadHTML($str2);
                $dom2->preserveWhiteSpace = false;

                $images2 = [];

                foreach ($dom2->getElementsByTagName('img') as $image2) {
                    $images2[] = $image2->getAttribute('src');
                }


                $image2 = str_replace('u0022', '', str_replace("\\", "", $images2[0]));


                if (!strstr($image2, '?token=' . $tokenresr) && $image2 != '') {

                    $image2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $image2 .
                    '?token=' . $tokenresr);
                }
                $regex2 = '#([^,\s]+\.mp3)#';
                preg_match_all($regex2, $str2, $mp3s2);

                $regex2 = '#([^,\s]+\.mp4)#';
                preg_match_all($regex2, $str2, $mp4s2);

                $regex2 = '#([^,\s]+\.ogg)#';
                preg_match_all($regex2, $str2, $oggs2);

                $regex2 = '#([^,\s]+\.wav)#';
                preg_match_all($regex2, $str2, $wavs2);

                $videov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp4s2[0][0])));
                $vx2 = explode('>', $videov2);
                $video2 = $vx2[0];



                $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $oggs2[0][0])));
                $ax2 = explode('>', $audiov2);
                $audio2 = $ax2[0];
                if (strstr($video2, 'href')) {
                    $video2 = str_replace('href="', '', $video2);


                    $video2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $video2 .
                    '?token=' . $tokenresr);
                }

                if ($audio2 == '') {
                    $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp3s2[0][0])));
                    $ax2 = explode('>', $audiov2);
                    $audio2 = $ax2[0];
                }

                if ($audio2 == '') {
                    $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $wavs2[0][0])));
                    $ax2 = explode('>', $audiov2);
                    $audio2 = $ax2[0];
                }

                if (!strstr($audio2, '?token=' . $tokenresr) && $audio2 != '') {
                    $audio2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $audio2 .
                    '?token=' . $tokenresr);
                }
                if (!strstr($video2, '?token=' . $tokenresr) && $video2 != '') {
                    $video2 = str_replace($_SERVER['SERVER_NAME'] . '/', $_SERVER['SERVER_NAME'] . '/webservice/', $video2 .
                    '?token=' . $tokenresr);
                }

                $delim = "||-||";
                $answerc = $as->answertext;

                if (trim($image2) != '') {
                    $data2 = $image2;
                } else if (trim($audio2) != '') {
                    $data2 = $audio2;
                } else if (trim($video2) != '') {
                    $data2 = $video2;
                }

                if ($data2 == '') {
                    $optarray[] = array("Option" . $ccnt => strip_tags($answrmedia) . $delim . $answerc, "OptionImage" .
                    $ccnt => "", "OptionAudio" . $ccnt => "", "OptionVideo" . $ccnt => "", "IsOption" . $ccnt .
                    "Correct" => "1", "OptionMarks" . $ccnt => "$defaultmarks");
                } else {
                    $optarray[] = array("Option" . $ccnt => $data2 . $delim . $answerc, "OptionImage" . $ccnt => "",
                    "OptionAudio" . $ccnt => "", "OptionVideo" . $ccnt => "", "IsOption" . $ccnt . "Correct" => "1",
                    "OptionMarks" . $ccnt => "$defaultmarks");
                }


                $ccnt = $ccnt + 1;
            }
        } else {

            $questiontype = 'SUBJECTIVE';
        }


        $str = $qd->questiontext;

        $dom  = new \DOMDocument();
        $dom->loadHTML($str);
        $dom->preserveWhiteSpace = false;
        $images = [];





        foreach ($dom->getElementsByTagName('img') as $image) {
            $images[] = $image->getAttribute('src');
        }



        $regex = '#([^,\s]+\.mp3)#';

        preg_match_all($regex, $str, $mp3s);



        $regex = '#([^,\s]+\.mp4)#';
        preg_match_all($regex, $str, $mp4s);



        $videov = str_replace("\\", "", str_replace('src="', '', $mp4s[0][0]));
        $vx = explode('>', $videov);
        $video = $vx[0];

        $audiov = str_replace("\\", "", str_replace('src="', '', $mp3s[0][0]));
        $ax = explode('>', $audiov);
        $audio = $ax[0];

        if ($audio == '') {

            $regex = '#([^,\s]+\.m4a)#';
            preg_match_all($regex, $str, $mp4as);


            $audiov = str_replace("\\", "", str_replace('src="', '', $mp4as[0][0]));
            $ax = explode('>', $audiov);
            $audio = $ax[0];
        }

        $image = str_replace("\\", "", $images[0]);




        $optarray = call_user_func_array('array_merge', $optarray);

        $subjectobj[] = array('SubjectID' => "$subjectid", 'SubjectName' => $subjectname, 'TopicID' => "$topicid",
        'TopicName' => $topicname, 'QuestionType' => "$questiontype", 'DifficultyLevel' => 'Basic', 'Question' => $question,
        'Image' => $image, 'Audio' => str_replace('href=', '', $audio), 'Video' => $video, "Marks" => "$defaultmark",
        "MoodleQuestionID" => $qid, 'UserEmailID' => $USER->email, "UserName" => $fullusername,
        "IPAddress" => $myipadrs) + $optarray;
    }



    if ($qd->qtype == 'shortanswer') {
        $et = '2';
    } else {
        $et = '3';
    }
    $questionobj = array("QuestionObj" => $subjectobj);


    if (isset($_POST['editrec']) && ($_POST['editrec'] != '')) {
        $jsondata = array("ExamObj" => array('ExamName' => $examname, "ScheduleID" => $scheduleid, 'ExamDuration' => "$duration",
         'ExamName' => $examname, 'ExamDuration' => "$duration", 'ExamType' => "$et", 'ScheduleStartDate' => $startdate,
         'ScheduleEndDate' => $enddate, 'TimeZone' => $timezone, 'UserID' => $USER->id, 'UserEmailID' => $USER->email,
         "PassingMarks" => $passinggrade, "TotalMarks" => $totalmarks, "AllowRemoteProctoring" => $enableremoteproctoring,
         "AudioStreaming" => $iscaptureaudio, "ScreenStreaming" => $iscapturevideo, "CaptureCandidatePhoto" => $iscandidatephoto,
         "ImageCaptureTime" => $photocaptime,
         "AllowInstantScoreView" => $flag05,
         "AllowReviewExam" => $flag06,
         "AllowQuestionNavigation" => $flag07,
         "RandomizeQuestion" => $flag08,
         "ShuffleQuestion" => $flag09,
         "Edit_Sectionwise" => $flag10,
         "Edit_SubjectTopicWise" => $flag11,
         "NegativeMarking" => $flag12,
         "NegativeMarks" => $txt4,
         "BlinkTimerTime" => $txt2,
         "NumberOfAlert" => $txt3,
         "IsOptionSuffling" => $flag13,
         "IsCalculatorAllowed" => $flag14,
         "IsShowMarks" => $flag15,
         "MoodleExamID" => $mdlexmid, "UserName" => $fullusername, "IPAddress" => $myipadrs,
         "QuestionObj" => $subjectobj));
    } else {
        $jsondata = array("ExamObj" => array(
            'ExamName' => $examname,
            'ExamDuration' => "$duration",
            'ExamName' => $examname,
            'ExamDuration' => "$duration",
            'ExamType' => "$et",
            'ScheduleStartDate' => $startdate,
            'ScheduleEndDate' => $enddate,
            'TimeZone' => $timezone,
            'UserID' => $USER->id,
            'UserEmailID' => $USER->email,
            "PassingMarks" => $passinggrade,
            "TotalMarks" => $totalmarks,
            "AllowRemoteProctoring" => $enableremoteproctoring,
            "AudioStreaming" => $iscaptureaudio,
            "ScreenStreaming" => $iscapturevideo,
            "CaptureCandidatePhoto" => $iscandidatephoto,
            "ImageCaptureTime" => $photocaptime,

            "AllowInstantScoreView" => $flag05,
            "AllowReviewExam" => $flag06,
            "AllowQuestionNavigation" => $flag07,
            "RandomizeQuestion" => $flag08,
            "ShuffleQuestion" => $flag09,
            "Edit_Sectionwise" => $flag10,
            "Edit_SubjectTopicWise" => $flag11,
            "NegativeMarking" => $flag12,
            "NegativeMarks" => $txt4,
            "BlinkTimerTime" => $txt2,
            "NumberOfAlert" => $txt3,
            "IsOptionSuffling" => $flag13,
            "IsCalculatorAllowed" => $flag14,
            "IsShowMarks" => $flag15,

            "MoodleExamID" => $mdlexmid,
            "UserName" => $fullusername, "IPAddress" => $myipadrs,
            "QuestionObj" => $subjectobj
        ));
    }

    $rv['test'] = json_encode($jsondata);

    if (isset($_POST['editrec']) && ($_POST['editrec'] != '')) {
        $posturl = $apiurl . '/SplashService.svc/UpdateMoodleExamWebAPI';
    } else {
        $posturl = $apiurl . '/SplashService.svc/CreateMoodleExamWebAPI';
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $posturl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, str_replace('u0022', '', str_replace($tokenresr . 'uu0022',
    $tokenresr, str_replace($tokenresr . '""', $tokenresr . '"',
    str_replace("\\", '', json_encode($jsondata, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT))))));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    $headers  = [
        'authenticationkey: ' . $examapikey,
        'Content-Type: application/json'
    ];


    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $serveroutput = curl_exec($ch);

    $pv = json_encode($jsondata);
    $pv2 = json_encode($serveroutput);


    $response = json_decode($serveroutput, true);

    $rv['test'] = $posturl . '\n' . $pv;


    if (isset($_POST['editrec']) && ($_POST['editrec'] != '')) {
        $status = ($response['UpdateMoodleExamWebAPIResult']['0']['objStatusCodes']);
    } else {
        $status = ($response['CreateMoodleExamWebAPIResult']['0']['objStatusCodes']);
    }


    curl_close($ch);
}


if (isset($_POST['withoutsection']) && $_POST['withoutsection'] != '') {


    if ($_POST['courseid'] != '') {

        $eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

        $apiurl = $eklavyaobj->apiurl;
        $examapikey = $eklavyaobj->examapikey;
        $timezone = $eklavyaobj->timezone1;

        $coursed = $DB->get_record_sql("SELECT id,fullname FROM {course} where id=" . $_POST['courseid']);
        $subjectname = $coursed->fullname;
        $subjectid = $coursed->id;
        $quizd = $DB->get_record_sql("SELECT name,timeopen,timeclose,timelimit,sumgrades,id FROM {quiz} where id=" .
        $_POST['withoutsection']);
        $examname = $quizd->name;
        $totalmarks = round($quizd->sumgrades);
        $iteminstance = $quizd->id;

        $grade = $DB->get_record_sql("SELECT gradepass FROM {grade_items} where courseid=" . $_POST['courseid'] .
        " AND iteminstance=" . $iteminstance . " ");
        $passinggrade = round($grade->gradepass);

        if ($quizd->timeopen == "0") {
            $startdate = date("Y-m-d");
        } else {
            $startdate = date("Y-m-d", $quizd->timeopen);
        }
        if ($quizd->timeclose == "0") {
            $enddate = date("Y-m-d");
        } else {
            $enddate = date("Y-m-d", $quizd->timeclose);
        }
        if ($quizd->timelimit == "0") {
            $duration = "60";
        } else {
            $duration = $quizd->timelimit / 60;
        }
    }


    require_once($CFG->libdir . '/questionlib.php');
    require_once($CFG->dirroot . '/question/format.php');

    $questiondata = array();

    if ($questions = $DB->get_records('quiz_slots', array('quizid' => $_POST['withoutsection']))) {
        foreach ($questions as $question) {
            array_push($questiondata, question_bank::load_question_data($question->questionid));
        }
    }
    $contextid = $questiondata[0]->contextid;
    foreach ($questiondata as $key => $questiond) {
        $itemid = "";
        $questionusageid = "";
        $slotid = "";
        $questionkey = $key;

        if (!$data = $DB->get_record_sql("SELECT itemid FROM {files} WHERE component='question' ".
        "AND filearea='questiontext' AND 		contextid=" . $contextid .
        " ORDER BY itemid DESC LIMIT 1", array($contextid))) {
            $itemid = 1;
        } else {
            $itemid = $data->itemid;
        }

        $results = $DB->get_record_sql("SELECT *  FROM {question_attempts} WHERE questionid = " .
        $questiondata[$questionkey]->id . " ORDER BY id DESC LIMIT 1");
        $questionusageid = $results->questionusageid;
        $slotid = $results->slot;
        $file = "pluginfile.php";
        $filearea = "questiontext";
        $component = "question";
        $itemid = $questionusageid . '/' . $slotid . '/' . $questiondata[$questionkey]->id;

        $str = file_rewrite_pluginfile_urls($questiondata[$questionkey]->questiontext, $file, $contextid, $component,
        $filearea, $itemid);
        $formatted = preg_replace('/(?<=src=")(.*?)(?=")/', '$1?token=' . $tokenresr, $str);
        $formatted = preg_replace('/(?<=href=")(.*?)(?=")/', '$1?token=' . $tokenresr, $formatted);
        $formatted = str_replace('moodle/', 'moodle/webservice/', $formatted);
        $questiondata[$questionkey]->questiontext = $formatted;


    }


    $subjectobj = array();



    foreach ($questiondata as $qd) {

        $qid = $qd->id;

        $qcat = $qd->category;

        $quezd = $DB->get_record_sql("SELECT maxmark FROM {quiz_slots} where questionid=" . $qid);
        $defaultmark = round($quezd->maxmark);

        $ransd = $DB->get_record_sql("SELECT rightanswer FROM {question_attempts} where questionid=" . $qid);
        $rightanswer = str_replace('&nbsp;', '', str_replace('{', '', str_replace('}', '', strip_tags($ransd->rightanswer))));

        $optarray = array();
        $cnt = 1;
        $rightanswer = preg_replace('/\s+/', ' ', $rightanswer);
        $rightanswer = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $rightanswer);

        $rightanswer = trim($rightanswer);

        if (count($qd->options->answers) > 5) {
            echo "This Exam can not be exported because " . $qd->name . " has more than 5 answers";

            exit;
        }
        if (count($qd->options->answers) == 0 && $qd->qtype != 'match') {
            echo "Sorry, This exam: " . $qd->name . " can not be exported because it has few questions ".
            "which doesn't have answers and such questions are not supported by Eklavya.";

            exit;
        }



        if ($qd->qtype == 'multichoice') {

            $questiontype = 'MCQ';
        } else if ($qd->qtype == 'truefalse') {

            $questiontype = 'TRUE OR FALSE';
        } else if ($qd->qtype == 'gapselect') {

            $questiontype = 'FILL IN BLANKS';
        } else if ($qd->qtype == 'match') {

            $questiontype = 'MATCH THE PAIR';

            $ccnt = 1;
            $optarray = array();
            foreach ($qd->options->subquestions as $as) {
                $qid = $qd->id;
                $quezd = $DB->get_record_sql("SELECT maxmark FROM {quiz_slots} where questionid=" . $qid);
                $defaultmarks = round($quezd->maxmark);
                $results = $DB->get_record_sql("SELECT *  FROM {question_attempts} WHERE questionid = " . $as->questionid .
                " ORDER BY id DESC LIMIT 1");
                $questionusageid = $results->questionusageid;


                $slotid = $results->slot;
                $file = "pluginfile.php";
                $filearea = "subquestion";
                $component = "qtype_match";
                $itemid = $questionusageid . '/' . $slotid . '/' . $as->id;
                $str2 = file_rewrite_pluginfile_urls($as->questiontext, $file, $contextid, $component, $filearea, $itemid);
                $formatted2 = preg_replace('/(?<=src=")(.*?)(?=")/', '$1?token=' . $tokenresr, $str2);
                $formatted2 = preg_replace('/(?<=href=")(.*?)(?=")/', '$1?token=' . $tokenresr, $formatted2);
                $formatted2 = str_replace('moodle/', 'moodle/webservice/', $formatted2);
                $answrmedia = $formatted2;



                $dom2  = new \DOMDocument();
                $dom2->loadHTML($str2);
                $dom2->preserveWhiteSpace = false;
                $images2 = [];

                foreach ($dom2->getElementsByTagName('img') as $image2) {
                    $images2[] = $image2->getAttribute('src');
                }


                $image2 = str_replace('u0022', '', str_replace("\\", "", $images2[0]));


                if (!strstr($image2, '?token=' . $tokenresr) && $image2 != '') {
                    $image2 = str_replace('moodle/', 'moodle/webservice/', $image2 . '?token=' . $tokenresr);
                }


                $regex2 = '#([^,\s]+\.mp3)#';
                preg_match_all($regex2, $str2, $mp3s2);

                $regex2 = '#([^,\s]+\.mp4)#';
                preg_match_all($regex2, $str2, $mp4s2);

                $regex2 = '#([^,\s]+\.ogg)#';
                preg_match_all($regex2, $str2, $oggs2);

                $regex2 = '#([^,\s]+\.wav)#';
                preg_match_all($regex2, $str2, $wavs2);

                $videov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp4s2[0][0])));
                $vx2 = explode('>', $videov2);
                $video2 = $vx2[0];



                $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $oggs2[0][0])));
                $ax2 = explode('>', $audiov2);
                $audio2 = $ax2[0];
                if (strstr($video2, 'href')) {
                    $video2 = str_replace('href="', '', $video2);





                    $video2 = str_replace('moodle/', 'moodle/webservice/', $video2 . '?token=' . $tokenresr);
                }

                if ($audio2 == '') {
                    $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp3s2[0][0])));
                    $ax2 = explode('>', $audiov2);
                    $audio2 = $ax2[0];
                }

                if ($audio2 == '') {
                    $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $wavs2[0][0])));
                    $ax2 = explode('>', $audiov2);
                    $audio2 = $ax2[0];
                }

                if (!strstr($audio2, '?token=' . $tokenresr) && $audio2 != '') {
                    $audio2 = str_replace('moodle/', 'moodle/webservice/', $audio2 . '?token=' . $tokenresr);
                }
                if (!strstr($video2, '?token=' . $tokenresr) && $video2 != '') {
                    $video2 = str_replace('moodle/', 'moodle/webservice/', $video2 . '?token=' . $tokenresr);
                }


                $delim = "||-||";
                $answerc = $as->answertext;

                if (trim($image2) != '') {
                    $data2 = $image2;
                } else if (trim($audio2) != '') {
                    $data2 = $audio2;
                } else if (trim($video2) != '') {
                    $data2 = $video2;
                }


                if ($data2 == '') {
                    $optarray[] = array("Option" . $ccnt => strip_tags($answrmedia) . $delim . $answerc, "OptionImage" .
                    $ccnt => "", "OptionAudio" . $ccnt => "", "OptionVideo" . $ccnt => "", "IsOption" . $ccnt .
                    "Correct" => "1", "OptionMarks" . $ccnt => "$defaultmarks");
                } else {
                    $optarray[] = array("Option" . $ccnt => $data2 . $delim . $answerc, "OptionImage" . $ccnt => "",
                    "OptionAudio" . $ccnt => "", "OptionVideo" . $ccnt => "", "IsOption" . $ccnt . "Correct" => "1",
                    "OptionMarks" . $ccnt => "$defaultmarks");
                }

                $ccnt = $ccnt + 1;
            }
        } else {

            $questiontype = 'SUBJECTIVE';
        }

        foreach ($qd->options->answers as $as) {





            $results = $DB->get_record_sql("SELECT *  FROM {question_attempts} WHERE questionid = " . $qd->id .
            " ORDER BY id DESC LIMIT 1");
            $questionusageid = $results->questionusageid;
            $slotid = $results->slot;
            $file = "pluginfile.php";
            $filearea = "answer";
            $component = "question";
            $itemid = $questionusageid . '/' . $slotid . '/' . $as->id;




            $str2 = file_rewrite_pluginfile_urls($as->answer, $file, $contextid, $component, $filearea, $itemid);
            $formatted2 = preg_replace('/(?<=src=")(.*?)(?=")/', '$1?token=' . $tokenresr, $str2);
            $formatted2 = preg_replace('/(?<=href=")(.*?)(?=")/', '$1?token=' . $tokenresr, $formatted2);
            $formatted2 = str_replace('moodle/', 'moodle/webservice/', $formatted2);
            $answrmedia = $formatted2;


            $dom2  = new \DOMDocument();
            $dom2->loadHTML($str2);
            $dom2->preserveWhiteSpace = false;

            $images2 = [];

            foreach ($dom2->getElementsByTagName('img') as $image2) {
                $images2[] = $image2->getAttribute('src');
            }




            $regex2 = '#([^,\s]+\.mp3)#';
            preg_match_all($regex2, $str2, $mp3s2);

            $regex2 = '#([^,\s]+\.mp4)#';
            preg_match_all($regex2, $str2, $mp4s2);

            $regex2 = '#([^,\s]+\.ogg)#';
            preg_match_all($regex2, $str2, $oggs2);

            $regex2 = '#([^,\s]+\.wav)#';
            preg_match_all($regex2, $str2, $wavs2);

            $videov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp4s2[0][0])));
            $vx2 = explode('>', $videov2);
            $video2 = $vx2[0];



            $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $oggs2[0][0])));
            $ax2 = explode('>', $audiov2);
            $audio2 = $ax2[0];
            if (strstr($video2, 'href')) {
                $video2 = str_replace('href="', '', $video2);

                $video2 = str_replace('moodle/', 'moodle/webservice/', $video2 . '?token=' . $tokenresr);
            }

            if ($audio2 == '') {
                $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $mp3s2[0][0])));
                $ax2 = explode('>', $audiov2);
                $audio2 = $ax2[0];
            }

            if ($audio2 == '') {
                $audiov2 = str_replace('u0022', '', str_replace("\\", "", str_replace('src="', '', $wavs2[0][0])));
                $ax2 = explode('>', $audiov2);
                $audio2 = $ax2[0];
            }


            $image2 = str_replace('u0022', '', str_replace("\\", "", $images2[0]));


            if (!strstr($image2, '?token=' . $tokenresr) && $image2 != '') {
                $image2 = str_replace('moodle/', 'moodle/webservice/', $image2 . '?token=' . $tokenresr);
            }


            if (!strstr($audio2, '?token=' . $tokenresr) && $audio2 != '') {
                $audio2 = str_replace('moodle/', 'moodle/webservice/', $audio2 . '?token=' . $tokenresr);
            }


            if (!strstr($video2, '?token=' . $tokenresr) && $video2 != '') {
                $video2 = str_replace('moodle/', 'moodle/webservice/', $video2 . '?token=' . $tokenresr);
            }


            $ans = str_replace('&nbsp;', '', trim(strip_tags_content($as->answer)));
            if (trim($ans) == '') {

                $ans = str_replace('&nbsp;', '', trim(strip_tags($as->answer)));
            }
            $anstext = substr($ans, 0, strpos($ans, '@@'));
            if (trim($anstext) == '') {
                $anstext = $ans;
                $nn = explode('@@PLUGINFILE', $anstext);
                $anstext = $nn[0];
            }
            if (trim($rightanswer) == $ans) {
                $raw = "1";
            } else {
                $raw = "0";
            }
            $idar = array();
            if ($gds = $DB->get_records('question_answers', array('question' => $qid))) {
                foreach ($gds as $gd) {
                    $ans2 = str_replace('&nbsp;', '', str_replace('{', '', str_replace('}', '', strip_tags($gd->answer))));
                    $ans2r = preg_replace('/\s+/', ' ', $ans2);
                    $ans2 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $ans2);

                    $ans2 = trim($ans2);
                    $idar[] = array('ag' => $gd->fraction, 'ans' => $ans2);
                }
            }

            foreach ($idar as $ii) {


                if ($ii['ans'] == $ans) {
                    $mmarks = $ii['ag'];
                }
            }


            $mmarks = round($mmarks);


            $optarray[] = array("Option" . $cnt => $anstext, "OptionImage" . $cnt => $image2, "OptionAudio" .
            $cnt => "$audio2", "OptionVideo" . $cnt => "$video2", "IsOption" . $cnt . "Correct" => "$raw",
            "OptionMarks" . $cnt => "$mmarks");

            $cnt = $cnt + 1;
        }

        $question = strip_tags($qd->questiontext);
        $regex = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";

        $question = preg_replace($regex, '', $question);




        $str = $qd->questiontext;

        $dom  = new \DOMDocument();
        $dom->loadHTML($str);
        $dom->preserveWhiteSpace = false;
        $images = [];

        foreach ($dom->getElementsByTagName('img') as $image) {
            $images[] = $image->getAttribute('src');
        }


        $regex = '#([^,\s]+\.mp3)#';
        preg_match_all($regex, $str, $mp3s);

        $regex = '#([^,\s]+\.mp4)#';
        preg_match_all($regex, $str, $mp4s);



        $videov = str_replace("\\", "", str_replace('src="', '', $mp4s[0][0]));
        $vx = explode('>', $videov);
        $video = $vx[0];

        $audiov = str_replace("\\", "", str_replace('src="', '', $mp3s[0][0]));
        $ax = explode('>', $audiov);
        $audio = $ax[0];

        $image = str_replace("\\", "", $images[0]);

        $optarray = call_user_func_array('array_merge', $optarray);

        if ($audio == '') {

            $regex = '#([^,\s]+\.m4a)#';
            preg_match_all($regex, $str, $mp4as);


            $audiov = str_replace("\\", "", str_replace('src="', '', $mp4as[0][0]));
            $ax = explode('>', $audiov);
            $audio = $ax[0];
        }

        $subjectobj[] = array('SubjectID' => "$subjectid", 'SubjectName' => $subjectname, 'TopicID' => "0",
        'TopicName' => $subjectname, 'QuestionType' => "$questiontype", 'DifficultyLevel' => 'Basic',
        'Question' => $question, 'Image' => $image, 'Audio' => str_replace('href=', '', $audio),
        'Video' => $video, "Marks" => "$defaultmark", "MoodleQuestionID" => $qid, 'UserEmailID' => $USER->email,
        "UserName" => $fullusername, "IPAddress" => $myipadrs) + $optarray;
    }

    if ($qd->qtype == 'shortanswer') {
        $et = '2';
    } else {
        $et = '3';
    }
    $questionobj = array("QuestionObj" => $subjectobj);


    if (isset($_POST['editrec']) && ($_POST['editrec'] != '')) {

        $jsondata = array("ExamObj" => array('ExamName' => $examname, "ScheduleID" => $scheduleid,
        'ExamDuration' => "$duration", 'ExamName' => $examname, 'ExamDuration' => "$duration",
        'ExamType' => "$et", 'ScheduleStartDate' => $startdate, 'ScheduleEndDate' => $enddate,
        'TimeZone' => $timezone, 'UserID' => $USER->id, 'UserEmailID' => $USER->email,
        "PassingMarks" => $passinggrade, "TotalMarks" => $totalmarks, "AllowRemoteProctoring" => $enableremoteproctoring,
        "AudioStreaming" => $iscaptureaudio, "ScreenStreaming" => $iscapturevideo, "CaptureCandidatePhoto" => $iscandidatephoto,
        "ImageCaptureTime" => $photocaptime, "MoodleExamID" => $mdlexmid, "UserName" => $fullusername, "IPAddress" => $myipadrs,
        "QuestionObj" => $subjectobj));
    } else {
        $jsondata = array("ExamObj" => array('ExamName' => $examname, 'ExamDuration' => "$duration", 'ExamName' => $examname,
        'ExamDuration' => "$duration", 'ExamType' => "$et", 'ScheduleStartDate' => $startdate, 'ScheduleEndDate' => $enddate,
        'TimeZone' => $timezone, 'UserID' => $USER->id, 'UserEmailID' => $USER->email, "PassingMarks" => $passinggrade,
        "TotalMarks" => $totalmarks, "AllowRemoteProctoring" => $enableremoteproctoring, "AudioStreaming" => $iscaptureaudio,
        "ScreenStreaming" => $iscapturevideo, "CaptureCandidatePhoto" => $iscandidatephoto, "ImageCaptureTime" => $photocaptime,
        "MoodleExamID" => $mdlexmid, "UserName" => $fullusername, "IPAddress" => $myipadrs, "QuestionObj" => $subjectobj));
    }


    if (isset($_POST['editrec']) && ($_POST['editrec'] != '')) {
        $posturl = $apiurl . '/SplashService.svc/UpdateMoodleExamWebAPI';
    } else {
        $posturl = $apiurl . '/SplashService.svc/CreateMoodleExamWebAPI';
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $posturl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, str_replace('u0022', '', str_replace($tokenresr . 'uu0022', $tokenresr,
    str_replace($tokenresr . '""', $tokenresr . '"', str_replace("\\", '',
    json_encode($jsondata, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT))))));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    $headers  = [
        'authenticationkey: ' . $examapikey,
        'Content-Type: application/json'
    ];


    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $serveroutput = curl_exec($ch);
    $response = json_decode($serveroutput, true);

    if (isset($_POST['editrec']) && ($_POST['editrec'] != '')) {
        $status = ($response['UpdateMoodleExamWebAPIResult']['0']['objStatusCodes']);
    } else {
        $status = ($response['CreateMoodleExamWebAPIResult']['0']['objStatusCodes']);
    }


    curl_close($ch);
}


if ($status != '') {

    $response['CreateMoodleExamWebAPIResult'];

    if (isset($_POST['editrec']) && ($_POST['editrec'] != '')) {

        $rv['isok'] = $response['UpdateMoodleExamWebAPIResult']['0']['objStatusCodes'];
        $rv['msg1'] = $response['UpdateMoodleExamWebAPIResult']['0']['Message'];
        $rv['msg2'] = $response['UpdateMoodleExamWebAPIResult']['0']['OutputMessage'];

    } else {
        $rv['isok'] = $response['CreateMoodleExamWebAPIResult']['0']['objStatusCodes'];
        $rv['msg1'] = $response['CreateMoodleExamWebAPIResult']['0']['Message'];
        $rv['msg2'] = $response['CreateMoodleExamWebAPIResult']['0']['OutputMessage'];

    }

}


ob_end_clean();



echo json_encode($rv);
