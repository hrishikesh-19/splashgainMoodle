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

global $USER, $DB;


$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;

// Load edit post fields.

if (isset($_POST['editrec'])) {
    $iseditmode = 1;
} else {
    $iseditmode = 0;
}


if (isset($_POST['editrecid'])) {
    $editrecid = $_POST['editrecid'];
} else {
    $editrecid = 0;
}



if (isset($_POST['courseid'])) {
    $courseid = $_POST['courseid'];
} else {
    $courseid = 0;
}

if (isset($_POST['associated'])) {

    $associated = $_POST['associated'];
} else {
    $associated = 0;
}


if (isset($_POST['section'])) {

    $section = $_POST['section'];
} else {
    $section = 0;
}


if (isset($_POST['quiz'])) {

    $quiz = $_POST['quiz'];
} else {
    $quiz = 0;
}

if (isset($_POST['withoutsection'])) {

    $withoutsection = $_POST['withoutsection'];
} else {
    $withoutsection = 0;
}


if (isset($_POST['quiz'])) {
    $mdlexmid = $_POST['quiz'];
}

$erp = 1;
if (isset($_POST['associatedq1'])) {
    $erp = $_POST['associatedq1'];
}

$cca = 1;
if (isset($_POST['associatedq2'])) {
    $cca = $_POST['associatedq2'];
}

$ccs = 1;
if (isset($_POST['associatedq3'])) {
    $ccs = $_POST['associatedq3'];
}

$ccp = 0;
if (isset($_POST['associatedq4'])) {
    $ccp = $_POST['associatedq4'];
}

$pct = "0";
if (isset($_POST['txt1'])) {
    $pct = $_POST['txt1'];
}

$aisv = 1;
if (isset($_POST['flag05'])) {
    $aisv = $_POST['flag05'];
}

$erqtc = 1;
if (isset($_POST['flag06'])) {
    $erqtc = $_POST['flag06'];
}

$aqn = 1;
if (isset($_POST['flag07'])) {
    $aqn = $_POST['flag07'];
}

$rmq = 1;
if (isset($_POST['flag08'])) {
    $rmq = $_POST['flag08'];
}

$shfq = 1;
if (isset($_POST['flag09'])) {
    $shfq = $_POST['flag09'];
}

$cews = 0;
if (isset($_POST['flag10'])) {
    $cews = $_POST['flag10'];
}

$ssotas = 0;
if (isset($_POST['flag11'])) {
    $ssotas = $_POST['flag11'];
}

$negmrk = 0;
if (isset($_POST['flag12'])) {
    $negmrk = $_POST['flag12'];
}

$aaosh = 1;
if (isset($_POST['flag13'])) {
    $aaosh = $_POST['flag13'];
}

$acite = 1;
if (isset($_POST['flag14'])) {
    $acite = $_POST['flag14'];
}

$smfqie = 1;
if (isset($_POST['flag15'])) {
    $smfqie = $_POST['flag15'];
}

$trfc = "1";
if (isset($_POST['txt2'])) {
    $trfc = $_POST['txt2'];
}

$mnoafsew = "10";
if (isset($_POST['txt3'])) {
    $mnoafsew = $_POST['txt3'];
}

$engm = "0";
if (isset($_POST['txt4'])) {
    $engm = $_POST['txt4'];
}

echo $OUTPUT->header();

?>

