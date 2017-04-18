                <html>
                <head>
                <title> Homework 5</title>
                    <script>document.getElementById('RepData').style.display='none';</script>
                    <style>
                        .formData {
                           border:1px solid black;
                            display: inline-block;
                            text-align: center;
                            padding: 7px 7px 1px 7px ;
                              margin-left: 40%;
                            width:100 px;
                            position: absolute;
                            z-index: 1;

                        }
                       
                        #btn{
                           
                            margin-right: -138px;
                        }
                       
                           .formData label {
                                display: inline-block;
                               width:138px;
                               
                        }
                       
                        h2{

                             margin-left: 40%;
                        }

                        #tableData{
                             width: 700px;
                            margin-left: 28%;
                            margin-right: auto;
                            position: absolute;
                            margin-top: 225px;
                            z-index: 2;
                        }
                        
                        #table{
                            width: inherit;
                            border-collapse: collapse;
                            text-align: center
                        }
                        
                        #RepData{
                            border:1px solid black;
                            padding: 20px 20px 20px 20px;
                            width:inherit;
                        }
                        #billData th{
                            text-align: left;
                        

                        }
                          #billData,#RepData tr{
                            text-align: left;

                        }
                        
                        #RepData img{
                         margin-left: auto;
                        margin-right: auto;
                        display: block;   
                        }
                       

                    </style>
                </head>
                <body>
                    <div id="tableData">

                     <?php 
                $keyword=$chamber=$state=$keyL='';
                      
    $statesname=array("alabama"=>"AL","alaska"=>"AK","arizona"=>"AZ","washington"=>"WA","montana"=>"MT","nebraska"=>"NE","nevada"=>"NV","new hampshire"=>"NH","new jersey"=>"NJ","new mexico"=>"NM","new york"=>"NY","north carolina"=>"NC","north dakota"=>"ND", "arkansas"=>"AR","california"=>"CA", "colorado"=>"CO","connecticut"=>"CT","delaware"=>"DE","district of columbia"=>"DC", "florida"=>"FL","ohio"=>"OH","georgia"=>"GA","oklahoma"=>"OK","hawaii"=>"HI","oregon"=>"OR","idaho"=>"ID","pennsylvania"=>"PA","illinois"=>"IL","rhode island"=>"RI","indiana"=>"IN","south carolina"=>"SC","iowa"=>"IA","south dakota"=>"SD","kansas"=>"KA","tennessee"=>"TN","kentucky"=>"KY", "texas"=>"TX","louisiana"=>"LA","utah"=>"UT","maine"=>"ME","vermont"=>"VT","maryland"=>"MD", "virginia"=>"VA","massachusetts"=>"MA","michigan"=>"MI","west virginia"=>"WV", "minnesota"=>"MN","wisconsin"=>"WI","mississippi"=>"MS","wyoming"=>"WY","missouri"=>"MO");


                        if($_SERVER["REQUEST_METHOD"]=="POST"){
                            
                             if(!empty($_POST["keyL"]))
                        {
                            $keyL=test_input($_POST["keyL"]);
                             
                        }

                        if(!empty($_POST["keyword"]))
                        {
                            $keyword=test_input($_POST["keyword"]);
                        }

                         if(!empty($_POST["chamber"]))
                        {
                            $chamber=test_input($_POST["chamber"]);
                        }
                        
                         if(!empty($_POST["state"]))
                        {
                            $state=test_input($_POST["state"]);
                       echo "<script type='text/javascript'> changeLabel();</script>" ;
                            
                             
                             if($state=="legislators"){
                                $url='';
                    $apikey="52a90f09068f49bc9cff552894e77413";
                                 if(isset($statesname[strtolower($keyword)])||array_key_exists(strtolower($keyword),$statesname)){
                                     $val=strtolower($keyword);
                                    $val=$statesname[$val];
                         $url="http://congress.api.sunlightfoundation.com/legislators?chamber=".$chamber."&state=".$val."&apikey=".$apikey;
                                    
                                 }
                                 else{
                                      if(preg_match('/\s/',$keyword)){

                           $url="http://congress.api.sunlightfoundation.com/legislators?chamber=".$chamber."&aliases=".urlencode(ucwords($keyword))."&apikey=".$apikey;
                         }
                         else{
                             $url="http://congress.api.sunlightfoundation.com/legislators?chamber=".$chamber."&query=".$keyword."&apikey=".$apikey;
                         }
                                 }
                                 legislator_api($url);

                             }
                              else if($state=="committees"){
                                committees_api($keyword,$chamber);
                             }
                             else if($state=="bills"){
                                bills_api($keyword,$chamber);
                             }
                              if($state=="amendments"){
                                amendments_api($keyword,$chamber);
                             }

                        }

                    }

                    ?>
                    <div id="demo">
                    </div>  
                    </div>
                   
                    <?php
                           function legislator_api($url){
                               $lastName=$firstName=$stateName=$repChamber='';
                      $result=@file_get_contents($url);
                         $data = json_decode($result,true);

                      if($data === false || $data['count'] == 0){
                          echo "<b> The API returned zero results for the request. </b>";
                      }
                      else{
                          echo "<table border='1' id='table'>
                                        <tr>
                                            <th>Name</th>
                                            <th>State</th>
                                            <th>Chamber</th>
                                            <th>Details</th>
                                            </tr>";
                        for($i=0; $i<count($data['results']); $i++){
                            
                            if (array_key_exists('last_name',$data['results'][$i])) {
                                 $lastName=$data['results'][$i]['last_name'];
                             }
                            if (array_key_exists('first_name',$data['results'][$i])) {
                                  $firstName=$data['results'][$i]['first_name'];
                             }
                             if (array_key_exists('state_name',$data['results'][$i])) {
                                  $stateName=$data['results'][$i]['state_name'];
                             }
                             if (array_key_exists('chamber',$data['results'][$i])) {
                                  $repChamber=$data['results'][$i]['chamber'];
                             }
                             
                        
                $jsonData = json_encode($data['results'][$i]);
                    ?>
                   <tr>
                        <td style="text-align:left;padding: 5px;"><?php echo $firstName." ".$lastName;?></td>
                            <td><?php echo $stateName;?></td>
                           <td><?php echo $repChamber;?></td>
<td><a href="javascript:void()" onClick='RepTable(<?php echo $jsonData; ?>)'>View Details</a></td>
                    </tr>
              <?php          
           
                        }        
              echo "</table>";
                      
                               
                     
                      }  }


                    function committees_api($keyword,$chamber){
                           $url=$comChamber=$comName=$committeeID='';
                    $apikey="52a90f09068f49bc9cff552894e77413";
                           $url="http://congress.api.sunlightfoundation.com/committees?committee_id=".strtoupper($keyword)."&chamber=".$chamber."&apikey=".$apikey;
                          $result=@file_get_contents($url);

                         $data = json_decode($result,true);
                          if($data === false || $data['count'] == 0){
                         echo "<b> The API returned zero results for the request. </b>";
                      }
                        else{
                            echo "<table border='2' id='table'>
                                        <tr>
                                            <th>Committee ID</th>
                                            <th>Committee Name</th>
                                            <th>Chamber</th>
                                            </tr>";

                        for($i=0; $i<count($data['results']); $i++){
                             if (array_key_exists('committee_id',$data['results'][$i])) {
                                 $committeeID=$data['results'][$i]['committee_id'];
                             }
                            if (array_key_exists('name',$data['results'][$i])) {
                                  $comName=$data['results'][$i]['name'];
                             }
                             if (array_key_exists('chamber',$data['results'][$i])) {
                                  $comChamber=$data['results'][$i]['chamber'];
                             }

                    ?>
                   <tr>
                        <td><?php echo $committeeID;?></td>
                            <td><?php echo $comName;?></td>
                           <td><?php echo $comChamber;?></td>
                    </tr>
              <?php  }        
              echo "</table>";
                        }

                       }

                    function bills_api($keyword,$chamber){
                           $url=$billID=$shortTitle=$billChamber='';
                    $apikey="52a90f09068f49bc9cff552894e77413";
                           $url="http://congress.api.sunlightfoundation.com/bills?bill_id=".strtolower($keyword)."&chamber=".$chamber."&apikey=".$apikey;
                        $result=@file_get_contents($url);

                         $data = json_decode($result,true);
                         if($data === false || $data['count'] == 0){
                         echo "<b>The API returned zero results for the request</b>";
                      }
                        else{
                             echo "<table border='2' id='table'>
                                        <tr>
                                            <th>Bill ID</th>
                                            <th>Short Title</th>
                                            <th>Chamber</th>
                                            <th>Details</th>
                                            </tr>";

                        for($i=0; $i<count($data['results']); $i++){
                             if (array_key_exists('bill_id',$data['results'][$i])) {
                                 $billID=$data['results'][$i]['bill_id'];
                             }
                            if (array_key_exists('short_title',$data['results'][$i])) {
                                 $shortTitle=$data['results'][$i]['short_title'];
                             }
                             if (array_key_exists('chamber',$data['results'][$i])) {
                                 $billChamber=$data['results'][$i]['chamber'];
                             }
    
                             $jsonData = test_input(json_encode($data['results'][$i],JSON_HEX_APOS|JSON_HEX_QUOT));
                    ?>
                   <tr>
                        <td><?php echo $billID;?></td>
                            <td><?php if(isset ($shortTitle)) {echo $shortTitle;} ?></td>
                           <td><?php echo $billChamber ;?></td>
                    <td><a href="javascript:void()" onClick='billTable(<?php echo $jsonData; ?>)'>View Details</a></td>
                 </tr>
              <?php          
     
                            
                        }        
              echo "</table>";
                      
                               
                     
                      }  }

                    function amendments_api($keyword,$chamber){
                           $url=$amendmentType=$introducedOn=$amendmentID=$amendChamber='';
                    $apikey="52a90f09068f49bc9cff552894e77413";
                           $url="http://congress.api.sunlightfoundation.com/amendments?amendment_id=".strtolower($keyword)."&chamber=".$chamber."&apikey=".$apikey;
                         $result=@file_get_contents($url);

                         $data = json_decode($result,true);
                          if($data === false || $data['count'] == 0){
                         echo "<b>The API returned zero results for the request. </b>";
                      }
                        else{

                             echo "<table border='2' id='table'>
                                        <tr>
                                            <th>Amendment ID</th>
                                            <th>Amendment Type</th>
                                            <th>Chamber</th>
                                            <th>Introduced on</th>
                                            </tr>";

                        for($i=0; $i<count($data['results']); $i++){
                            
                            if (array_key_exists('amendment_type',$data['results'][$i])) {
                                 $amendmentType=$data['results'][$i]['amendment_type'];
                             }
                            if (array_key_exists('introduced_on',$data['results'][$i])) {
                                  $introducedOn=$data['results'][$i]['introduced_on'];
                             }
                             if (array_key_exists('amendment_id',$data['results'][$i])) {
                                  $amendmentID=$data['results'][$i]['amendment_id'];
                             }
                             if (array_key_exists('chamber',$data['results'][$i])) {
                                  $amendChamber=$data['results'][$i]['chamber'];
                             }
                            
                           
                    ?>
                    
                   <tr>
                        <td><?php echo $amendmentID; ?></td>
                            <td><?php if(isset ($amendmentType)) {echo $amendmentType;}?></td>
                           <td><?php echo $amendChamber ?></td>
                       <td><?php if(isset ($introducedOn)){echo $introducedOn;} ?></td>
                    </tr>
              <?php  }        
              echo "</table>";
                        }

                        }


                  function test_input($data) {
                  $data = trim($data);
                  $data = stripslashes($data);
                  $data = htmlspecialchars($data);
                  return $data;
                }  

                       ?>    
              <h2>Congress Information Search</h2>

                <div class="formData">

                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" onsubmit="return validateForm()" id="myForm">

                        <label for="state">Congress Database</label> <select name="state" id="stateLabel" onchange="changeLabel()">
                        <option value="select">Select your option</option>
                        <option <?php if (isset($state) && $state=="legislators") echo "selected";?> value="legislators">Legislators</option>
                        <option <?php if (isset($state) && $state=="committees") echo "selected";?> value="committees">Committees</option>
                        <option <?php if (isset($state) && $state=="bills") echo "selected";?> value="bills">Bills</option>
                        <option <?php if (isset($state) && $state=="amendments") echo "selected";?> value="amendments">Amendments</option>
                        </select><br/>

                        <label for="chamber">Chamber</label>
                        <input type="radio" name="chamber" <?php if (isset($chamber) && $chamber=="senate") echo "checked";?> value="senate" checked="true">Senate
                    <input type="radio" name="chamber" <?php if(isset($chamber) && $chamber=="house") echo "checked";?> value="house">House<br/>

        <input type="hidden" value="<?php echo isset($_POST["keyL"])? $_POST["keyL"] : "Keyword*";?>" id="keyw" name="keyL">
        
        <label for="keyword" id="keywordLabel" ><?php echo isset($_POST["keyL"]) ? $_POST["keyL"] : "Keyword*";?>
                            </label> 
                    <input type="text" name="keyword" id="keywordValue"  value="<?php echo $keyword; ?>"><br/><br/>
                        <input type="submit" name="submit" value="Search">
                        <input type="button" name="reset" value="Clear" onClick="resetForm()" id="btn">
                    </form>

                        <a href="http://sunlightfoundation.com/" target="_blank">Powered by Sunlight Foundation</a>

                    
                    </div>
                      <script>

                          
                          function changeLabel(){
                              var combo= document.getElementById('stateLabel');
                          var value=combo.options[combo.selectedIndex].value;
                              if(value=="legislators"){
                            document.getElementById('keywordLabel').innerHTML="State/Representative*";
                            document.getElementById('keyw').value="State/Representative*";
                              }
                                else if(value=="committees"){
                                  document.getElementById('keywordLabel').innerHTML="Committee ID*";
                                    document.getElementById('keyw').value="Committee ID*";
                              }
                                  else if(value=="bills"){
                                  document.getElementById('keywordLabel').innerHTML="Bill ID*";
                                document.getElementById('keyw').value="Bill ID*";
                              }
                                   else if(value=="amendments"){
                                  document.getElementById('keywordLabel').innerHTML="Amendment ID*";
                                    document.getElementById('keyw').value="Amendment ID*";
                              }
                              else if(value=="select"){
                                  document.getElementById('keywordLabel').innerHTML="Keyword*";
                                  document.getElementById('keyw').value="Keyword*";
                              }

                          }
                     function validateForm(){
                        var congressCombo= document.getElementById('stateLabel');
                          var value=congressCombo.options[congressCombo.selectedIndex].value;
                         var flag=false;
                     var err='Please enter the following information: ';
                     if(value=="" || value=="select"){
                         err+="Congress Database ";
                         flag=true;

                     }
                   var keywordval=document.getElementById("keywordValue").value;
                         keywordval=keywordval.trim();
                     if(keywordval==""){
                         err+="keyword";
                         flag=true;

                     }

                          if(flag){
                              alert(err);
                              return false;
                          }
                         else{
                             return true;
                         }
                 }
                          function resetForm(){
                              document.getElementById("stateLabel").selectedIndex = 0;
                              document.getElementById("keywordValue").value = '';
                              var radioElem = document.getElementsByName("chamber");
                              radioElem[0].checked=true;
                               document.getElementById("tableData").innerHTML="";
                              changeLabel();
                       }
                          
                        
                             function RepTable(jsonData){
                                
         var table= "<div id='RepData'><table style='margin-left: auto;margin-right: auto;width: 450;padding: 15px;'><tbody>";
                if(jsonData.bioguide_id != null){
                    table+="<tr><img src='https://theunitedstates.io/images/congress/225x275/"+jsonData.bioguide_id +".jpg'></tr>";  
                               }
                 if(jsonData.title!=null || jsonData.first_name!= null || jsonData.last_name){
                table+="<tr><th>Full Name</th><td>"+jsonData.title+" "+jsonData.first_name+" "+jsonData.last_name;
                    }
                  table+=" </td></tr><tr><th>Term Ends on</th><td>";if(jsonData.term_end!=null){table+=jsonData.term_end;}
                 table+="</td></tr><tr><th>Website</th><td> ";if(jsonData.website!=null){
                table+="<a href='"+jsonData.website+"'target='_blank'>"+jsonData.website;}
                
                table+="</td></tr><tr><th>Office</th><td>";if(jsonData.office!=null){table+= jsonData.office;}
                table+= "</td></tr><tr><th>Facebook </th><td>"; if(jsonData.facebook_id!=null){ table+= "<a href='https://www.facebook.com/"+ jsonData.facebook_id+ "'target='_blank'>"+ jsonData.first_name+" "+jsonData.last_name;}
                table+="</a></td></tr><tr><th> Twitter </th><td>"; if(jsonData.twitter_id!=null){ table+= "<a href='https://twitter.com/"+ jsonData.twitter_id+ "'target='_blank'>"+ jsonData.first_name+" "+jsonData.last_name;}
                                 
                 table+= "</a></td></tr></tbody></table></div>";              
            
                            document.getElementById("table").innerHTML="";     
                           document.getElementById("demo").innerHTML=table; 
                             }
                          
                    function billTable(jsonData){
var title=introducedOn=version=lastaction=pdfURL=txt=sponsorTitle=sponsorFirstName=sponsorLastName='';          
                        if('short_title' in jsonData){
                            if(jsonData.short_title!=null){
                              title=jsonData.short_title;  
                            }
                            
                        }  
                        
                        if('introduced_on' in jsonData){
                            if(jsonData.introduced_on!=null){
                              introducedOn=jsonData.introduced_on;  
                            }
                            
                        } 
                    
                          if('last_version' in jsonData){
                              if('version_name'){
                                if(jsonData.last_version.version_name!=null){
                              version=jsonData.last_version.version_name;  
                            }  
                              } 
                        }
                         if('last_action_at' in jsonData){
                            if(jsonData.last_action_at!=null){
                              lastaction=jsonData.last_action_at;  
                            }
                            
                        } 
                        
                            if(jsonData.sponsor.title!=null){
                              sponsorTitle=jsonData.sponsor.title; 
                                
                            }
                            if(jsonData.sponsor.last_name!=null){
                               sponsorLastName=jsonData.sponsor.last_name; 
                            }
                            if(jsonData.sponsor.first_name!=null){
                              sponsorFirstName=jsonData.sponsor.first_name; 
                                
                            }
                        
                            if(jsonData.last_version.urls.pdf!=null){
                              pdfURL=jsonData.last_version.urls.pdf;  
                            }
                            
                
                        if(title==null || title ==''){
                            txt=jsonData.bill_id;
                        }
                        else{
                            txt=title;
                        }
var table= "<div id='RepData'><table style='margin-left: auto;margin-right: auto;width: 450;padding: 15px;'><tbody>";

                    table+="<tr><th>Bill ID</th><td>"+jsonData.bill_id;
                    table+=" </td></tr><tr><th>Bill Title</th><td>"+title ;
                    table+= "</td></tr><tr><th>Sponsor</th><td>";
                        if(sponsorTitle!='' || sponsorFirstName!= '' || sponsorLastName!=''){table+=sponsorTitle+" "+ sponsorFirstName+" "+sponsorLastName;}
                    table+= "</td></tr><tr><th>Introduced on </th><td>"+introducedOn;
                    table+="</td></tr><tr><th> Last action with date </th><td>";                   if(version!='' || lastaction!= ''){table+=version+", "+lastaction;}
                    table+="</td></tr><tr><th>Bill URL</th/><td> ";
                         if(pdfURL!=null && pdfURL!=''){
                             table+="<a href='"+pdfURL+"'target='_blank'>"+txt;   
                        }               
                    table+= "</a></td></tr></tbody></table></div>"; 
                    document.getElementById("table").innerHTML="";     
                    document.getElementById("demo").innerHTML=table;
                 
                             }


                     </script> 
                     </body>
                </html>