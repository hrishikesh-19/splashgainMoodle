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

// Sel enable remote proctoring.

if (isset($_POST['selenableremoteproctoring'])) {
    $selenableremoteproctoring = $_POST['selenableremoteproctoring'];
}

// Sel capture candidate audio.
if (isset($_POST['selcapturecandidateaudio'])) {
    $selcapturecandidateaudio = $_POST['selcapturecandidateaudio'];
}

// Sel capture candidat escreen.
if (isset($_POST['selcapturecandidatescreen'])) {
    $selcapturecandidatescreen = $_POST['selcapturecandidatescreen'];
}

// Sel capture candidate photo.
if (isset($_POST['selcapturecandidatephoto'])) {
    $selcapturecandidatephoto = $_POST['selcapturecandidatephoto'];
}

if (isset($_POST['selphotocapturetime'])) {
    $selphotocapturetime = $_POST['selphotocapturetime'];
}

if (isset($_POST['selphotocapturetime'])) {
    $selphotocapturetime = $_POST['selphotocapturetime'];
}

if (isset($_POST['selallowinstantscoreview'])) {
    $selallowinstantscoreview = $_POST['selallowinstantscoreview'];
}

if (isset($_POST['selenablereviewquestiontocandidate'])) {
    $selenablereviewquestiontocandidate = $_POST['selenablereviewquestiontocandidate'];
}

if (isset($_POST['selallowquestionnavigation'])) {
    $selallowquestionnavigation = $_POST['selallowquestionnavigation'];
}

if (isset($_POST['selrandomisequestion'])) {
    $selrandomisequestion = $_POST['selrandomisequestion'];
}


if (isset($_POST['selshufflequestion'])) {
    $selshufflequestion = $_POST['selshufflequestion'];
}

if (isset($_POST['selconductexamwithsections'])) {
    $selconductexamwithsections = $_POST['selconductexamwithsections'];
}

if (isset($_POST['selsetsubjectortopicassection'])) {
    $selsetsubjectortopicassection = $_POST['selsetsubjectortopicassection'];
}

if (isset($_POST['selnegativemarking'])) {
    $selnegativemarking = $_POST['selnegativemarking'];
}

if (isset($_POST['selenternegativemarks'])) {
    $selenternegativemarks = $_POST['selenternegativemarks'];
}


if (isset($_POST['selallowansweroptionshuffling'])) {
    $selallowansweroptionshuffling = $_POST['selallowansweroptionshuffling'];
}


if (isset($_POST['selallowcalculatorintheexam'])) {
    $selallowcalculatorintheexam = $_POST['selallowcalculatorintheexam'];
}

if (isset($_POST['selshowmarksforquestioninexam'])) {
    $selshowmarksforquestioninexam = $_POST['selshowmarksforquestioninexam'];
}

if (isset($_POST['seltimereminderforcandidate'])) {
    $seltimereminderforcandidate = $_POST['seltimereminderforcandidate'];
}

if (isset($_POST['selmaxnumberofalertsforswitchingexamwindow'])) {
    $selmaxnumberofalertsforswitchingexamwindow = $_POST['selmaxnumberofalertsforswitchingexamwindow'];
}

// Load Selected Flags Ends.

?>



