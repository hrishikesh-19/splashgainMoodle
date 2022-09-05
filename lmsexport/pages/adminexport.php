<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/
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


require_once("../../../config.php");
require_once("../conf.php");
require_once("pgnfunc.php");


error_reporting(1);

global $USER, $DB;

require_admin();

error_reporting(1);





if (isset($_POST['lmsapikey']) && isset($_POST['continue'])) {

    if (isset($_POST['id'])) {

        $rslt = $DB->execute(
            'UPDATE {local_lmsexport} SET timezone1 ="' . $_POST['timezone'] . '" , examapikey ="' .
                $_POST['lmsapikey'] . '", apiurl ="' . $_POST['apiurl'] . '"  WHERE id = ?',
            array($_POST['id'])
        );



    } else {

        $sql = 'INSERT INTO {local_lmsexport} (apiurl,examapikey,timezone1)
        values("' . $_POST['apiurl'] . '","' . $_POST['lmsapikey'] . '","' . $_POST['timezone'] . '")';
        $rslt = $DB->execute($sql);


    }

    if ($rslt) {
        ?>
        <script>
            alert('Updated Successfully.');
        </script>
        <?php
    }

}

$records = $DB->get_record('local_lmsexport', array(), $fields = '*', $ignoremultiple = false);


$timezonelist = array(
    "India Standard Time",
    "Dateline Standard Time",
    "UTC-11",
    "Aleutian Standard Time",
    "Hawaiian Standard Time",
    "Marquesas Standard Time",
    "Alaskan Standard Time",
    "UTC-09",
    "Pacific Standard Time (Mexico)",
    "UTC-08",
    "Pacific Standard Time",
    "US Mountain Standard Time",
    "Mountain Standard Time (Mexico)",
    "Mountain Standard Time",
    "Central America Standard Time",
    "Central Standard Time",
    "Easter Island Standard Time",
    "Central Standard Time (Mexico)",
    "Canada Central Standard Time",
    "SA Pacific Standard Time",
    "Eastern Standard Time (Mexico)",
    "Eastern Standard Time",
    "Haiti Standard Time",
    "Cuba Standard Time",
    "US Eastern Standard Time",
    "Paraguay Standard Time",
    "Atlantic Standard Time",
    "Venezuela Standard Time",
    "Central Brazilian Standard Time",
    "SA Western Standard Time",
    "Pacific SA Standard Time",
    "Turks And Caicos Standard Time",
    "Newfoundland Standard Time",
    "Tocantins Standard Time",
    "E. South America Standard Time",
    "SA Eastern Standard Time",
    "Argentina Standard Time",
    "Greenland Standard Time",
    "Montevideo Standard Time",
    "Magallanes Standard Time",
    "Saint Pierre Standard Time",
    "Bahia Standard Time",
    "UTC-02",
    "Mid-Atlantic Standard Time",
    "Azores Standard Time",
    "Cape Verde Standard Time",
    "Morocco Standard Time",
    "GMT Standard Time",
    "Greenwich Standard Time",
    "W. Europe Standard Time",
    "Central Europe Standard Time",
    "Romance Standard Time",
    "Central European Standard Time",
    "W. Central Africa Standard Time",
    "Namibia Standard Time",
    "Jordan Standard Time",
    "GTB Standard Time",
    "Middle East Standard Time",
    "Egypt Standard Time",
    "E. Europe Standard Time",
    "Syria Standard Time",
    "West Bank Standard Time",
    "South Africa Standard Time",
    "FLE Standard Time",
    "Israel Standard Time",
    "Kaliningrad Standard Time",
    "Libya Standard Time",
    "Arabic Standard Time",
    "Turkey Standard Time",
    "Arab Standard Time",
    "Belarus Standard Time",
    "Russian Standard Time",
    "E. Africa Standard Time",
    "Iran Standard Time",
    "Arabian Standard Time",
    "Astrakhan Standard Time",
    "Azerbaijan Standard Time",
    "Russia Time Zone 3",
    "Mauritius Standard Time",
    "Saratov Standard Time",
    "Georgian Standard Time",
    "Caucasus Standard Time",
    "Afghanistan Standard Time",
    "West Asia Standard Time",
    "Ekaterinburg Standard Time",
    "Pakistan Standard Time",
    "India Standard Time",
    "Sri Lanka Standard Time",
    "Nepal Standard Time",
    "Central Asia Standard Time",
    "Bangladesh Standard Time",
    "Omsk Standard Time",
    "Myanmar Standard Time",
    "SE Asia Standard Time",
    "China Standard Time",
    "North Asia Standard Time",
    "N. Central Asia Standard Time",
    "Tomsk Standard Time",
    "North Asia East Standard Time",
    "Singapore Standard Time",
    "W. Australia Standard Time",
    "Taipei Standard Time",
    "Ulaanbaatar Standard Time",
    "North Korea Standard Time",
    "Aus Central W. Standard Time",
    "Transbaikal Standard Time",
    "Tokyo Standard Time",
    "Korea Standard Time",
    "Yakutsk Standard Time",
    "Cen. Australia Standard Time",
    "AUS Central Standard Time",
    "E. Australia Standard Time",
    "AUS Eastern Standard Time",
    "West Pacific Standard Time",
    "Tasmania Standard Time",
    "Vladivostok Standard Time",
    "Lord Howe Standard Time",
    "Bougainville Standard Time",
    "Russia Time Zone 10",
    "Magadan Standard Time",
    "Norfolk Standard Time",
    "Sakhalin Standard Time",
    "Central Pacific Standard Time",
    "Russia Time Zone 11",
    "New Zealand Standard Time",
    "UTC+12",
    "Fiji Standard Time",
    "Kamchatka Standard Time",
    "Chatham Islands Standard Time",
    "UTC+13",
    "Tonga Standard Time",
    "Samoa Standard Time",
    "Line Islands Standard Time",
    "AUS Western Standard Time",
    "Eastern European Time",
    "Sao Tome Standard Time",
    "Hong Kong Time"
);