<div class="authform">
    
    <?php require('../../ekl_nav.php');  ?>

    <?php
    if ($iseditmode == 1) {
    ?>
        <div id="titleui" class="col-md-12 pt-4 authform">
            <h3> Exam(s) >> Exam Updation </h3>
        </div>
        <?php
    } else {
    ?>
        <div id="titleui" class="col-md-12 pt-4 authform">
            <h3> Exam(s) >> Exam Creation </h3>
        </div>
        <?php
    }

    ?>

    <div id="debugtest">

    </div>


    <div id="formfields">

        <input type="hidden" id="formiseditmode" name="formiseditmode" value="<?php echo $iseditmode; ?>">

        <input type="hidden" id="formeditrecid" name="formeditrecid" value="<?php echo $editrecid; ?>">

        <input type="hidden" id="formcourseid" name="formcourseid" value="<?php echo $courseid; ?>">

        <input type="hidden" id="formassociated" name="formassociated" value="<?php echo $associated; ?>">

        <input type="hidden" id="formsection" name="formsection" value="<?php echo $section; ?>">

        <input type="hidden" id="formquiz" name="formquiz" value="<?php echo $quiz; ?>">

        <input type="hidden" id="formwithoutsection" name="formwithoutsection" value="<?php echo $withoutsection; ?>">

        <input type="hidden" id="formenableremoteproctoring" name="formenableremoteproctoring" value="<?php echo $erp; ?>">

        <input type="hidden" id="formisaudiocapture" name="formisaudiocapture" value="<?php echo $cca; ?>">

        <input type="hidden" id="formisscreencapture" name="formisscreencapture" value="<?php echo $ccs; ?>">

        <input type="hidden" id="formisphotocapture" name="formisphotocapture" value="<?php echo $ccp; ?>">

        <input type="hidden" id="formphotocapturetime" name="formphotocapturetime" value="<?php echo $pct; ?>">

        <!-- flags -->

        <input type="hidden" id="formallowinstantscoreview" name="formallowinstantscoreview"
        value="<?php echo $aisv; ?>">

        <input type="hidden" id="formenablereviewquestiontocandidate" name="formenablereviewquestiontocandidate" 
        value="<?php echo $erqtc; ?>">

        <input type="hidden" id="formallowquestionnavigation" name="formallowquestionnavigation" value="<?php echo $aqn; ?>">

        <input type="hidden" id="formrandomisequestion" name="formrandomisequestion" value="<?php echo $rmq; ?>">

        <input type="hidden" id="formshufflequestion" name="formshufflequestion" value="<?php echo $shfq; ?>">

        <input type="hidden" id="formconductexamwithsections" name="formconductexamwithsections" value="<?php echo $cews; ?>">

        <input type="hidden" id="formsetsubjectortopicassection" name="formsetsubjectortopicassection" 
        value="<?php echo $ssotas; ?>">

        <input type="hidden" id="formnegativemarking" name="formnegativemarking" value="<?php echo $negmrk; ?>">

        <input type="hidden" id="formenternegativemarks" name="formenternegativemarks" value="<?php echo $engm; ?>">


        <input type="hidden" id="formallowansweroptionshuffling" name="formallowansweroptionshuffling" 
        value="<?php echo $aaosh; ?>">

        <input type="hidden" id="formallowcalculatorintheexam" name="formallowcalculatorintheexam"
         value="<?php echo $acite; ?>">

        <input type="hidden" id="formshowmarksforquestioninexam" name="formshowmarksforquestioninexam" 
        value="<?php echo $smfqie; ?>">

        <input type="hidden" id="formtimereminderforcandidate" name="formtimereminderforcandidate" 
        value="<?php echo $trfc; ?>">

        <input type="hidden" id="formmaxnumberofalertsforswitchingexamwindow" name="formmaxnumberofalertsforswitchingexamwindow" 
        value="<?php echo $mnoafsew; ?>">



    </div>

    <hr>

    <div id="contentform">


        <div id="courseui">


        </div>

        <div id="AssociatedUI">


        </div>

        <div id="TopicsUI">


        </div>

        <div id="QuizUI">


        </div>


        <div id="QuizFlagsUI">


        </div>



    </div>

    <hr>

    <div class="col-sm-12 pt-4 text-right">
        <form action="../index.php" method="post">
            <input type="submit" class="btn btn-outline-primary" value="Back to List ">
        </form>


    </div>


</div>

<?php
echo $OUTPUT->footer();

?>




<script>
    $(document).ready(function() {

        $("#page-header").hide();
               
        showcourseui();

    });
</script>

<!-- Course UI script -->
<script>
    function showcourseui() {
      
        var ci = $("#formcourseid").val();

        $.ajax({
            url: "get_courses_ui.php",
            method: "POST",
            data: {
                courseid: ci
            },
            success: function(dataabc) {
                //console.log(dataabc);
                //alert(dataabc);
                $("#courseui").html(dataabc);
                changecourse();
                
            }
        });

    }

    function changecourse() {

        var ci = $("#courseid").val();

        $("#formcourseid").val(ci);
        //alert('ok');

        showassociatedui();

    }
