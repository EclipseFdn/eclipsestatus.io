<?php
/*********************************************************************
 * Copyright (c) 2018-2020 Eclipse Foundation, Inc.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * SPDX-License-Identifier: EPL-2.0
 **********************************************************************/

require_once('eclipse.org-common/system/app.class.php');
$App = new App();

$Theme = $App->getThemeClass($App->getHTTPParameter('theme'));
$Theme->setMetatags('refresh', array( 'http-equiv' => 'Refresh',   'content' => '120',      ));
$Theme->setPageAuthor("Denis Roy");
$Theme->setPageKeywords("Eclipse Foundation, infrastructure, servers, down, flaky, status");
$Theme->setPageTitle('Eclipse Service Status');
$Theme->setDisplayToolbar(FALSE);

$textonly = $App->getHTTPParameter("textonly");
if($textonly == "1") {
  $textonly = true;
}
else {
  $textonly = false;
}

$jipp_list = array("hipp1", "hipp2", "hipp3", "hipp4", "hipp5", "hipp6", "hipp7", "hipp8", "hipp9", "hipp10", "hippcentos");


$html = "<h1>Eclipse Service Status  <a class='small' href='https://accounts.eclipse.org/committertools/infra-status'>(<u>Detailed view</u>)</a></h1>
           <p>We measure performance of various Eclipse.org services from our monitoring agent on <a href='https://azure.microsoft.com/'>Microsoft Azure/West Europe</a></p>";

# Outage broadcast. Uncomment and update
$html .= "<p><font color=red><b>2020-02-18 13:18 EST: Git/Gerrit performance is degraded because of the Feb 7 hardware failure. Please see <a href=http://eclip.se/560283>Bug 560283</a> for details.</b></font></p>";


$state_www = getState("www.eclipse.org");
$state_gerrit = getState("git.eclipse.org");
$state_wiki = getState("wiki.eclipse.org");
$state_ci = getState("ci.eclipse.org");
$state_download = getState("download.eclipse.org");
$state_bugs = getState("bugs.eclipse.org");
$state_repo = getState("repo.eclipse.org");
$state_jipp = getJIPPState($jipp_list);

if ($textonly) {
  echo "www:" . $state_www . "\n";
  echo "bugs:" . $state_bugs . "\n";
  exit;
}

$html .= '
<div class="row">
  <div class="col-sm-offset-3 col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="https://eclipse.org/">Website</a></h2>
      </div>
      ' . getStateIcon($state_www) . '
      <h4>' . $state_www . '</h4>
      <p>' . getStateText($state_www) . '</p>
   </div></div>

  <div class="col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="https://git.eclipse.org/r/">Git/Gerrit</a></h2>
      </div>
      ' . getStateIcon($state_gerrit) . '
      <h4>' . $state_gerrit . '</h4>
      <p>' . getStateText($state_gerrit) . '</p>
   </div></div>


  <div class="col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="https://wiki.eclipse.org/">Wiki</a></h2>
      </div>
      ' . getStateIcon($state_wiki) . '
      <h4>' . $state_wiki . '</h4>
      <p>' . getStateText($state_wiki) . '</p>
   </div></div>

   <div class="col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="https://bugs.eclipse.org/bugs/">Bugzilla</a></h2>
      </div>
      ' . getStateIcon($state_bugs) . '
      <h4>' . $state_bugs . '</h4>
      <p>' . getStateText($state_bugs) . '</p>
   </div></div>
</div>

<div class="row">
   <div class="col-sm-offset-3 col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="https://repo.eclipse.org/">Nexus</a></h2>
      </div>
      ' . getStateIcon($state_repo) . '
      <h4>' . $state_repo . '</h4>
      <p>' . getStateText($state_repo) . '</p>
   </div></div>

   <div class="col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="https://ci.eclipse.org/">JIPP/CI Site</a></h2>
      </div>
      ' . getStateIcon($state_ci) . '
      <h4>' . $state_ci . '</h4>
      <p>' . getStateText($state_ci) . '</p>
   </div></div>

   <div class="col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="https://ci.eclipse.org/">JIPP/CI Farm</a></h2>
      </div>
      ' . getStateIcon($state_jipp) . '
      <h4>' . $state_jipp . '</h4>
      <p>' . getStateText($state_jipp) . '</p>
   </div></div>


   <div class="col-sm-4 text-center"><div class="thumbnail">
      <div class="caption">
        <h2><a href="http://download.eclipse.org/">Download</a></h2>
      </div>
      ' . getStateIcon($state_download) . '
      <h4>' . $state_download . '</h4>
      <p>' . getStateText($state_download) . '</p>
   </div></div>


</div>';


$html .= '
<div class="row">
  <div class="col-sm-15">
  <h2>Jump to:</h2>
       <p><ul>
         <li><a href="#web">Web Page load times</a></li>
             <li><a href="#dl">Download performance</a></li>
             <li><a href="#jat">JIPP / build server access time</a></li>
             <li><a href="#jla">JIPP / build server load average</a></li>
             <li><a href="#cbi">CBI services access time</a></li>
           </ul></p>
   </div>
   <div class="col-sm-6">
                    <a class="twitter-timeline" data-height="300"  href="https://twitter.com/EclipseFdn" data-widget-id="296300311444856832">Tweets by @EclipseFdn</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
   </div>
</div>';