echo $OUTPUT->header(); ?>
<div class="auth_form">

    <?php require('ekl_nav.php');  ?>
    
    <div>
        <div id="title_ui" class="col-md-12 pt-4">
            <h3>API and Timezone Settings</h3>
        </div>

        <div class="col-md-12 pt-4" style="text-align:left;width:100%">

            <a href="https://www.eklavvya.com/book-free-demo-moodle-proctoring/">
                <span class="btn btn-outline-primary">
                    Subscribe To Get Candidate API URL and Authentication Key
                </span>
            </a>
        </div>
        <br>

    </div>


    <form class="m-element-confirmation text-center auth_form" method="POST">


        <div class="container">

            <div class="" style="width:100%;text-align:left">
                <div class="col-md-3">
                    <?php if (isset($records->examapikey)) { ?>
                        <input type="hidden" name="id" value="<?php if (isset($records->id)) {
                                                                    echo $records->id;
                                                              } ?>" />
                    <?php } ?>
                </div>
            </div>
            <div class="row m-t-1" style="width:100%;text-align:left">
                <div class="col-md-3 text-right">
                    <label>Candidate API URL :</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="apiurl" placeholder="API Url" class="form-control" 
                    value="<?php if (isset($records->apiurl)) {
                                echo $records->apiurl;
                           } ?>" />
                </div>
                <div class="col-md-3"></div>
            </div>

            <div class="row m-t-1" style="width:100%;text-align:left">
                <div class="col-md-3 text-right">
                    <label>Authentication Key :</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="lmsapikey" placeholder="***********************" 
                    class="form-control" value="<?php if (isset($records->examapikey)) {
                                                    echo $records->examapikey;
                                                } ?>" />
                </div>
                <div class="col-md-3"></div>
            </div>
            <div class="row m-t-1" style="width:100%;text-align:left">
                <div class="col-md-3 text-right">
      <label>TimeZone :</label>
      </div>
      <div class=" col-md-6">

                    <select name="timezone" id="timezone" class="form-control">
            
                        <?php
                        foreach ($timezonelist as $timezone) {
                        ?>                         
                            <option 
                            <?php if ($records->timezone1 == $timezone) {
                            ?> selected 
                            <?php } ?> 
                            value="<?php echo $timezone; ?>"><?php echo $timezone; ?></option>
                            <?php
                        }
                        ?>

                    </select>

                </div>
                <div class="col-md-3"></div>
            </div>
            <div class="row rowStyle">
                <div class="col-md-3"></div>
                <div class="col-md-6" style="text-align:center;margin-top:2%!important">

                    <input type="submit" name="continue" class="m-element-button btn btn-primary" value="Update" />
                    <!--span class="m-element-button btn btn-primary Test" id="Test"  name="Test"> Alert </span-->
                </div>
                <div class="col-md-3"></div>
            </div>


            <br>
            <br>
            <br>



        </div>


    </form>
    <footer class="copyright-area">&nbsp;<?php /*?> &copy; 2019- Eklavvya Student<?php */ ?> </footer>
</div>

<?php
echo $OUTPUT->footer();
?>

<link href="<?php echo new moodle_url('/local/lmsexport/CSS/Custom.css'); ?>" rel="stylesheet" />

<script>

    $("#page-header").hide();

    
  

</script>