</script>

<!-- Associated UI script -->
<script>
    function showassociatedui() {
        var ci = $("#formcourseid").val();
        var aso = $("#formassociated").val();


      

        if (!(aso > 0)) {

            $("#formassociated").val(1);
            aso = 1;
        }

        if (ci > 0) {

            $.ajax({
                url: "get_associated_ui.php",
                method: "POST",
                data: {
                    courseid: ci,
                    aso: aso
                },
                success: function(dataabc) {
                    //console.log(dataabc);
                    $("#AssociatedUI").html(dataabc);

                    changeassociated(aso);

                    //alert(dataabc);
                }
            });
        } else {
            $("#AssociatedUI").html('');
        }

    }

    function changeassociated(yesno) {

        $("#formassociated").val(yesno);

        $("#TopicsUI").html('');
        $("#QuizUI").html('');

    
        if (yesno == 1) // course wise
        {


            showquizui();


        } else if (yesno == 2) // topic wise
        {

            showtopicui();
            showquizui();

        }

    }
</script>


<!-- Topic UI script -->
<script>
    function showtopicui() {
        var ci = $("#formcourseid").val();
        var aso = $("#formassociated").val();
        var sec = $("#formsection").val();


        if (aso == 2) {

            $.ajax({
                url: "get_topic_ui.php",
                method: "POST",
                data: {
                    courseid: ci,
                    aso: aso,
                    section: sec
                },
                success: function(dataabc) {
                    //console.log(dataabc);
                    $("#TopicsUI").html(dataabc);

                    //alert(dataabc);
                }
            });
        } else {
            $("#TopicsUI").html('');
        }

    }

    function changetopic() {

        var se = $("#section").val();

        $("#formsection").val(se);

        //alert('topic sel updated')
        showquizui();

    }
</script>




<!-- Quiz UI script -->
<script>
    function showquizui() {

        var ci = $("#formcourseid").val();
        var aso = $("#formassociated").val();
        var sec = $("#formsection").val();
        var qz = $("#formquiz").val();
        var wsqz = $("#formwithoutsection").val();

        
        if ((aso == 1) || (aso == 2 && sec > 0)) {

            $.ajax({
                url: "get_quiz_ui.php",
                method: "POST",
                data: {
                    courseid: ci,
                    aso: aso,
                    section: sec,
                    withoutsection: wsqz,
                    quiz: qz
                },
                success: function(dataabc) {
                    //console.log(dataabc);
                    $("#QuizUI").html(dataabc);
                    //alert(dataabc);
                    if (aso == 1) {
                        changewithoutsection();
                    } else if (aso == 2) {

                        changequiz();

                    }

                    //alert(dataabc);
                }
            });
        } else {
            $("#QuizUI").html('');
        }

    }

    function changequiz() {

        var se = $("#quiz").val();

        $("#formquiz").val(se);

        showquizflagsui();

    }

    function changewithoutsection() {

        var se = $("#withoutsection").val();

        $("#formwithoutsection").val(se);

        showquizflagsui();

    }
</script>