<div class="row m-t-1">


    <div class="col-md-3 text-right"> Enable remote proctoring :</div>

    <div class="col-md-6 input-group">
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" id="radio1" name="associatedQ1" class="form-check-input" 
                <?php if ($selenableremoteproctoring == 1) {
                                                                                                    echo 'checked="checked"';
                } ?> value="Yes" onchange="changeenableremoteproctoring(1);">Yes
            </label>
        </div>
        <div class="form-check m-l-1">
            <label class="form-check-label">
                <input type="radio" id="radio1" name="associatedQ1" class="form-check-input" 
                <?php if (!$selenableremoteproctoring == 1) {
                                                                                                    echo 'checked="checked"';
                } ?> value="No" onchange="changeenableremoteproctoring(0);">No
            </label>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<div id="erpyesdetailsui">

    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Capture candidate audio :</div>

        <input type="hidden" id="selcapturecandidateaudio" name="selcapturecandidateaudio" 
        value="<?php echo $selcapturecandidateaudio; ?>">


        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio2" name="associatedq2" class="form-check-input" 
                    <?php if ($selcapturecandidateaudio == 1) {
                            echo 'checked="checked"';
                    } ?> value="Yes" onchange="changecapturecandidateaudio(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio2" name="associatedq2" class="form-check-input" 
                    <?php if (!$selcapturecandidateaudio == 1) {
                            echo 'checked="checked"';
                    } ?> value="No" onchange="changecapturecandidateaudio(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


    <div class="row m-t-1">

        <div class="col-md-3 text-right"> Capture candidate Screen :</div>

        <input type="hidden" id="selcapturecandidatescreen" name="selcapturecandidatescreen" 
        value="<?php echo $selcapturecandidatescreen; ?>">

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio3" name="associatedq3" class="form-check-input" 
                    <?php if ($selcapturecandidatescreen == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changecapturecandidatescreen(1);">Yes
                </label>
            </div>

            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio3" name="associatedq3" class="form-check-input" 
                    <?php if (!$selcapturecandidatescreen == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changecapturecandidatescreen(0);">No
                </label>
            </div>
        </div>

        <div class="col-md-3"></div>
    </div>

</div>



<div id="erpnodetailsui">

    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Capture candidate photo :</div>

        <input type="hidden" id="selcapturecandidatephoto" name="selcapturecandidatephoto" 
        value="<?php echo $selcapturecandidatephoto; ?>">


        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio4" name="associatedq4" class="form-check-input" 
                    <?php if ($selcapturecandidatephoto == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changecapturecandidatephoto(1);">Yes
                </label>
            </div>

            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio4" name="associatedq4" class="form-check-input" 
                    <?php if (!$selcapturecandidatephoto == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changecapturecandidatephoto(0);">No
                </label>
            </div>
        </div>

        <div class="col-md-3"></div>
    </div>

    <div id="photocapturedetailsui">
        <div class="row m-t-1">
            <div class="col-md-3 text-right"> Photo capture time interval (In seconds) :</div>

            <div class="col-md-6 input-group">
                <input type="text" id="txt1" name="txt1" class="form-control" 
                value="<?php if (isset($selphotocapturetime)) {
                    echo $selphotocapturetime;
                       } ?>" onchange="changephotocapturetime();" />
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

</div>

<!--  --------------------------------------------------------------- Start of adv Options -->

<div>




    <!-- Allow instant score view : -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Allow instant score view :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio5" name="flag05" class="form-check-input" 
                    <?php if ($selallowinstantscoreview == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeallowinstantscoreview(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio5" name="flag05" class="form-check-input" 
                    <?php if (!$selallowinstantscoreview == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeallowinstantscoreview(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    <!-- Enable review question to candidate : -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Enable review question to candidate :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio6" name="flag06" class="form-check-input" 
                    <?php if ($selenablereviewquestiontocandidate == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeenablereviewquestiontocandidate(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio6" name="flag06" class="form-check-input" 
                    <?php if (!$selenablereviewquestiontocandidate == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeenablereviewquestiontocandidate(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    <!-- Allow question navigation : -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Allow question navigation :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio7" name="flag07" class="form-check-input" 
                    <?php if ($selallowquestionnavigation == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeallowquestionnavigation(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio7" name="flag07" class="form-check-input" 
                    <?php if (!$selallowquestionnavigation == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeallowquestionnavigation(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    <!-- Randomise question : -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Randomise question :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio8y" name="flag08" class="form-check-input" 
                    <?php if ($selrandomisequestion == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changerandomisequestion(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio8n" name="flag08" class="form-check-input" 
                    <?php if (!$selrandomisequestion == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changerandomisequestion(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


    <!-- Shuffle question : -->
    <div class="row m-t-1" id="shufflequestionui">
        <div class="col-md-3 text-right"> Shuffle question :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio9y" name="flag09" class="form-check-input" 
                    <?php if ($selshufflequestion == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeshufflequestion(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio9n" name="flag09" class="form-check-input" 
                    <?php if (!$selshufflequestion == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeshufflequestion(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


    <!-- Conduct exam with sections : -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Conduct exam with sections :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio10y" name="flag10" class="form-check-input" 
                    <?php if ($selconductexamwithsections == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeconductexamwithsections(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio10n" name="flag10" class="form-check-input" 
                    <?php if (!$selconductexamwithsections == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeconductexamwithsections(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


    <!--  Set Subject or Topic as a Section : -->
    <div class="row m-t-1" id="flag11ui">
        <div class="col-md-3 text-right"> Set Subject or Topic as a Section :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio11s" name="flag11" class="form-check-input" 
                    <?php if ($selsetsubjectortopicassection == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changesetsubjectortopicassection(1);">Subject
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio11t" name="flag11" class="form-check-input" 
                    <?php if (!$selsetsubjectortopicassection == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changesetsubjectortopicassection(0);">Topic
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


    <!--  Negative marking : -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Negative marking :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio12" name="flag12" class="form-check-input" 
                    <?php if ($selnegativemarking == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changenegativemarking(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio12" name="flag12" class="form-check-input" 
                    <?php if (!$selnegativemarking == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changenegativemarking(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


    <!--  Enter negative marks :  -->
    <div class="row m-t-1" id="negativemarkui">
        <div class="col-md-3 text-right"> Enter negative marks :</div>

        <div class="col-md-6 input-group">
            <input type="text" id="txt4" name="txt4" class="form-control" 
            value="<?php if (isset($selenternegativemarks)) {
                echo $selenternegativemarks;
                   } ?>" onchange="changeenternegativemarks();" />
        </div>

        <div class="col-md-3"></div>
    </div>



    <!--  Time remaining reminder for candidate :  -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Time remaining reminder for candidate :</div>

        <div class="col-md-6 input-group">
            <input type="text" id="txt2" name="txt2" class="form-control"
            value="<?php if (isset($seltimereminderforcandidate)) {
                echo $seltimereminderforcandidate;
                   } ?>" onchange="changetimereminderforcandidate();" />
        </div>

        <div class="col-md-3"></div>
    </div>

    <!--  Max number of alerts for switching exam window :  -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Max number of alerts for switching exam window :</div>

        <div class="col-md-6 input-group">
            <input type="text" id="txt3" name="txt3" class="form-control"
            value="<?php if (isset($selmaxnumberofalertsforswitchingexamwindow)) {
                echo $selmaxnumberofalertsforswitchingexamwindow;
                   } ?>" onchange="changemaxnumberofalertsforswitchingexamwindow();" />
        </div>

        <div class="col-md-3"></div>
    </div>


    <!--  Allow Answer Option Shuffling :  -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Allow Answer Option Shuffling :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio13" name="flag13" class="form-check-input"
                    <?php if ($selallowansweroptionshuffling == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeallowansweroptionshuffling(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio13" name="flag13" class="form-check-input" 
                    <?php if (!$selallowansweroptionshuffling == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeallowansweroptionshuffling(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    <!--  Allow calculator in the exam :  -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Allow calculator in the exam :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio14" name="flag14" class="form-check-input" 
                    <?php if ($selallowcalculatorintheexam == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeallowcalculatorintheexam(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio14" name="flag14" class="form-check-input" 
                    <?php if (!$selallowcalculatorintheexam == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeallowcalculatorintheexam(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


    <!--  Show marks for question in exam :  -->
    <div class="row m-t-1">
        <div class="col-md-3 text-right"> Show marks for question in exam :</div>

        <div class="col-md-6 input-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" id="radio15" name="flag15" class="form-check-input" 
                    <?php if ($selshowmarksforquestioninexam == 1) {
                        echo 'checked="checked"';
                    } ?> value="Yes" onchange="changeshowmarksforquestioninexam(1);">Yes
                </label>
            </div>
            <div class="form-check m-l-1">
                <label class="form-check-label">
                    <input type="radio" id="radio15" name="flag15" class="form-check-input" 
                    <?php if (!$selshowmarksforquestioninexam == 1) {
                        echo 'checked="checked"';
                    } ?> value="No" onchange="changeshowmarksforquestioninexam(0);">No
                </label>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>


</div>

<!--  --------------------------------------------------------------- End of adv Options -->

<!-- submit button -->

<div class="row m-t-1">
    <div class="col-md-3 text-right"></div>

    <div class="col-md-6 input-group">

        <input type="submit" name="continue" class="m-element-button btn btn-primary" 
        value="Continue" style="margin:0px !important" onclick="saveform();" />
    </div>
    <div class="col-md-3"></div>
</div>