$html .= '<div class="row">
  <div class="col-md-24"><a name="web" /><h2>Web Page load times</h2>
           <p>Access times to our various web sites. Lower is better.</p></div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Daily (5 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_7_1.png" />
   </div>
  </div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Weekly (30 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_7_2.png" />
   </div>
  </div>
</div>';

$html .= '<div class="row">
  <div class="col-md-24"><a name="dl" /><h2>Download performance</h2>
           <p>Download performance measures the throughput while retrieving a 5 MiB file from download.eclipse.org. Higher is better. During peak times, performance decreases naturally since downloads have low priority access to bandwidth. As our download mirrors handle the bulk of our downloads, we encourage projects to <a href="https://wiki.eclipse.org/IT_Infrastructure_Doc#Use_mirror_sites.2Fsee_which_mirrors_are_mirroring_my_files.3F">use Mirrors</a>.</p></div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Daily (5 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_6_1.png" />
   </div>
  </div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Weekly (30 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_6_2.png" />
   </div>
  </div>
</div>';

$html .= '<div class="row">
  <div class="col-md-24"><a name="jat" /><h2>JIPP / build server access time</h2>
           <p>Average time to load the home page of a CI instance on a CI server. This is not an indication of actual build performance, but rather if the CI instance is reachable and responding. You can see which projects are running on a specific host by accessing <a href="https://ci.eclipse.org/">ci.eclipse.org</a>.</p></div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Daily (5 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_8_1.png" />
   </div>
  </div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Weekly (30 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_8_2.png" />
   </div>
  </div>
</div>';

$html .= '<div class="row">
  <div class="col-md-24"><a name="jla" /><h2>JIPP / build server load average</h2>
           <p>CI server load average, reported as typical unix load average.  You can see which projects are running on a specific host by accessing <a href="https://ci.eclipse.org/">ci.eclipse.org</a>.</p></div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Daily (5 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_9_1.png" />
   </div>
  </div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Weekly (30 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_9_2.png" />
   </div>
  </div>
</div>';

$html .= '<div class="row">
  <div class="col-md-24"><a name="cbi" /><h2>CBI service access time</h2>
           <p>Time taken to load CBI services. This is not an indication of actual build performance.</p></div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Daily (5 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_10_1.png" />
   </div>
  </div>
  <div class="col-sm-12 text-center">
    <div class="thumbnail">
       <div class="caption">
        <h3>Weekly (30 Minute Average)</h3>
      </div>
      <img src="exports/graphs/graph_10_2.png" />
   </div>
  </div>
</div>';


$Theme->setHtml($html);
$Theme->setLayout('default-fluid');
$Theme->generatePage();


function getState($website) {
  # rValue is one of "All Good", "Down", "Flaky", "Unknown"
  $rValue = "down";
  if($website != "") {
    $filename = "/localsite/out/monitor_availability_$website.out";
    $handle = fopen($filename, "r");
    if ($handle) {
      $count = 0;
      $firstline_good = FALSE;
      $presence_of_down = FALSE;

      while (($line = fgets($handle)) !== false) {
        # first line determines overall state
        if($count == 0) {
          if(preg_match('/^200$/', $line)) {
            $firstline_good = TRUE;
          }
        }
        else {
          if(!preg_match('/^200$/', $line)) {
            $presence_of_down = TRUE;
          }
        }
        $count++;
      }
      fclose($handle);

      # Determine state
      if($firstline_good && !$presence_of_down) {
        $rValue = "All Good";
      }
      elseif($firstline_good && $presence_of_down) {
        $rValue = "Flaky";
      }
      elseif(!$firstline_good && !$presence_of_down) {
        $rValue = "Flaky";
      }
      else {
        $rValue = "Down";
      }
    }
    else {
      $rValue = "Unknown";
    }
  }
  return $rValue;
}


function getJIPPState($host_list) {
  # rValue is one of "All Good", "Down", "Flaky", "Unknown"
  $rValue = "down";
  $filename = "/localsite/out/monitor_jipp_top.out";
  $handle = fopen($filename, "r");
  $list_on_file = array();
  if ($handle) {
    $count = 0;
    while (($line = fgets($handle)) !== false) {
      preg_match('/^hipp(.*):/', $line, $matches);
      $host = str_replace(":", "", $matches[0]);
      array_push($list_on_file, $host);
    }
  }
  $s = count(array_diff($list_on_file, $host_list));

  if($s == 0) {
    $rValue = "All Good";
  }
  elseif($s == count($host_list)) {
    $rValue = "Down";
  }
  else {
    $rValue = "Flaky";
  }
  return $rValue;
}


function getStateIcon($state) {
  switch($state) {
    case "All Good": return "<i class='fa fa-check-circle fa-5x' style='color:green' aria-hidden='true'></i>"; break;
    case "Flaky":    return "<i class='fa fa-warning fa-5x' style='color:gold' aria-hidden='true'></i>"; break;
    case "Down":     return "<i class='fa fa-times-circle fa-5x' style='color:darkred' aria-hidden='true'></i>"; break;
    case "Unknown":  return "<i class='fa fa-question-circle fa-5x' aria-hidden='true'></i>"; break;
    default:         return "<i class='fa fa-question-circle fa-5x' aria-hidden='true'></i>"; break;
  }
}
function getStateText($state) {
  switch($state) {
    case "All Good": return "All systems are operating normally."; break;
    case "Flaky":    return "There appears to be some instability with this service."; break;
    case "Down":     return "This service appears to be down. Someone is probably fixing it right away."; break;
    case "Unknown":  return "The status of this service is unknown."; break;
    default:         return "The status of this service is unknown."; break;
  }
}