<!-- Quiz Flags UI script -->
<script>
    function showquizflagsui() {

        var ci = $("#formcourseid").val();
        var aso = $("#formassociated").val();
        var sec = $("#formsection").val();
        var qz = $("#formquiz").val();
        var wsqz = $("#formwithoutsection").val();


        var erp = $("#formenableremoteproctoring").val();
        if (!(erp >= 0)) {
            $("#formenableremoteproctoring").val(1);
            erp = 1;
        }

        var cca = $("#formisaudiocapture").val();
        if (!(cca >= 0)) {
            $("#formisaudiocapture").val(1);
            cca = 1;
        }

        var ccs = $("#formisscreencapture").val();
        if (!(ccs >= 0)) {
            $("#formisscreencapture").val(1);
            ccs = 1;
        }

        var ccp = $("#formisphotocapture").val();
        if (!(ccp >= 0)) {
            $("#formisphotocapture").val(0);
            ccp = 0;
        }


        var pct = $("#formphotocapturetime").val();


        var aisv = $("#formallowinstantscoreview").val();
        if (!(aisv >= 0)) {
            $("#formallowinstantscoreview").val(0);
            aisv = 0;
        }

        var erqtc = $("#formenablereviewquestiontocandidate").val();
        if (!(erqtc >= 0)) {
            $("#formenablereviewquestiontocandidate").val(0);
            erqtc = 0;
        }

        var aqn = $("#formallowquestionnavigation").val();
        if (!(aqn >= 0)) {
            $("#formallowquestionnavigation").val(0);
            aqn = 0;
        }

        var rmq = $("#formrandomisequestion").val();
        if (!(rmq >= 0)) {
            $("#formrandomisequestion").val(0);
            rmq = 0;
        }

        var shfq = $("#formshufflequestion").val();
        if (!(shfq >= 0)) {
            $("#formshufflequestion").val(0);
            shfq = 0;
        }

        var cews = $("#formconductexamwithsections").val();
        if (!(cews >= 0)) {
            $("#formconductexamwithsections").val(0);
            cews = 0;
        }

        var ssotas = $("#formsetsubjectortopicassection").val();
        if (!(ssotas >= 0)) {
            $("#formsetsubjectortopicassection").val(0);
            ssotas = 0;
        }

        var negmrk = $("#formnegativemarking").val();
        if (!(negmrk >= 0)) {
            $("#formnegativemarking").val(0);
            negmrk = 0;
        }

        var engm = $("#formenternegativemarks").val();
        if (!(engm >= 0)) {
            $("#formenternegativemarks").val(0);
            engm = 0;
        }


        var aaosh = $("#formallowansweroptionshuffling").val();
        if (!(aaosh >= 0)) {
            $("#formallowansweroptionshuffling").val(0);
            aaosh = 0;
        }

        var acite = $("#formallowcalculatorintheexam").val();
        if (!(acite >= 0)) {
            $("#formallowcalculatorintheexam").val(0);
            acite = 0;
        }


        var smfqie = $("#formshowmarksforquestioninexam").val();
        if (!(smfqie >= 0)) {
            $("#formshowmarksforquestioninexam").val(0);
            smfqie = 0;
        }


        var trfc = $("#formtimereminderforcandidate").val();

        var mnoafsew = $("#formmaxnumberofalertsforswitchingexamwindow").val();



        //alert(wsqz);

        if ((aso == 1 && wsqz > 0) || (aso == 2 && qz > 0)) {
            $.ajax({
                url: "get_quiz_options_ui.php",
                method: "POST",
                data: {
                    selenableremoteproctoring: erp,
                    selcapturecandidateaudio: cca,
                    selcapturecandidatescreen: ccs,
                    selcapturecandidatephoto: ccp,
                    selphotocapturetime: pct,
                    selallowinstantscoreview: aisv,
                    selenablereviewquestiontocandidate: erqtc,
                    selallowquestionnavigation: aqn,
                    selrandomisequestion: rmq,
                    selshufflequestion: shfq,
                    selconductexamwithsections: cews,
                    selsetsubjectortopicassection: ssotas,
                    selnegativemarking: negmrk,
                    selenternegativemarks: engm,
                    selallowansweroptionshuffling: aaosh,
                    selallowcalculatorintheexam: acite,
                    selshowmarksforquestioninexam: smfqie,
                    seltimereminderforcandidate: trfc,
                    selmaxnumberofalertsforswitchingexamwindow: mnoafsew

                },
                success: function(dataabc) {
                    //console.log(dataabc);
                    $("#QuizFlagsUI").html(dataabc);

                    changeenableremoteproctoring(erp);

                    changecapturecandidateaudio(cca);

                    changecapturecandidatescreen(ccs);

                    changecapturecandidatephoto(ccp);

                    changephotocapturetime(pct);

                    changeallowinstantscoreview(aisv);

                    changeenablereviewquestiontocandidate(erqtc);

                    changeallowquestionnavigation(aqn);

                    changerandomisequestion(rmq);

                    changeshufflequestion(shfq);

                    changeconductexamwithsections(cews);

                    changesetsubjectortopicassection(ssotas);

                    changenegativemarking(negmrk);

                    changeenternegativemarks(engm);

                    changeallowansweroptionshuffling(aaosh);

                    changeallowcalculatorintheexam(acite);

                    changeshowmarksforquestioninexam(smfqie);

                    changetimereminderforcandidate(trfc);

                    changemaxnumberofalertsforswitchingexamwindow(mnoafsew);




                    //alert(dataabc);
                }
            });

        } else {
            $("#QuizFlagsUI").html('');
        }

        //alert(erp);




    }

    function changeenableremoteproctoring(yesno) {

        $("#formenableremoteproctoring").val(yesno);
        //alert(yesno);
        $('#erpyesdetailsui').hide();
        $('#erpnodetailsui').hide();

        //alert(yesno);
        if (yesno == 1) // true
        {
            $('#erpyesdetailsui').show();
            $('#erpnodetailsui').hide();


        } else // false
        {
            $('#erpyesdetailsui').hide();
            $('#erpnodetailsui').show();
        }

    }

    function changecapturecandidateaudio(yesno) {
        if (yesno == 1) // true
        {
            $('#formisaudiocapture').val(1);
        } else // false
        {
            $('#formisaudiocapture').val(0);
        }

    }


    function changecapturecandidatescreen(yesno) {
        $('#formisscreencapture').val(yesno);
    }

    function changecapturecandidatephoto(yesno) {

        if (yesno == 1) // true
        {
            $('#formisphotocapture').val(1);
            $("#photocapturedetailsui").show();
        } else // false
        {
            $('#formisphotocapture').val(0);
            $("#photocapturedetailsui").hide();
        }

    }


    function changephotocapturetime() {
        var vl = $('#txt1').val();
        $('#formisphotocapture').val(vl);
    }

    function changeallowinstantscoreview(yesno) {

        $('#formallowinstantscoreview').val(yesno);

    }

    function changeenablereviewquestiontocandidate(yesno) {

        $('#formenablereviewquestiontocandidate').val(yesno);

    }

    function changeallowquestionnavigation(yesno) {

        $('#formallowquestionnavigation').val(yesno);

    }

    function changerandomisequestion(yesno) {

        $('#formrandomisequestion').val(yesno);
        if (yesno == 1) {
            $('#formshufflequestion').val(2);
            document.getElementById('radio9n').checked = true;
        } else {
            //$('#formshufflequestion').val(1);        
            //document.getElementById('radio9y').checked = true;
        }

    }

    function changeshufflequestion(yesno) {

        $('#formshufflequestion').val(yesno);
        if (yesno == 1) {
            $('#formrandomisequestion').val(2);
            document.getElementById('radio8n').checked = true;
        } else {
            //$('#formrandomisequestion').val(1);        
            // document.getElementById('radio8y').checked = true;
        }

    }

    function changeconductexamwithsections(yesno) {

        $('#formconductexamwithsections').val(yesno);
        if (yesno == 1) {
            $('#flag11ui').show();
        } else {
            $('#flag11ui').hide();
        }

    }

    function changesetsubjectortopicassection(yesno) {

        $('#formsetsubjectortopicassection').val(yesno);

    }


    function changenegativemarking(yesno) {

        $('#formnegativemarking').val(yesno);
        if (yesno == 1) {
            $("#negativemarkui").show();
        } else {
            $("#negativemarkui").hide();
        }

    }

    function changeallowansweroptionshuffling(yesno) {

        $('#formallowansweroptionshuffling').val(yesno);

    }

    function changeallowcalculatorintheexam(yesno) {

        $('#formallowcalculatorintheexam').val(yesno);

    }


    function changeshowmarksforquestioninexam(yesno) {

        $('#formshowmarksforquestioninexam').val(yesno);

    }

    function changetimereminderforcandidate() {
        var vl = $('#txt2').val();
        $('#formtimereminderforcandidate').val(vl);
    }

    function changemaxnumberofalertsforswitchingexamwindow() {
        var vl = $('#txt3').val();
        $('#formmaxnumberofalertsforswitchingexamwindow').val(vl);
    }

    function changeenternegativemarks() {
        var vl = $('#txt4').val();
        $('#formenternegativemarks').val(vl);
    }
