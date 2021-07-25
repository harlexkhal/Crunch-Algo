<?php
/*FullData Array[Each_Class] = array(array([WkCourseSchedule]) | array([WkLecSchedule])
                                            array([Periods])  |  array([NameOfClass]))*/
                                            
$CourseCheck = array(array());/*[ClassIndex][PeriodIndex]*/
$LecturerCheck = array(array());/*[ClassIndex][PeriodIndex]*/
$TimeTableArray = array(array(array()));/*[ClassIndex][PeriodIndex][DailyIndex]*/

$Number_Of_Classes = sizeof($FullData);
$Num_Of_Days = 5;
$Max_Period = 0;
$Max = array();
for($i = 0; $i < $Number_Of_Classes; $i++)
    $Max[$i] = max($FullData[$i][2]);
$Max_Period = max($Max);
 
$WkCourseSize = array(); #for each class
$AllWkCourses = array();
for($i = 0; $i < $Number_Of_Classes; $i++)
{
    $WkCourseSize[$i] = sizeof($FullData[$i][0]);
    $AllWkCourses[$i] = $FullData[$i][0];
}

$Clash = false;
for($i = 0; $i < $Num_Of_Days; $i++)
{
    for($j = 0; $j < $Max_Period; $j++ )
    {    
        for($k = 0; $k < $Number_Of_Classes; $k++)
        {
            if(($j+1) > $FullData[$k][2][$i])
                continue; #Class has reached max number of period for the day...continue to the next class for insertion
               
            for($x = 0; $x < $WkCourseSize[$k]; $x++)
            {
                $Clash = false;
                $PickCourse = $FullData[$k][0][$x];
                $PickLec = $FullData[$k][1][$x];
               
                if(!isset($PickCourse))
                   continue;
                   //Do another check here to make sure a subject doesn't occur more than 2 times a day
                   if(!isset($CourseCheck[$k]))
                      $CourseCheck[$k] = array("");

                   $NumofTimes = count(array_keys($CourseCheck[$k], $PickCourse));
                   if($NumofTimes >= 2)
                    continue;
                   
                for($z = 0; $z < sizeof($CourseCheck[$k]); $z++)
                {
                    if($z != $j)
                    if($PickCourse == $CourseCheck[$k][$z])
                        { 
                           $temp = $CourseCheck[$k][$z+1];
                           $CourseCheck[$k][$z+1] = $PickCourse;
                           $PickCourse = $temp;

                           $temp = $LecturerCheck[$k][$z+1];
                           $LecturerCheck[$k][$z+1] = $PickLec;
                           $PickLec = $temp;

                           for($a = 0; $a < sizeof($CourseCheck); $a++)
                           {
                            if($a != $k) #just to make sure it doesn't find itself
                            if($PickCourse == $CourseCheck[$a][$j] || 
                               $PickLec == $LecturerCheck[$a][$j])
                            {
                                $temp = $CourseCheck[$k][$z+1];
                                $CourseCheck[$k][$z+1] = $PickCourse;
                                $PickCourse = $temp;

                                $temp = $LecturerCheck[$k][$z+1];
                                $LecturerCheck[$k][$z+1] = $PickLec;
                                $PickLec = $temp;
                                $Clash = true;
                               break;
                               }
                           }
                                        
                            if($Clash == true)
                               break;

                            else
                            {
                                for($a = 0; $a < sizeof($CourseCheck); $a++)
                                {
                                    if($a != $k) #just to make sure it doesn't find itself
                                      if($CourseCheck[$k][$z+1] == $CourseCheck[$a][$z+1] || 
                                         $LecturerCheck[$k][$z+1] == $LecturerCheck[$a][$z+1])
                                       {
                                          $temp = $CourseCheck[$k][$z+1];
                                          $CourseCheck[$k][$z+1] = $PickCourse;
                                          $PickCourse = $temp;
        
                                          $temp = $LecturerCheck[$k][$z+1];
                                          $LecturerCheck[$k][$z+1] = $PickLec;
                                          $PickLec = $temp;
                                          $Clash = true;
                                          break;
                                       }
                                }
                            }
                            
                            if(!$Clash)
                              $TimeTableArray[$k][$z+1][$i] = $CourseCheck[$k][$z+1];
                            else
                              break;
                       }
                       
                    if($Clash == true)
                       break;
                }

                if($Clash == true)
                {
                    unset($PickCourse);
                    unset($PickLec);
                    continue;
                }
                   
                for($b = 0 ; $b < sizeof($CourseCheck); $b++)
                    if($PickCourse == $CourseCheck[$b][$j] || $PickLec == $LecturerCheck[$b][$j])
                    {
                        $Clash = true;
                    break;
                    }
                    
                if($Clash == true)
                   continue;

                if(!isset($PickCourse))
                  continue;

                    $Count = 0;
                    for($o = 0; $o < $Num_Of_Days; $o++)
                    {
                        for($p = 0; $p < $Max_Period; $p++)
                        {
                            if($PickCourse == $TimeTableArray[$k][$p][$o])
                            $Count++;
                        }
                    }

                    if(!isset($AllWkCourses[$k]))
                    $AllWkCourses[$k] = array("");

                    $NumofTimes = count(array_keys($AllWkCourses[$k], $PickCourse));
                    if($Count >= $NumofTimes)
                    {
                       unset($PickCourse);
                       unset($PickLec);
                       unset($FullData[$k][0][$x]);
                       unset($FullData[$k][1][$x]);
                       continue;
                    }
                  
                $CourseCheck[$k][$j] = $PickCourse;
                $LecturerCheck[$k][$j] = $PickLec;
                $TimeTableArray[$k][$j][$i] = $PickCourse;
                unset($PickCourse);
                unset($PickLec);
                unset($FullData[$k][0][$x]);
                unset($FullData[$k][1][$x]);
                break;
            }
        }
    }
    //On a new day reset $CourseCheck[ClassIndex][PeriodIndex] && $LecturerCheck[ClassIndex][PeriodIndex]
            unset($CourseCheck);
            unset($LecturerCheck);
}

