<!doctype Html>
 <html>
      <head>
        <meta charset="utf-8">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->

        <link rel="stylesheet" href="Vendors/Vendor-Bootstrap3-Inuse/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="Vendors/Vendor-Bootstrap3-Inuse/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="Vendors/Vendor-Bootstrap3-Inuse/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="Vendors/Vendor-Bootstrap3-Inuse/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
            folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="Vendors/Vendor-Bootstrap3-Inuse/dist/css/skins/_all-skins.min.css">

         <!-- jQuery 3 -->
        <script src="Vendors/Vendor-Bootstrap3-Inuse/bower_components/jquery/dist/jquery.min.js"></script>
         <!-- Bootstrap 3.3.7 -->
         <script src="Vendors/Vendor-Bootstrap3-Inuse/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
         <!-- FastClick -->
         <script src="Vendors/Vendor-Bootstrap3-Inuse/bower_components/fastclick/lib/fastclick.js"></script>
         <!-- AdminLTE App -->
         <script src="Vendors/Vendor-Bootstrap3-Inuse/dist/js/adminlte.min.js"></script>
         <!-- AdminLTE for demo purposes -->
         <script src="Vendors/Vendor-Bootstrap3-Inuse/dist/js/demo.js"></script>
      </head>
      <body>
         <?php
            error_reporting(0);
               #When new Classes are added this are the array set data thats needs to be changed
               $ClassMap_NameData = array('ClassName1', 'ClassName2','ClassName3');
               $ClassMap_DayData_HTML =  array('Class1day[]', 'Class2day[]', 'Class3day[]');
               $ClassMap_DayData_PHP =  array('Class1day', 'Class2day', 'Class3day');

               #When new Courses/Classes are added this are the array set data thats needs to be changed >>>Note courses are to be in the same order when editing data set.
               $Reg_Courses = array('Biology','Math','Physics','Chemistry','Computer');
               $ClassMap_CourseData = array(array('Bio1','BioCount1','BioLec1',
                                                 'Mth1', 'MthCount1','MthLec1', 
                                                 'Phy1', 'PhyCount1','PhyLec1',
                                                 'Chm1', 'ChmCount1','ChmLec1',
                                                 'Com1', 'ComCount1','ComLec1'),

                                              array('Bio2','BioCount2','BioLec2',
                                                 'Mth2', 'MthCount2', 'MthLec2',
                                                 'Phy2', 'PhyCount2', 'PhyLec2',
                                                 'Chm2', 'ChmCount2','ChmLec2',
                                                 'Com2', 'ComCount2','ComLec2'),

                                            array('Bio3','BioCount3','BioLec3',
                                                  'Mth3', 'MthCount3', 'MthLec3',
                                                  'Phy3', 'PhyCount3', 'PhyLec3',
                                                  'Chm3', 'ChmCount3','ChmLec3',
                                                  'Com3', 'ComCount3','ComLec3')
                                          );
                                    
               $FullData = array();
                for($a = 0; $a < sizeof($ClassMap_NameData) ; $a++)
                  {
                     $ClassName = array($_POST[$ClassMap_NameData[$a]]);
                     $Course = array();
                     $Count = array();
                     $Lec = array();
                     $Periods = array();

                     $index = 0;
                     for($Cd = 0; $Cd < sizeof($ClassMap_CourseData[$a]); $Cd++)
                        if(isset($ClassMap_CourseData[$a][$Cd]))
                        {
                            $Class1_CourseData[$index] = $_POST[$ClassMap_CourseData[$a][$Cd]];
                            $index++;
                        }
                        
                     $index = 0;
                     for($i = 0; $i < 5 ; $i++)
                        if(isset($_POST[$ClassMap_DayData_PHP[$a]][$i]))
                        {
                           $Periods[$index] = $_POST[$ClassMap_DayData_PHP[$a]][$i];
                           $index++;
                        }

                     $index = 0;
                     $offset = 3;    
                     for($i = 0; $i < sizeof($Class1_CourseData);)
                        {
                          if(isset($Class1_CourseData[$i]))
                           {
                              $Course[$index] = $Class1_CourseData[$i];
                              $Count[$index] = $Class1_CourseData[$i+1];
                              $Lec[$index] = $Class1_CourseData[$i+2];
                              $index++;
                           }
                           $i+=$offset;
                        }
                  
                     $WkCourse = array();
                     $WkLec = array();
                     $index = 0;
                     for($i=0; $i < sizeof($Course); $i++)
                        for($j=0; $j < $Count[$i]; $j++)
                        {
                           $WkCourse[$index] = $Course[$i];
                           $WkLec[$index]= $Lec[$i];
                           $index++;           
                        }
                     //Shuffles through----------
                     $Wk_Course_Init = $WkCourse;
                    shuffle($WkCourse);
                    $WkLec_Shuffle = array();
                    for($i = 0; $i < sizeof($Wk_Course_Init); $i++)
                       for($j = 0; $j < sizeof($WkCourse); $j++)
                          if($Wk_Course_Init[$i] == $WkCourse[$j])
                             $WkLec_Shuffle[$j] = $WkLec[$i];
                    $WkLec = $WkLec_Shuffle;

                    $FullData[$a] =  array($WkCourse, $WkLec, $Periods, $ClassName);
                  }
           ?>

      <?php 
        if(isset($_POST['submit'])) 
          {
            unset($ClassMap_NameData);
            include 'generator.php';
          }
      ?>

    <div class="container-fluid bg-1 text-left">
       <form id="form" action= "init.php" method="post" class="form-horizontal">
           <?php for($i = 0; $i < sizeof($ClassMap_NameData) ; $i++) { ?>
             <div class="box-body" style ="border-bottom:3px solid skyblue;">
                <div class="form-group">
                    <div class = "row">
                      <label for="ClassName" class="col-sm-2">ClassName</label>
                      <input type="text" class="col-sm-3"  name="<?= $ClassMap_NameData[$i]?>"/>
                    </div>
                    <br/>

                     <div class="row">
                       <div class="col-md-12">
                         <div class="box box-solid">
                            <div class="box-body">
                               <div class="box-group" id="accordion">
                                   <div class="box-header with-border">
                                     <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#<?=$ClassMap_NameData[$i]?>">
                                           Click here to Register Courses
                                         </a>
                                      </h4>
                                   </div>
                                   <div id="<?=$ClassMap_NameData[$i]?>" class="panel-collapse collapse">
                                      <div class="box-body">
                                        <?php $Rc = 0; ?>
                                        <?php for($j = 0; $j < sizeof($ClassMap_CourseData[$i]); ) { ?>
                                           <div class = "row">
                                              <label for="ClassName" class="col-sm-1"><?php echo $Reg_Courses[$Rc]?></label>
                                              <input type="checkbox"  class="col-sm-1"  name= "<?= $ClassMap_CourseData[$i][$j++]?>" value="<?=$Reg_Courses[$Rc]?>">
                                              <label for="ClassName" class="col-sm-3">Number of periods weekly</label>
                                              <input type="text"  class="col-sm-1" name= "<?= $ClassMap_CourseData[$i][$j++]?>"/>
                                              <label for="ClassName" class="col-sm-2">Tutors Name</label>
                                              <input type="text" class="col-sm-3" name= "<?= $ClassMap_CourseData[$i][$j++]?>" />
                                           </div>
                                           <br />
                                         <?php $Rc++; }?>
                                       </div>
                                   </div>
                                </div>
                             </div>
                           </div>
                       </div>
                    </div>

                     <!--Days Weekly periods form fill-->
                    <?php for($d = 1; $d <= 5 ; $d++) { ?>
                      <div class="row">
                         <label for="ClassName" class="col-sm-3">Number of periods day-<?php echo $d;?></label>
                         <input type="text" class="col-sm-1" name="<?= $ClassMap_DayData_HTML[$i]?>" required/>
                      </div>
                       <br />
                    <?php }?>
                </div>
              </div>
           <?php } ?>
            <input type="submit" name= "submit" class="btn btn-info pull-right" value ="Generate"/>
       </form>

         <a href ="init.php" id ="edit" style="display:none"><button class="btn btn-info pull-right">Re-Edit</button></a>
    </div>

     <?php if(isset($_POST['submit'])) { ?>
          <script>
              function Button(){ 
                  document.getElementsByName("submit")[0].style.display = "none";
                  document.getElementById("edit").style.display = "block";
						}
              Button();
            </script>
        <?php }?> 
    <h6><i>Made BY Alex-Ibeh A.K.A=>harlexkhal copyright 2021</i></h6>
   </body>
</html>