</script>


<script>
    function saveform() {
        //alert('saving data');

        var ci = $("#courseid").val();

        var se = $("#section").val();

        var qz = $("#quiz").val();

        var ws = $("#withoutsection").val();

        var qbri = $("#formassociated").val();

        if (qbri == 1) // course
        {
            qzby = 'course';
        } else if (qbri == 2) // topic
        {
            qzby = 'topic';
        }


        //var aq1=$("#selenableremoteproctoring").val();
        if ($("#formenableremoteproctoring").val() == 1) {
            var aq1 = "Yes";
        } else {
            var aq1 = "No";
        }


        if ($("#formisaudiocapture").val() == 1) {
            var aq2 = "Yes";
        } else {
            var aq2 = "No";
        }


        if ($("#formisscreencapture").val() == 1) {
            var aq3 = "Yes";
        } else {
            var aq3 = "No";
        }

        if ($("#formisphotocapture").val() == 1) {
            var aq4 = "Yes";
        } else {
            var aq4 = "No";
        }

        if ($("#formallowinstantscoreview").val() == 1) {
            var flag05 = "1";
        } else {
            var flag05 = "0";
        }

        if ($("#formenablereviewquestiontocandidate").val() == 1) {
            var flag06 = "1";
        } else {
            var flag06 = "0";
        }

        if ($("#formallowquestionnavigation").val() == 1) {
            var flag07 = "1";
        } else {
            var flag07 = "0";
        }

        if ($("#formrandomisequestion").val() == 1) {
            var flag08 = "1";
        } else {
            var flag08 = "0";
        }

        if ($("#formshufflequestion").val() == 1) {
            var flag09 = "1";
        } else {
            var flag09 = "0";
        }

        if ($("#formconductexamwithsections").val() == 1) {
            var flag10 = "1";
        } else {
            var flag010 = "0";
        }

        if ($("#formsetsubjectortopicassection").val() == 1) {
            var flag11 = "1";
        } else {
            var flag011 = "0";
        }


        if ($("#formnegativemarking").val() == 1) {
            var flag12 = "1";
        } else {
            var flag012 = "0";
        }

        if ($("#formallowansweroptionshuffling").val() == 1) {
            var flag13 = "1";
        } else {
            var flag013 = "0";
        }

        if ($("#formallowcalculatorintheexam").val() == 1) {
            var flag14 = "1";
        } else {
            var flag014 = "0";
        }

        if ($("#formshowmarksforquestioninexam").val() == 1) {
            var flag15 = "1";
        } else {
            var flag015 = "0";
        }

        var txt1 = $("#formphotocapturetime").val();

        var txt2 = $("#formtimereminderforcandidate").val();

        var txt3 = $("#formmaxnumberofalertsforswitchingexamwindow").val();

        var txt4 = $("#formenternegativemarks").val();


   

        $.ajax({
            url: "save_exam.php",
            method: "POST",
            data: {
                courseid: ci,
                section: se,
                withoutsection: ws,
                quiz: qz,
                associated: qzby,
                associatedq1: aq1,
                associatedq2: aq2,
                associatedq3: aq3,
                associatedq4: aq4,
                flag05: flag05,
                flag06: flag06,
                flag07: flag07,
                flag08: flag08,
                flag09: flag09,
                flag10: flag10,
                flag11: flag11,
                flag12: flag12,
                flag13: flag13,
                flag14: flag14,
                flag15: flag15,
                txt1: txt1,
                txt2: txt2,
                txt3: txt3,
                txt4: txt4,
                continue: "Continue",
            },
            success: function(dataabc) {
                //console.log(dataabc);
                //$("#QuizUI").html(dataabc);
                //alert(dataabc)

                var data = JSON.parse(dataabc);

                // alert(data['test']);


                //$("#debugtest").html(data['sentdata']);


                if (data['isok'] > 0) {
                  

                    alert(data['msg1'] + '\n' + data['msg2']);


                    window.location.href="../index.php";

                } else {

                    // not sucess

                }





            }
        });

    }
</script>