#Debugging view for complete timetable data
for($k = 0; $k < $Number_Of_Classes; $k++)
{
    echo "<table>";
    echo '<h3>'. $FullData[$k][3][0] .'<h3>';
    for($i = 0; $i < $Num_Of_Days; $i++)
    {   
        echo "<tr>";
        for($j = 0; $j < $Max_Period; $j++)
        {
            echo "<td style='background-color:#0088dd; padding:15px; border:2px solid #000000;'>" . $TimeTableArray[$k][$j][$i] . "</td>";
        }  
        echo "</tr>";
    }
    echo "</table>";
}

$Select = "";
for($i = 0; $i < $Num_Of_Days; $i++)
{
    for($j = 0; $j < $Max_Period; $j++)
    {
        for($k = 0; $k < $Number_Of_Classes; $k++)
        {
            $insert = false;
            if(($j+1) > $FullData[$k][2][$i])
                continue;

            if(!isset($TimeTableArray[$k][$j][$i]))
            {
                for($x = 0; $x < $WkCourseSize[$k]; $x++)
                {
                    if(isset($FullData[$k][0][$x]))
                    {
                        $Select =  $FullData[$k][0][$x];
                        $insert = true;
                    }

                    if($insert)
                    {
                        $TimeTableArray[$k][$j][$i] = $Select;
                        unset($Select);
                        unset($FullData[$k][0][$x]);
                        unset($FullData[$k][1][$x]);
                        break;
                    }
                }
            }
           
        }
    }
}
?>

<div class="row">
<div class="col-md-12">
  <div class="box box-solid">
     <div class="box-body">
        <div class="box-group" id="accordion">
            <div class="box-header with-border">
              <h4 class="box-title">
                 <a data-toggle="collapse" data-parent="#accordion" href="#autofix">
                   Click here to View Auto Fixes for courses that was not added to the table
                  </a>
               </h4>
            </div>
            <div id="autofix" class="panel-collapse collapse">
               <div class="box-body">
                 <?php
                    echo '<h1 style="color:green">AUTOMATIC COURSE FIX</h1>';
                    #Debugging view for complete timetable data
                    for($k = 0; $k < $Number_Of_Classes; $k++)
                    {
                      echo "<table>";
                      echo '<h3>'. $FullData[$k][3][0];
                      for($i = 0; $i < $Num_Of_Days; $i++)
                       {   
                         echo "<tr>";
                         for($j = 0; $j < $Max_Period; $j++)
                         {
                           echo "<td style='background-color:#0088dd; padding:15px; border:2px solid #000000;'>" . $TimeTableArray[$k][$j][$i] . "</td>";
                         }  
                         echo "</tr>";
                        }
                      echo "</table>";
                      echo '<br/><br/>';
                    }
                  ?>
                </div>
            </div>
         </div>
      </div>
    </div>
</div>
</div>

<?php
#---------------Free Cache Memory Off Post Input Data------------------
unset($TimeTableArray);
for($a = 0; $a < sizeof($ClassMap_NameData) ; $a++)
    {
        unset($_POST[$ClassMap_NameData[$a]]);

        for($b = 0; $b < sizeof($ClassMap_CourseData[$a]); $b++)
            unset($_POST[$ClassMap_CourseData[$a][$b]]);

        for($i = 0; $i < 5 ; $i++)
            unset($_POST[$ClassMap_DayData_PHP[$a]][$i]);

            unset($FullData);
    }
?>