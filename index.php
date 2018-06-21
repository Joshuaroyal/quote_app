<!doctype html>

<!--
    ** To Do List **
    
-->

<html class="no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <style>
        a:link {
            color: #f7f7f7;
        }

        body {
            background-image: url("background.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

    </style>
</head>

<body class="bg-dark">
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <!-- Add your site or application content here -->

    <?php
				require 'Medoo.php';
				use Medoo\Medoo;
		
				$database = new Medoo([
					'database_type' => 'mysql',
					'database_name'=>'rce',
					'server'=>'joshua.ais.local',
					'username'=>'root',
					'password'=>'Jrcrazy71'
				]);
		
				// Get Cost Information
				$datas = $database->select("vendor_price","*");
		
				$cost = array();
		
				// Create Associative Array
				foreach($datas as $data){
					$cost[$data['part']] = $data['cost'];
				}
		
				// Create Hidden Inputs 
				foreach($cost as $key => $value){
					echo '<input type="hidden" id="'.$key.'" value="'.$value.'" />';
				}
							
				/**
				 * Text Input Field
				 * @textInput
				 * @param  string $name            Name of Text Field
				 * @param  string [$value=Null]    A default value for the field
				 * @param  string [$readonly=NULL] Use 'readonly' to have the input field set to readonly 
				 * @param  string [$model=NULL]    If is not NUll the ng-model is omitted 
				 * @return object $output          The html for the input field
				 */
				function textInput($name,$value=Null,$readonly=NULL,$model=NULL){
					$name = str_replace(" ","-",$name);
					if($readonly) {
						$readonly = "readonly='readonly'";
					}
					if(is_null($model)){
						$output =  '
							<div id="'.$name.'-group" class="form-group row">
								<label for="'.$name.'" class="col-3 col-form-label">'.ucwords(str_replace('-'," ",$name)).'</label>
								<div class="col-9">
									<input type="text" class="form-control" id="'.$name.'" ng-model="'.str_replace('-',"",$name).'" value="'.$value.'" ng-change="'.str_replace('-',"",$name).'Change()" '.$readonly.'/>
								</div>
							</div>
						';
					}
					else
					{
						$output =  '
							<div id="'.$name.'-group" class="form-group row">
								<label for="'.$name.'" class="col-3 col-form-label">'.ucwords(str_replace('-'," ",$name)).'</label>
								<div class="col-9">
									<input type="text" class="form-control" id="'.$name.'" value="'.$value.'" '.$readonly.'/>
								</div>
							</div>
						';
					}
					return $output;
				}
				
				/**
				 * Checkbox form input field
				 * @param  string $name     The name of the input field
				 * @param  string $label    The text shown next to the checkbox field
				 * @param  string $function The Angluarjs function to run when the checkbox is clicked
				 * @return object $output   The html for the checkbox input field
				 */
				function checkboxInput($name,$label,$function) {					
					$name = str_replace(" ","-",$name);
					$output = '
						<div class="form-check">
							<label class="checkbox-inline">
								<input name="'.$name.'" class="form-check-input" type="checkbox" ng-model="'.str_replace('-',"",$name).'" ng-click="'.$function.'"/>
								'.$label.'
							</label>
						</div>
					';
					return $output;
				}
				
				
				/**
				 * Radio form input field
				 * @param  string $name     The name of the input field
				 * @param  string $label    The text shown next the radio field
				 * @param  string $function The Angluarjs function to run when the radio is clicked
				 * @return object $output   The html for the radio input field
				 */
				function radioInput ($name, $label, $function){					
					$name = str_replace(" ","-",$name);
					$label = str_replace(" ","-",$label);
					$output = '
						<div class="form-check">
							<label class="form-check-label">
								<input name="'.$name.'" class="form-check-input" type="radio" ng-model="'.str_replace('-',"",$name).'" ng-click="'.$function.'" value="'.$label.'"/>
								'.ucwords(str_replace('-'," ",$label)).'
							</label>
						</div>
					';
					return $output;
				}
				
				/**
				 * Selectbox Input Field
				 * @param  string $name     The name of the input field
				 * @param  array  $list     A array of items that will populate the selectbox dropdown list
				 * @return object $output   The html for the selectbox input field
				 */
				function selectInput($name,$list){
					$name = str_replace(" ","-",$name);
					$output = '
						<div id="'.$name.'-group" class="form-group row">
							<label for="'.$name.'" class="col-3 col-form-label">'.ucwords(str_replace('-'," ",$name)).'</label>
							<div class="col-9">
								<select name="'.$name.'" class="custom-select" ng-model="'.str_replace('-',"",$name).'" ng-change="'.str_replace('-',"",$name).'Change()">';
									
									foreach($list as $lis){
										$output .= '<option value="'.$lis.'">'.ucwords($lis).'</option>';	
									}																		
									
								$output .= '</select>
							</div>
						</div>
					';
					return $output;
				}
                
                /**
                 * Prints the Title
                 * @param  string $company Company Name
                 * @param  string $tag     Subject Tag
                 * @return string   html for the page title
                 */
                function title($company,$tag){
                   return '<h1>'.$company.'<br><small><em class="text-primary">'.$tag.'</em></small></h1>'; 
                }
            
                /**
                 * Create the Tab Menu
                 * @param  array $items List of Tabs that are in the menu
                 * @return string html of the Tab Menu
                 */
                function menuTabs($items){
                    $output = '<ul class="nav nav-tabs">';
                    $activeClass = 'active';
                    $i = 0;
                    foreach($items as $item){
                        $link = str_replace(' ','-', $item);
                            $output .= '<li class="nav-item">';
                                // If $i is less than 1 applies the active class to the anchor
                                if($i < 1){                                    
                                    $output .= '<a class="nav-link active" data-toggle="tab" href="#'.$link.'">'.ucwords($item).'</a>';
                                }
                                else
                                {
                                    $output .= '<a class="nav-link" data-toggle="tab" href="#'.$link.'">'.ucwords($item).'</a>';
                                }
                            $output .= '</li>';
                       $i ++;
                    }
                    
                    $output .= '</ul>';
                    
                    return $output;
                }
			?>
        <!-- Header -->
        <div class="container-fluid bg-light mb-3">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php echo title('Russell Conveyor & Equipment', 'Quote Calculator');?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Container -->
        <div class="container d-print-table" ng-app="myApp" ng-controller="myCtrl">
            <div id="tabs" class="row">
                <div class="col-12">
                    <?php echo menuTabs(['conveyor','curve','quick calculations']);?>
                </div>
            </div>
            <!-- End Tabs-->

            <div id="button row" class="row">
                <div class="col-12 my-3">
                    <div class="row justify-content-between">
                        <div class="col-2">
                            <a class="btn btn-primary btn-sm d-print-none" href="http://server.ais.local/apps/rce/quote_app/worksheet/" target="_blank">Worksheet</a>
                        </div>
                        <div class="col-2 text-right">
                            <a class="btn btn-primary btn-sm d-print-none" href="#" onClick="window.print()">Print</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end button row -->

            <div class="tab-content col-12 bg-secondary py-1 rounded">
                <div class="tab-pane active" id="conveyor">
                    <div id="Param Section">
                        <div class="row mx-1 my-3 bg-light py-3 rounded">
                            <div class="col-12 d-flex justify-content-between">
                                <h5>Parameters</h5>
                                <span>
                                        <small>Conveyor Style</small><br>                                   
                                        <select class="custom-select d-print-none" name="style" ng-model="style" ng-change="styleChange()">
                                            <option value="straight">Straight</option>
                                            <option value="curve">Curve</option>
                                            <option value="herringbone">Herringbone</option>
                                            <option value="divert">Divert</option>
                                        </select>
                                    </span>
                            </div>
                        </div>
                        <div class="row mx-1 my-3 bg-light py-3 rounded">
                            <div id="item info" class="col-12">
                                <h5>Quote Info</h5>
                                <!-- Margin -->
                                <?php echo textInput('margin'); ?>

                                <!-- Description -->
                                <?php echo textInput('Description'); ?>
                            </div>
                        </div>
                        <div class="row mx-1 my-3">
                            <!-- Conveyor and Product Sections-->
                            <div id="conveyor info" class="col-6 bg-light border-right py-3 rounded">
                                <h5>Conveyor Info</h5>

                                <!-- Footage -->
                                <?php echo textInput('footage'); ?>

                                <!-- Conveyor Type -->
                                <?php echo selectInput("conveyor type",['accumulation','transportation','trash-line','gravity']); ?>

                                <!-- Conveyor Type Options -->
                                <div class="form-group row">

                                    <!-- Incline -->
                                    <div class="col-3 offset-3">
                                        <?php echo checkboxInput("incline","Incline",'inclineChange()') ?>
                                    </div>

                                    <!-- Decline -->
                                    <div class="col-3">
                                        <?php echo checkboxInput("decline","Decline",'declineChange()') ?>
                                    </div>

                                    <!-- Angle -->
                                    <div class="col-12" ng-show="incline">
                                        <?php echo textInput("angle"); ?>
                                    </div>
                                </div>
                                <!-- Zone Length & Zone OverRide Option-->

                                <?php //echo textInput("zone length"); ?>
                                <?php echo selectInput("zone length",['18','24','30','36','40','60','120']); ?>
                                <!-- Zone OverRide -->
                                <div class="form-group row">
                                    <lable class="col-3"></lable>
                                    <div class="col-9">
                                        <?php echo checkboxInput("zone override","Override default 48in zone behavior","update()"); ?>
                                    </div>
                                </div>
                            </div>
                            <div id="product info" class="col-6 bg-light py-3 rounded">
                                <h5>Product Info</h5>
                                <!-- Min and Max Speed -->
                                <?php echo textInput("max speed"); ?>

                                <!-- Product Length -->
                                <?php echo textInput("max length"); ?>

                                <!-- Weight -->
                                <?php echo textInput("max weight"); ?>

                                <span class="form-text text-muted small">
								        Weight in KG: {{mass | number : 2 }}kg
                                    </span>
                                <!-- Material -->
                                <?php echo selectInput("material",['wood','steel','cardboard','plastic','rubber']); ?>

                                <small class="form-text text-muted d-flex justify-content-between">
								<span>Required Torque: {{torquelbf}}lbf</span><span>Speed Code: {{speedCode}}</span><span>Speed Range: {{speedRange}}</span>
							</small>

                                <!-- Cellophane -->
                                <div class="form-group row">
                                    <label class="col-3">Cellophane Wrap</label>

                                    <!-- Cellophane Yes -->
                                    <div class="col-4">
                                        <?php echo radioInput('cellophane','yes',$function=NULL); ?>
                                    </div>

                                    <!-- Cellophane No -->
                                    <div class="col-4">
                                        <?php echo radioInput('cellophane','no',$function=NULL); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-1 my-3 ">
                            <div id="roller info" class="col-6 bg-light border-right py-3 rounded">


                                <h5>Roller Info</h5>
                                <!-- Roller Options -->
                                <div class="form-group row">
                                    <label class="col-3">Roller Options</label>

                                    <!-- Standard -->
                                    <div class="col-4">
                                        <?php echo radioInput('roller spec','standard','rollerspecChange()'); ?>
                                    </div>

                                    <!-- Wash Down -->
                                    <div class="col-4">
                                        <?php echo radioInput('roller spec','wash down','rollerspecChange()'); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3"></label>

                                    <!-- Brake -->
                                    <div class="col-4">
                                        <?php echo radioInput('roller spec','brake','rollerspecChange()'); ?>
                                    </div>

                                    <!-- Freezer -->
                                    <div class="col-4">
                                        <?php echo radioInput('roller spec','freezer','rollerspecChange()'); ?>
                                    </div>
                                </div>

                                <!-- Roller Location -->
                                <div class="mt-1">
                                    <?php echo selectInput("roller position",['low','high']); ?>
                                </div>

                                <!-- Roller Centers -->
                                <div class="mt-1">
                                    <?php echo textInput('roller center'); ?>
                                </div>

                                <!-- Between Frame -->
                                <?php //echo textInput('bf'); ?>
                                <?php echo selectInput("bf",['15','22','28','30','34']); ?>

                                <!-- Power Roller Override -->
                                <?php echo textInput('power roller override'); ?>
                            </div>
                            <div id="options" class="col-6 bg-light py-3 rounded">
                                <h5>Options</h5>
                                <!-- ConveyLink Upgrade -->
                                <div id="options-group" class="form-group row">
                                    <div class="col-4">
                                        <?php echo checkboxInput("conveyLinx upgrade","ConveyLinx Upgrade","update()"); ?>
                                    </div>

                                    <!-- Include Belts Guard Rail-->
                                    <div class="col-4">
                                        <?php echo checkboxInput("belts","Include Belts","update()"); ?>
                                    </div>

                                    <!-- Include Guard Rail -->
                                    <div class="col-4">
                                        <?php echo checkboxInput("guard rail","Include Guard Rail","update()"); ?>
                                    </div>
                                </div>

                                <div id="options2-group" class="form-group row">
                                    <div class="col-4">
                                        <?php echo checkboxInput("remove photoeye","Remove Photo Eyes","update()"); ?>
                                    </div>
                                    <div class="col-4">
                                        <?php echo checkboxInput('channel covers','Channel Covers', "update()"); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-1 my-3 bg-light pt-3 rounded">
                            <div class="col-12">
                                <!-- Zone Calculation -->
                                <?php echo textInput("zones","{{zones | number : 0}}","readyonly"); ?>
                            </div>
                        </div>
                        <div class="row mx-1 my-3 pt-3 d-print-none">

                            <!-- Update Button -->
                            <a class="btn btn-primary mr-3" href="#" ng-model="updateButton" ng-click="update()">Update</a>

                            <!-- Reset -->
                            <a class="btn btn-default" href="#" onClick="reset()">Reset</a>
                        </div>
                    </div>
                    <!-- Param Section & col-6 -->
                </div>
                <!-- conveyor -->
                <div class="tab-pane" id="curve">
                    <div id="Param Section">
                        <div class="row mx-1 my-3 bg-light pt-3 rounded">
                            <div class="col-12">
                                <h4>45 Degree Curve</h4>
                                <h5>Parameters</h5>
                            </div>
                        </div>
                        <div class="row mx-1 my-3 bg-light pt-3 rounded">
                            <div class="col-12">
                                <!-- Margin -->
                                <?php echo textInput('margin'); ?>
                            </div>

                            <div class="col-12">
                                <!-- Description -->
                                <?php echo textInput('Description'); ?>
                            </div>

                            <div class="col-12">
                                <!-- Qty -->
                                <?php echo textInput('curve qty'); ?>
                            </div>

                            <div class="col-12">
                                <!-- Bf -->
                                <?php echo textInput('bf',Null,'readonly'); ?>
                            </div>

                            <div class="col-12">
                                <!-- Straight Roller OverRide -->
                                <?php echo checkboxInput("taper override","Straight Roller Curve","update()"); ?>

                                <!-- 90 Degree Curve -->
                                <div class="d-flex justify-content-between">
                                    <?php echo checkboxInput("ninty curve","90 Degree Curve","update()"); ?><span ng-show="nintycurve">({{curveqty / 2}}) <em>90 Degree Curves</em></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-1 my-3 pt-3 d-print-none">
                            <!-- Update Button -->
                            <a class=" d-print-none btn btn-primary mt-3" href="#" ng-model="updateButton" ng-click="update()">Update Curve Values</a>
                        </div>
                    </div>
                </div>
                <!-- curve -->
                <div id="quick-calculations" class="tab-pane">
                    <div class="row mx-1 my-3 bg-light pt-3 rounded">
                        <div class="col-12">
                            <h5>Quick Calculations</h5>
                        </div>
                    </div>
                    <div class="row mx-1 my-3 bg-light pt-3 rounded">
                        <div class="col-6">
                            <!-- Quick Footage Calculate -->
                            <?php echo textInput("quick footage"); ?>

                            <!-- Quick Zone Calculate -->
                            <?php echo textInput("quick zone"); ?>

                            <!-- Quick Power Rollers-->
                            <?php echo textInput("quick power roller"); ?>
                        </div>

                        <!-- Power Supplies -->
                        <div class="col-6">
                            <h6>Power Supply</h6>

                            <!-- Power Supply Qty -->
                            <?php echo textInput("ps qty",$vaule=Null,"readonly"); ?>

                            <!-- Power Supply Type-->
                            <?php echo selectInput("ps type",['480 VAC 5A','480 VAC 10A','480 VAC 20A','480 VAC 40A','480 VAC 80A']); ?>

                            <!-- Power Supply Price-->
                            <?php echo textInput("ps price","{{pspricePrice | currency}}","readonly", "1"); ?>
                        </div>
                    </div>
                    <div class="row mx-1 my-3 bg-light pt-3 rounded">
                        <div class="col-6">
                            <!-- Supports -->
                            <h6>Supports</h6>

                            <!-- Support Qty -->
                            <?php echo textInput("support qty"); ?>

                            <!-- Support TOR -->

                            <!-- Support Type -->
                            <?php echo selectInput("support type",['supports_20','supports_30','supports_40','poly_40']); ?>

                            <!-- Support Price -->
                            <?php echo textInput("support","{{supportPrice | currency}}","readonly", "1"); ?>

                        </div>
                        <div class="col-6">
                            <!-- Mechanical and Electrical Install Cost -->
                            <h6>Mechanical and Electrical Install Cost</h6>
                            <?php echo textInput("mechanical install",'{{mechanicalinstallPrice | currency}}','readonly','1'); ?>
                            <?php echo textInput("electrical install","{{electricalinstallPrice | currency}}",'readonly','1'); ?>
                        </div>
                    </div>
                    <!-- quick-calculations -->
                </div>
                <!-- tab-content -->
            </div>

            <div id="Calculation-Area" class="col-12 bg-secondary py-2 rounded mt-1">
                <table class="table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Component</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Cost</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody class="bg-light">
                        <tr id="frame">
                            <td><b>Frame</b></td>
                            <td id="qty">
                                <input type="text" class="form-control" name="frame-qty" value="{{frameQty | number : 0}}" readonly />
                            </td>
                            <td id="unitCost">
                                <input type="text" class="form-control" name="frame-unitCost" value="{{frameUnitCost | number : 2}}" readonly />
                            </td>
                            <td id="cost">
                                <input type="text" class="form-control" name="frame-cost" value="{{frameCost | number : 2}}" readonly />
                            </td>
                            <td id="price">
                                <input type="text" class="form-control" name="frame-price" value="{{framePrice | currency}}" readonly />
                            </td>
                        </tr>
                        <!-- frame -->

                        <tr id="powered-roller">
                            <td><b>Powered Roller</b><br><small><em>{{rolleroption}}</em></small></td>
                            <td id="qty">
                                <input type="text" class="form-control" name="p-roller-qty" value="{{powerRollerQty | number : 0}}" readonly />
                            </td>
                            <td id="unitCost">
                                <input type="text" class="form-control" name="p-roller-unitCost" value="{{powerRollerUnitCost | number : 2}}" readonly />
                            </td>
                            <td id="cost">
                                <input type="text" class="form-control" name="p-roller-cost" value="{{powerRollerCost | number : 2}}" readonly />
                            </td>
                            <td id="price">
                                <input type="text" class="form-control" name="p-roller-price" value="{{powerRollerPrice | currency}}" readonly />
                            </td>
                        </tr>
                        <!-- powered-roller -->

                        <tr id="idler-roller">
                            <td><b>Idler Roller</b><br><small><em>{{irollerType}}</em></small></td>
                            <td id="qty">
                                <input type="text" class="form-control" name="i-roller-qty" value="{{idlerRollerQty | number : 0}}" readonly />
                            </td>
                            <td id="unitCost">
                                <input type="text" class="form-control" name="i-roller-unitCost" value="{{idlerRollerUnitCost | number : 2}}" readonly />
                            </td>
                            <td id="cost">
                                <input type="text" class="form-control" name="i-roller-cost" value="{{idlerRollerCost | number : 2}}" readonly />
                            </td>
                            <td id="price">
                                <input type="text" class="form-control" name="i-roller-price" value="{{idlerRollerPrice | currency}}" readonly />
                            </td>
                        </tr>
                        <!-- idler-roller -->

                        <tr id="control Card">
                            <td><b>Control Card</b><br><small><em></em>{{cardType}}</small></td>
                            <td id="qty">
                                <input type="text" class="form-control" name="control-card-qty" value="{{controlCardQty | number : 0}}" readonly />
                            </td>
                            <td id="unitCost">
                                <input type="text" class="form-control" name="control-card-unitCost" value="{{controlCardUnitCost | number : 2}}" readonly />
                            </td>
                            <td id="cost">
                                <input type="text" class="form-control" name="control-card-cost" value="{{controlCardCost | number : 2}}" readonly />
                            </td>
                            <td id="price">
                                <input type="text" class="form-control" name="control-card-price" value="{{controlCardPrice | currency}}" readonly />
                            </td>
                        </tr>
                        <!-- control-card -->

                        <tr id="photo-eye">
                            <td><b>Photo Eye</b></td>
                            <td id="qty">
                                <input type="text" class="form-control" name="photo-eye-qty" value="{{photoeyeQty | number : 0}}" readonly />
                            </td>
                            <td id="unitCost">
                                <input type="text" class="form-control" name="photo-eye-unitCost" value="{{photoeyeUnitCost | number : 2}}" readonly />
                            </td>
                            <td id="cost">
                                <input type="text" class="form-control" name="photo-eye-cost" value="{{photoeyeCost | number : 2}}" readonly />
                            </td>
                            <td id="price">
                                <input type="text" class="form-control" name="photo-eye-price" value="{{photoeyePrice | currency}}" readonly />
                            </td>
                        </tr>
                        <!-- photo-eye -->

                        <tr id="cable">
                            <td><b>Cable</b></td>
                            <td id="qty">
                                <input type="text" class="form-control" name="cable-qty" value="{{cablingQty | number : 0}}" readonly />
                            </td>
                            <td id="unitCost">
                                <input type="text" class="form-control" name="cable-unitCost" value="{{cablingUnitCost | number : 2}}" readonly />
                            </td>
                            <td id="cost">
                                <input type="text" class="form-control" name="cable-cost" value="{{cablingCost | number : 2}}" readonly />
                            </td>
                            <td id="price">
                                <input type="text" class="form-control" name="cable-price" value="{{cablingPrice | currency}}" readonly />
                            </td>
                        </tr>
                        <!-- cable -->

                        <tr id="o-rings">
                            <td><b>O-Rings</b></td>
                            <td id="qty">
                                <input type="text" class="form-control" name="o-ring-qty" value="{{oringQty | number : 0}}" readonly />
                            </td>
                            <td id="unitCost">
                                <input type="text" class="form-control" name="o-ring-unitCost" value="{{oringUnitCost | number : 2}}" readonly />
                            </td>
                            <td id="cost">
                                <input type="text" class="form-control" name="o-ring-cost" value="{{oringCost | number : 2}}" readonly />
                            </td>
                            <td id="price">
                                <input type="text" class="form-control" name="o-ring-price" value="{{oringPrice | currency}}" readonly />
                            </td>
                        </tr>
                        <!-- o-rings -->

                        <tr id="options-area">
                            <td colspan="5">
                                <table id="options-table" class="table">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Options</th>
                                            <th>Qty</th>
                                            <th>Unit Cost</th>
                                            <th>Cost</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="belt-option" ng-show="belts">
                                            <td><b>Belt</b></td>
                                            <td id="qty">
                                                <input type="text" class="form-control" name="belt-qty" value="{{beltsQty | number : 0}}" readonly />
                                            </td>
                                            <td id="unitCost">

                                            </td>
                                            <td id="cost">
                                                <input type="text" class="form-control" name="belt-cost" value="{{beltsCost | number : 2}}" readonly />
                                            </td>
                                            <td id="price">
                                                <input type="text" class="form-control" name="belt-price" value="{{beltsPrice | currency}}" readonly />
                                            </td>
                                        </tr>
                                        <tr class="bg-info text-white" ng-show="belts">
                                            <td>Belt Information</td>
                                            <td colspan="4"><span>{{beltsRoller}} Rollers | {{beltsCenter}}" Centers | {{beltsLength}}" Long | {{beltsWidth}}" Wide</span></td>
                                        </tr>

                                        <!-- belt-option -->

                                        <tr id="guard-rail-option" ng-show="guardrail">
                                            <td><b>Guard Rail</b></td>
                                            <td id="qty">
                                                <input type="text" class="form-control" name="guard-rail-qty" value="{{guardsQty | number : 0}}" readonly />
                                            </td>
                                            <td id="unitCost">

                                            </td>
                                            <td id="cost">
                                                <input type="text" class="form-control" name="guard-rail-cost" value="{{guardsCost | number : 2}}" readonly />
                                            </td>
                                            <td id="price">
                                                <input type="text" class="form-control" name="guard-rail-price" value="{{guardsPrice | currency}}" readonly />
                                            </td>
                                        </tr>
                                        <!-- guard-rail-option -->

                                        <tr id="channel-option" ng-show="channelcovers">
                                            <td><b>Channel Covers</b></td>
                                            <td id="qty">
                                                <input type="text" class="form-control" name="guard-rail-qty" value="{{channelQty | number : 0}}" readonly />
                                            </td>
                                            <td id="unitCost">
                                                <input type="text" class="form-control" name="guard-rail-unit" value="{{channelUnit | number : 2}}" readonly />
                                            </td>
                                            <td id="cost">
                                                <input type="text" class="form-control" name="guard-rail-cost" value="{{channelCost | number : 2}}" readonly />
                                            </td>
                                            <td id="price">
                                                <input type="text" class="form-control" name="guard-rail-price" value="{{channelPrice | currency}}" readonly />
                                            </td>
                                        </tr>
                                        <!-- channel-option -->
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- options-area -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><b>Component Total</b></td>
                            <td id="cost">
                                <input type="text" class="form-control" name="component-total" value="{{componentTotalPrice | currency}}" readonly />
                            </td>
                        </tr>
                        <tr ng-show="belts">
                            <td colspan="4" class="text-right"><b>Belt Total</b></td>
                            <td id="cost">
                                <input type="text" class="form-control" name="belt-total" value="{{beltsTotal | currency}}" readonly />
                            </td>
                        </tr>
                        <tr ng-show="guardrail">
                            <td colspan="4" class="text-right"><b>Guard Rail Total</b></td>
                            <td id="cost">
                                <input type="text" class="form-control" name="guard-rail-total" value="{{guardsTotal | currency}}" readonly />
                            </td>
                        </tr>
                        <tr ng-show="channelcovers">
                            <td colspan="4" class="text-right"><b>Channel Covers Total</b></td>
                            <td id="cost">
                                <input type="text" class="form-control" name="channel-covers-total" value="{{channelTotal | currency}}" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><b>Total Item Sale Price</b></td>
                            <td id="cost">
                                <input type="text" class="form-control" name="item-total" value="{{quoteTotalPrice | currency}}" readonly />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><b>Total Sale Price / Ft</b><br><small><em>OR per Curve</em></small></td>
                            <td id="cost">
                                <input type="text" class="form-control" name="item-total" value="{{totalPriceFt | currency}}" readonly />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Calculation-Area -->


            <!-- BF Alert -->
            <div class="d-print-none position-absolute alert alert-info alert-dismissible fade show w-50 m-auto" style="top: 50%; left: 0; right: 0;" role="alert" ng-show="alertsBF">
                <p><strong>BF Alert</strong> Please Choose a BF Measurement and then Choose the Curve Tab followed by Pressing the Update Curve Button.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>

            <!-- ConveyLinx Alert -->
            <div class="d-print-none position-absolute alert alert-danger alert-dismissible fade show w-50 m-auto" style="top: 50%; left: 0; right: 0;" role="alert" ng-show="alertsCL">
                <p><strong>Conveylinx Alert</strong> A ConveyLinx Control Module is Required to Produce this amount of Torque.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>

            <!-- ConveyLink Alert - Brake and Freezer Roller Options -->
            <div class="d-print-none position-absolute alert alert-danger alert-dismissible fade show w-50 m-auto" style="top: 50%; left: 0; right: 0;" role="alert" ng-show="alertsCLBF">
                <p><strong>Conveylinx Alert</strong> A ConveyLinx Control Module is Required for Brake and Freezer Rated Rollers.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>

            <!-- Herringbone Alert -->
            <div class="d-print-none position-absolute alert alert-danger alert-dismissible fade show w-50 m-auto" style="top: 50%; left: 0; right: 0;" role="alert" ng-show="alertsHERR">
                <p><strong>Herringbone Alert</strong> Enter a 10' Section, then that price should double, and then $1000.00 should be added to total and that should be the price for each</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>

        </div>
        <!-- Container -->

        <script src="js/vendor/modernizr-3.5.0.min.js"></script>
        <!--<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> -->
        <script>
            window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')

        </script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script>
            function reset() {
                var reset = confirm("Are you sure you want to Reset the Form");
                if (reset == true) {
                    window.location.assign('http://server.ais.local/apps/rce/quote_app/');
                } else {
                    // Do Nothing
                }

            }

        </script>
        <script>
            var app = angular.module('myApp', []);
            app.controller('myCtrl', function($scope) {
                // Sets Margin
                $scope.margin = ".70";

                // Set Variables
                $scope.componentTotalPrice = "0";
                $scope.quoteTotalPrice = "0";
                $scope.curveqty = "1";
                $scope.rollerspec = 'standard';
                $scope.cellophane = 'yes';
                $scope.style = 'straight';
                var i = 0;

                /**
                 * Update Everything
                 */
                $scope.update = function() {

                    $scope.setFrame();
                    $scope.setCabling();
                    $scope.zonesChange();

                    if ($scope.zones != null && $scope.bf != null) {
                        $scope.setPRoller();
                    }

                    if ($scope.rollercenter != null && $scope.bf != null && $scope.powerRollerQty != null && $scope.footage != null) {
                        $scope.setIRoller();
                    }

                    if ($scope.conveyortype != null && $scope.powerRollerQty != null) {
                        $scope.conveyortypeChange();
                    }

                    $scope.setPhotoEye();
                    $scope.setOrings();
                    $scope.rollerspecChange();

                    if ($scope.channelconvers == true) {
                        $scope.setChannelCover();
                    }

                    if ($scope.belts == true) {
                        $scope.setBelts();
                    } else {
                        $scope.beltsPrice = 0;
                        $scope.beltsTotal = 0;
                        $scope.quoteTotal();
                    }

                    if ($scope.guardrail == true) {
                        $scope.setGuards();
                    } else {
                        $scope.guardsPrice = 0;
                        $scope.guardsTotal = 0;
                        $scope.quoteTotal();
                    }

                    return;

                }

                // Update Margin
                $scope.marginChange = function() {
                    $scope.update();
                };

                // Apply Margin
                $scope.applyMargin = function(x) {
                    console.log('applyMargin');
                    var y = Math.ceil(x / $scope.margin);

                    return y;
                }

                // Footage 
                $scope.footageChange = function() {
                    console.log('footageChange');

                    $scope.update();
                };

                /**
                 * Frame
                 */
                $scope.setFrame = function() {
                    console.log('setFrame');
                    //Set Frame Qty
                    //Adjust Num. of Frames based on wether its a curve or not
                    if (document.getElementsByClassName('active')[1].id == 'curve') {
                        // Curve
                        $scope.frameQty = Math.ceil($scope.footage / 10) * $scope.curveqty;

                    } else {

                        // Straight                        
                        $scope.frameQty = Math.ceil($scope.footage / 10);
                    }

                    // Herringbone Adjustment
                    if ($scope.style == 'herringbone') {
                        $scope.frameQty = $scope.frameQty * 2;
                    }

                    // Set Frame Unit Cost
                    //Curve and Straight
                    if (document.getElementsByClassName('active')[1].id == 'curve') {
                        // Curve
                        var frame = document.getElementById('curve-frame').value;

                    } else {

                        // Straight                                                                 
                        // Gravity
                        if ($scope.conveyortype == "gravity") {
                            var frame = document.getElementById('gravity-frame').value;
                        } else {
                            var frame = document.getElementById('frame').value;
                        }

                        // Partal Frame Less Than 10 foot    
                        // 80 is Cut Fee, 20 is extra crossbrace   
                        if ($scope.footage < 10) {
                            frame = Math.ceil(((frame / 10) * $scope.footage) + 80 + 20);
                        }
                    }
                    $scope.frameUnitCost = frame;

                    // Set Frame Cost
                    $scope.frameCost = $scope.frameUnitCost * $scope.frameQty;

                    // Set Frame Price
                    $scope.framePrice = $scope.applyMargin($scope.frameCost);

                    // Adjust Component Total
                    $scope.componentTotal();
                };

                /**
                 * Zones Change
                 */
                $scope.zonesChange = function() {
                    console.log('zoneChange');
                    if (document.getElementsByClassName('active')[1].id == 'curve') {
                        $scope.zones = Math.ceil(($scope.footage * 12) / $scope.zonelength) * $scope.curveqty;
                    } else {
                        $scope.zones = Math.ceil(($scope.footage * 12) / $scope.zonelength);
                    }
                };

                /**
                 * Zone Length
                 */
                $scope.zonelengthChange = function() {
                    console.log('zonelengthChange');

                    if ($scope.maxlength != null) {
                        $scope.maxlengthChange();
                    }

                    $scope.update();
                }

                /**
                 * BF (Between Frame)
                 */
                $scope.bfChange = function() {
                    console.log('bfChange');

                    $scope.update();
                };

                /**
                 * Roller Center
                 */
                $scope.rollercenterChange = function() {
                    console.log('rollercenterChange');
                    //$scope.rollercenter;

                    $scope.update();
                }

                /**
                 * Powered Rollers
                 */
                $scope.setPRoller = function() {
                    console.log('setPRoller');

                    // Set Power Roller Qty															
                    $scope.powerRollerQty = $scope.zones; // Should already be Rounded										

                    // Zone Length Override
                    if ($scope.zonelength > 48) {
                        $scope.powerRollerQty = $scope.powerRollerQty * 2;
                    }

                    // 48" Zone Override 					
                    if ($scope.zoneoverride == true) {
                        console.log('Zone Over ride');
                        $scope.powerRollerQty = $scope.zones;
                        console.log("zone override");
                    }

                    // Roller Override
                    if ($scope.powerrolleroverride != null && $scope.powerrolleroverride > 0) {
                        $scope.powerRollerQty = Number($scope.powerrolleroverride);
                    }

                    // Gravity Roller
                    if ($scope.conveyortype == "gravity") {
                        $scope.powerRollerQty = 0;
                    }

                    // Herringbone Adjustment
                    if ($scope.style == 'herringbone') {
                        $scope.powerRollerQty = $scope.powerRollerQty * 2;
                    }

                    // Set Power Roller Cost
                    // Test for Roller Options
                    // Need to Change if statement to a switch.  Only the Brake option works
                    if ($scope.rollerspec == "brake") {
                        var proller = $scope.getPower('brake');
                        $scope.rolleroption = 'Built-in Brake';

                    } else {

                        // Keep From Asking Everytime You Update Options
                        if (!$scope.powerRollerUnitCost) {

                            var proller = $scope.getPower('power');
                        }
                    }

                    if (proller) {
                        $scope.powerRollerUnitCost = proller;
                        $scope.powerRollerCost = proller * $scope.powerRollerQty
                    }

                    //Set Power Roller Price
                    $scope.powerRollerPrice = $scope.applyMargin($scope.powerRollerCost);

                    // Adjust Component Total
                    $scope.componentTotal();
                };

                $scope.getPower = function(type) {
                    try {
                        var proller = document.getElementById(type + $scope.bf).value;
                    } catch (err) {
                        alert('Could not find a Cost for the Choosen BF');
                        proller = prompt('Power Roller Cost');
                    } finally {
                        return proller;
                    }
                }

                /**
                 * Get Idler Cost based on type and returns it
                 * @param   {string} type       The type of Idler Roller 
                 * @returns {number} iroller    The cost of the Idler Roller
                 */
                $scope.getIdler = function(type) {
                    try {
                        var iroller = document.getElementById(type + $scope.bf).value;
                    } catch (err) {
                        alert('Could not find a Cost for the Choosen BF');
                        iroller = prompt('Roller Cost');
                    } finally {
                        return iroller;
                    }
                }

                /**
                 * Idler Roller
                 */
                $scope.setIRoller = function() {
                    console.log('setIRoller');

                    // Set Idler Roller Qty
                    if (document.getElementsByClassName('active')[1].id == 'curve' && !$scope.taperoverride) {
                        $scope.idlerRollerQty = Math.ceil((($scope.footage * 12) / $scope.rollercenter)) * $scope.curveqty;
                    } else {
                        if (document.getElementsByClassName('active')[1].id == 'curve') {
                            $scope.idlerRollerQty = (Math.ceil((($scope.footage * 12) / $scope.rollercenter)) * $scope.curveqty) - $scope.powerRollerQty;
                        } else {
                            $scope.idlerRollerQty = Math.ceil((($scope.footage * 12) / $scope.rollercenter) - $scope.powerRollerQty);
                        }
                    }

                    // Herringbone Adjustment
                    if ($scope.style == 'herringbone') {
                        $scope.idlerRollerQty = ($scope.idlerRollerQty * 2) + $scope.powerRollerQty;
                    }

                    // Set Idler Roller Cost
                    // If Curve with a Tapered Roller

                    if (document.getElementsByClassName('active')[1].id == 'curve' && !$scope.taperoverride) {

                        // Curve
                        if ($scope.conveyortype == "gravity") {

                            // Gravity Tapered Roller                     
                            var iroller = $scope.getIdler('gav');
                            $scope.irollerType = "Tapered Roller";

                        } else {

                            // MDR Tapered Roller
                            var iroller = $scope.getIdler('tap');
                            $scope.irollerType = "Tapered Roller";
                        }

                    } else {

                        // Straight Conveyor or Curve with Straight Rollers (The Price for the Idler Roller is the Same)
                        // Straight Gravity Conveyor
                        if ($scope.conveyortype == "gravity") {

                            // Gavity                                                          
                            var iroller = $scope.getIdler('gav');
                            $scope.irollerType = "Gravity Roller";

                        } else {

                            // Straight MDR Conveyor
                            var iroller = $scope.getIdler('idler');
                            $scope.irollerType = "Straight Roller";
                        }
                    }

                    if (iroller) {
                        $scope.idlerRollerUnitCost = iroller;
                        $scope.idlerRollerCost = Math.ceil(iroller * $scope.idlerRollerQty);
                    }

                    // Set Idler Roller Price
                    $scope.idlerRollerPrice = $scope.applyMargin($scope.idlerRollerCost);

                    // Adjust Component Total
                    $scope.componentTotal();
                };

                /**
                 * Roller Specs
                 * Determine if a ConveyLinx Module is needed based on Roller Spec
                 */
                $scope.rollerspecChange = function() {
                    console.log('rollerspecChange');
                    console.log($scope.rollerspec);
                    if ($scope.rollerspec == 'brake' || $scope.rollerspec == 'freezer') {
                        $scope.alert('conveyLinxBF');
                        $scope.conveyLinxupgrade = true;
                    }

                    if ($scope.rollerspec == 'standard' || $scope.rollerspec == 'wash-down') {
                        console.log('am in');
                        // Conveylink not Required
                        if ($scope.incline != true && $scope.torquelbf < 27.4 && $scope.conveyLinxupgrade == true) {
                            var r = confirm("Do you want to keep the Conveylinx Module");
                            if (r == false) {
                                $scope.conveyLinxupgrade = false;
                            }
                        }
                    }
                }

                /** - Control Card -
                 *  Gets the control card needed based on the conveyor type and user options
                 */

                $scope.conveyortypeChange = function() {
                    console.log('conveyortypeChange');

                    // Set Control Card Qty
                    // Get Conveyor Type Different Types Uses Different Cards Returns which card is needed
                    var card = $scope.getConveyorType();
                    $scope.cardType = card;

                    switch (card) {
                        case "iqzonz":
                            $scope.controlCardQty = Math.ceil($scope.powerRollerQty / 2);

                            // If 45 Degree Curve																							
                            if (document.getElementsByClassName('active')[1].id == 'curve' && $scope.nintycurve != true) {
                                console.log('45 degree curve');
                                $scope.controlCardQty = $scope.powerRollerQty;
                            }
                            // Herringbone Adjustment
                            if ($scope.style == 'herringbone') {
                                $scope.controlCardQty = $scope.controlCardQty + 1;
                            }
                            break;
                        case "conveyLinx":
                            $scope.controlCardQty = Math.ceil($scope.powerRollerQty / 2);

                            // If 45 Degree Curve																							
                            if (document.getElementsByClassName('active')[1].id == 'curve' && $scope.nintycurve != true) {
                                console.log('45 degree curve');
                                $scope.controlCardQty = $scope.powerRollerQty;
                            }
                            // Herringbone Adjustment
                            if ($scope.style == 'herringbone') {
                                $scope.controlCardQty = $scope.controlCardQty + 1;
                            }
                            break;
                        case "eqube":

                            $scope.controlCardQty = $scope.powerRollerQty;

                            break;
                        case "n/a":
                            $scope.controlCardQty = 0;
                            break;
                    }

                    // Set Control Card Cost
                    if (card == "n/a") {
                        var controlCard = 0;
                        $scope.controlCardUnitCost = 0;
                        $scope.controlCardCost = Math.ceil(controlCard * $scope.controlCardQty);
                    } else {
                        var controlCard = document.getElementById(card).value;
                        $scope.controlCardUnitCost = controlCard;
                        $scope.controlCardCost = Math.ceil(controlCard * $scope.controlCardQty);
                    }

                    // Set Control Card Price
                    $scope.controlCardPrice = $scope.applyMargin($scope.controlCardCost);

                    // Adjust Component Total
                    $scope.componentTotal();

                };

                /** - Conveyor Type -
                 * Gets the Conveyor type that was selected and returns the value to the function that called it
                 */

                $scope.getConveyorType = function() {
                    console.log('getConveyorType');

                    switch ($scope.conveyortype) {
                        case "accumulation":
                            // default is iqzonz
                            // ConveyLinx upgrade
                            if ($scope.conveyLinxupgrade == true) {
                                return "conveyLinx";
                            } else {
                                return "iqzonz";
                            }
                            break;
                        case "transportation":
                            // ConveyLinx upgrade
                            if ($scope.conveyLinxupgrade == true) {
                                return "conveyLinx";
                            } else {
                                return "eqube";
                            }
                            break;
                        case "trash-line":
                            return "eqube";
                            break;
                        case "gravity":
                            return "n/a";
                            break;
                    }
                };


                $scope.styleChange = function() {
                    console.log('styleChange');

                    switch ($scope.style) {
                        case "straight":
                            return;
                            break;
                        case "curve":
                            $scope.loadcurveChange();
                            break;
                        case "herringbone":
                            $scope.loadherringbone();
                            break;
                        case "divert":

                            break;
                    }
                }

                $scope.loadherringbone = function() {
                    console.log('loadherringbone');

                    $scope.footage = "10";
                    $scope.conveyortype = "accumulation";

                    $scope.alert("herringbone");
                }

                /** - Photo Eyes -
                 * Calculate Photo Eyes Qty, Cost, and Price and Sends to the ComponentTotal function
                 */

                $scope.setPhotoEye = function() {
                    console.log('setPhotoEye');

                    // Set Photo Eye Qty
                    // Test if conveyor type is accumulation and that the remove photoeye checkbox has not be checked
                    if ($scope.conveyortype == "accumulation" && $scope.removephotoeye != true) {
                        $scope.photoeyeQty = $scope.powerRollerQty;
                    } else {
                        // If conveyor type is any thing other than accumulation and/or the remove photoeye checkbox is checked
                        $scope.photoeyeQty = 0;
                    }

                    // Set Photo Eye Cost
                    var photoeye = document.getElementById('photoEye').value;
                    $scope.photoeyeUnitCost = photoeye;
                    $scope.photoeyeCost = photoeye * $scope.photoeyeQty;

                    // Set Photo Eye Price
                    $scope.photoeyePrice = $scope.applyMargin($scope.photoeyeCost);

                    // Adjust Component Total
                    $scope.componentTotal();
                };

                /** - Cabling -
                 * Calculate Cabling Qty, Cost, and Price and sends to the ComponentTotal function
                 */

                $scope.setCabling = function() {
                    console.log('setCabling');
                    // Set Cabling Qty
                    // Curve
                    if (document.getElementsByClassName('active')[1].id == 'curve') {
                        $scope.cablingQty = $scope.footage * $scope.curveqty;
                    } else {
                        // Straight
                        $scope.cablingQty = $scope.footage;
                    }

                    // Test if Conveyor is gravity and if so removes the cabling
                    if ($scope.conveyortype == "gravity") {
                        $scope.cablingQty = 0;
                    }

                    // Herringbone Adjustment
                    if ($scope.style == 'herringbone') {
                        $scope.cablingQty = $scope.cablingQty * 2;
                    }

                    // Set Cabling Cost
                    var cabling = document.getElementById('cabling').value;
                    $scope.cablingUnitCost = cabling;
                    $scope.cablingCost = Math.ceil(cabling * $scope.cablingQty);

                    // Set Cabling Price
                    $scope.cablingPrice = $scope.applyMargin($scope.cablingCost);

                    // Adjust Component Total
                    $scope.componentTotal();
                };

                /** - 0-Rings -
                 * Calculate O-Ring's Qty, Cost, and Price and Sends to the ComponentTotal function
                 */

                $scope.setOrings = function() {
                    console.log('setOrings');

                    // Set O-Ring Qty					
                    $scope.oringQty = $scope.powerRollerQty + $scope.idlerRollerQty;

                    // Test to see if conveyor type is gravity and if so sets qty to 0
                    if ($scope.conveyortype == "gravity") {
                        $scope.oringQty = 0;
                    }

                    // Set O-Ring Cost
                    // Test to see if conveyor is a curve or a straight
                    if (document.getElementsByClassName('active')[1].id == 'curve') {

                        // Curve
                        // Test for Gravity Curve
                        if ($scope.conveyortype == "gravity") {
                            $scope.oringUnitCost = 0;
                            $scope.oringCost = 0;
                        } else {
                            var oring = document.getElementById('o_rings_curve').value;
                            $scope.oringUnitCost = oring;
                            $scope.oringCost = Math.ceil((oring * $scope.footage) * $scope.curveqty);
                        }
                    } else {
                        // Straight
                        if ($scope.oringQty > 0) {
                            var oring = document.getElementById('o_rings').value;
                            $scope.oringUnitCost = oring;
                            $scope.oringCost = Math.ceil(oring * $scope.footage);
                        }
                    }

                    // Herringbone Adjustment
                    if ($scope.style == 'herringbone') {
                        $scope.oringCost = $scope.oringCost * 2;
                    }

                    // Set O-Ring Price
                    $scope.oringPrice = $scope.applyMargin($scope.oringCost);

                    // Adjust Component Total
                    $scope.componentTotal();
                };

                /** - Component Price Total -
                 * Gets all the component prices and sends to the quoteTotal function
                 */

                $scope.componentTotal = function() {
                    console.log('componentTotal');

                    // Frame
                    $scope.componentTotalPrice = $scope.framePrice;

                    // Power Roller
                    if ($scope.powerRollerPrice > 0) {
                        $scope.componentTotalPrice = $scope.componentTotalPrice + $scope.powerRollerPrice;
                    }

                    // Idler Roller
                    if ($scope.idlerRollerPrice > 0) {
                        $scope.componentTotalPrice = $scope.componentTotalPrice + $scope.idlerRollerPrice;
                    }

                    // Control Card
                    if ($scope.controlCardPrice > 0) {
                        $scope.componentTotalPrice = $scope.componentTotalPrice + $scope.controlCardPrice;
                    }

                    // Photo Eyes
                    if ($scope.photoeyePrice > 0) {
                        $scope.componentTotalPrice = $scope.componentTotalPrice + $scope.photoeyePrice;
                    }

                    // Cabling 
                    if ($scope.cablingPrice > 0) {
                        $scope.componentTotalPrice = $scope.componentTotalPrice + $scope.cablingPrice;
                    }

                    // O-Rings
                    if ($scope.oringPrice > 0) {
                        $scope.componentTotalPrice = $scope.componentTotalPrice + $scope.oringPrice;
                    }

                    // Herringbone Adjustment
                    if ($scope.style == 'herringbone') {
                        $scope.componentTotalPrice = $scope.componentTotalPrice + 1000;
                    }

                    // Adjust Total Quote Price
                    $scope.quoteTotal();
                };

                /** - Belts -
                 * Calculate Belts Qty, Cost, and Price and sends to the quoteTotal function
                 */

                $scope.setBelts = function() {
                    console.log('setBelts');

                    // Belt Rollers
                    $scope.beltsRoller = Math.ceil((($scope.footage * 12) / $scope.rollercenter) / (($scope.footage * 12) / $scope.zonelength));

                    // Belt Center
                    $scope.beltsCenter = Math.ceil($scope.beltsRoller * $scope.rollercenter) - 3;

                    // Belt Length
                    $scope.beltsLength = ($scope.beltsCenter * 2) + (1.9 * 3.14159);

                    // Belt Width
                    if ($scope.conveyortype == "trash-line") {
                        $scope.beltsWidth = $scope.bf - 2;
                    } else {
                        $scope.beltsWidth = $scope.bf - 6;
                    }

                    // Set Belt Qty
                    $scope.beltsQty = $scope.zones;

                    // Set Belt Cost
                    var belt = document.getElementById('belt').value;
                    $scope.beltsCost = (($scope.beltsLength / 12) * belt) * $scope.beltsQty;

                    // Set Belt Price
                    $scope.beltsPrice = $scope.applyMargin($scope.beltsCost);

                    // Set Belt Total Price For Calculation
                    $scope.beltsTotal = $scope.beltsPrice;

                    // Adjust Total Quote Price
                    $scope.quoteTotal();
                };

                $scope.setChannelCover = function() {
                    console.log('setChannelCover');

                    // Set Channel Qty
                    $scope.channelQty = $scope.frameQty;

                    // Set Channel Cost
                    var channel = document.getElementById('channel').value;
                    $scope.channelUnit = channel;
                    $scope.channelCost = channel * $scope.channelQty;

                    // Set Channel Price
                    $scope.channelPrice = $scope.applyMargin($scope.channelCost);

                    // Set Channel Total Price For Calculation
                    $scope.channelTotal = $scope.channelPrice;

                    // Adjust Total Quote Price
                    $scope.quoteTotal();
                }

                /** - Set Guard Rails -
                 * Calculate Guard Rail Qty, Cost, and Price and sends to the quoteTotal function
                 */

                $scope.setGuards = function() {
                    console.log('setGuards');

                    // Set Guard Qty
                    $scope.guardsQty = $scope.footage * 2;

                    // Set Guard Cost
                    // Get the cost of guard rails from the database
                    var guards = document.getElementById('guards').value;

                    $scope.guardsCost = Math.ceil(guards * $scope.footage);

                    // Set Guard Price
                    $scope.guardsPrice = $scope.applyMargin($scope.guardsCost);

                    // Set Guards Total Price For Calculation
                    $scope.guardsTotal = $scope.guardsPrice;

                    // Adjust Total Quote Price
                    $scope.quoteTotal();
                }

                /** - Max Speed -
                 * Sends the Requested the Max speed to the torque function
                 */

                $scope.maxspeedChange = function() {
                    console.log('maxspeedChange');
                    $scope.torque();
                }

                /** - Material -
                 * Sends the Material Class to the torque function
                 */

                $scope.materialChange = function() {
                    console.log('materialChange');
                    $scope.torque();
                };

                /** - Weight -
                 * Sends the Material's Weight to the torque function
                 */

                $scope.maxweightChange = function() {
                    console.log('maxweightChange');

                    $scope.torque();
                };

                /** - Max Length -
                 * Tests the material's length to the zone length and if needed swicthes the conveyLinxupgrade to true to enable Flex Zones
                 * or gives and error message
                 */

                $scope.maxlengthChange = function() {
                    console.log('maxlengthChange');
                    // Test Material Length to Zone size
                    if ($scope.zonelength != null && $scope.maxlength >= $scope.zonelength) {
                        if (confirm('Box Length is Equal to or Greater than Alloted Zone Length \n Upgrade to ConveyLinx to Enable Flex Zone')) {

                            // Change conveyLinxupgrade to true
                            $scope.conveyLinxupgrade = true;
                        } else {
                            // Display Error Message
                            alert('Configuration Error! Please Check Parameters');
                        }
                    }

                };

                /** - Incline Change -
                 * Checks weather the Converyor is an incline or not and if it is then sends the angle of incline to the torque function
                 * If not then clears the angle input re-runs the torque function and set the conveyLinxupgrade according.
                 */

                $scope.inclineChange = function() {
                    console.log('inclineChange');

                    // Test the incline checkbox and goes to the angleChange Function
                    if ($scope.incline == true) {
                        $scope.angleChange();
                    } else {
                        // If not an angle clear angle input, adjust torque and conveyLinxupgrade status
                        $scope.angle = null;
                        $scope.torque();
                        if ($scope.torquelbf < 27.4 && $scope.conveyLinxupgrade == true) {
                            $scope.conveyLinxupgrade = false;
                        }
                    }
                }

                /** - Incline Angle -
                 * Part of the inclineChange function sends the amount of incline angle to the torque function
                 */

                $scope.angleChange = function() {
                    console.log('angleChange');

                    // Test Incline and Angle Inputs 
                    if ($scope.incline == true && $scope.angle > 0) {
                        $scope.torque();
                    }
                }

                /** - Decline -
                 * Changes Roller Spec base on weather the conveyor is a decline or not
                 * Alerts User if a ConveyLinx Module is Needed
                 * Then sets $scope.rollerspec according
                 */

                $scope.declineChange = function() {
                    console.log('declineChange');

                    // Test weather Decline is Checked or Unchecked
                    if ($scope.decline == true) {

                        // Alert that a Conveylinx is needed for brake Spec
                        $scope.alert('conveyLinxBF');

                        // Change Roller Spec
                        $scope.rollerspec = 'brake';
                    } else {
                        // If not checked Make sure Roller Spec in set to Standard
                        $scope.rollerspec = 'standard';
                    }
                }

                /**
                 * Torque
                 */
                $scope.torque = function() {
                    console.log('torque');
                    // Get the Coefficient
                    switch ($scope.material) {
                        case "wood":
                            var coeff = .05;
                            break;
                        case "steel":
                            var coeff = .02;
                            break;
                        case "cardboard":
                            var coeff = .1;
                            break;
                        case "plastic":
                            var coeff = .04;
                            break;
                        case "rubber":
                            var coeff = .1;
                            break;
                    }

                    // Convert LBS to KG
                    $scope.mass = $scope.maxweight * .454;

                    // Get force in Newtons			
                    var newtons = $scope.mass * 9.8 * coeff;

                    // Check for Incline
                    if ($scope.incline == true) {
                        newtons = newtons + ($scope.mass * 9.8 * Math.sin($scope.angle * Math.PI / 180));
                    }


                    // Change Newtons to Torque					
                    $scope.torquelbf = Math.round((newtons * .2248) / .64);


                    $scope.speedcodeFind();
                };

                /**
                 * Speed Code
                 */
                $scope.speedcodeFind = function() {

                    if ($scope.torquelbf < 1.8) {
                        $scope.speedCode = "215";
                        $scope.speedRange = "96.2 ~ 969.8";

                        if ($scope.maxspeed > 969.8) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 1.8 && $scope.torquelbf < 2.2) {
                        $scope.speedCode = "175";
                        $scope.speedRange = "78.8 ~ 794.9";

                        if ($scope.maxspeed > 794.9) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 2.2 && $scope.torquelbf < 3.0) {
                        $scope.speedCode = "125";
                        $scope.speedRange = "57.7 ~ 581.9";

                        if ($scope.maxspeed > 581.9) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 3 && $scope.torquelbf < 4.1) {
                        $scope.speedCode = "95";
                        $scope.speedRange = "42.3 ~ 426.7";

                        if ($scope.maxspeed > 426.7) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 4.1 && $scope.torquelbf < 5.4) {
                        $scope.speedCode = "75";
                        $scope.speedRange = "32.1 ~ 323.3";

                        if ($scope.maxspeed > 323.3) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 5.4 && $scope.torquelbf < 6.7) {
                        $scope.speedCode = "60";
                        $scope.speedRange = "26.3 ~ 265.0";

                        if ($scope.maxspeed > 265.0) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 6.7 && $scope.torquelbf < 9.1) {
                        $scope.speedCode = "45";
                        $scope.speedRange = "19.2 ~ 194.0";

                        if ($scope.maxspeed > 194.0) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 9.1 && $scope.torquelbf < 11.1) {
                        $scope.speedCode = "35";
                        $scope.speedRange = "15.8 ~ 159.0";

                        if ($scope.maxspeed > 159.0) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 11.1 && $scope.torquelbf < 16.4) {
                        $scope.speedCode = "25";
                        $scope.speedRange = "10.7 ~ 107.8";

                        if ($scope.maxspeed > 107.8) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 16.4 && $scope.torquelbf < 20.1) {
                        $scope.speedCode = "20";
                        $scope.speedRange = "8.8 ~ 88.3";

                        if ($scope.maxspeed > 88.3) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 20.1 && $scope.torquelbf < 27.4) {
                        $scope.speedCode = "15";
                        $scope.speedRange = "6.4 ~ 64.7";

                        if ($scope.maxspeed > 64.7) {
                            alert("Request Max Speed is Outside of Speed Range");
                        }
                    } else if ($scope.torquelbf > 27.4 && $scope.torquelbf < 29.9) {
                        // Must go to Boost Mode
                        if ($scope.conveyLinxupgrade == true) {
                            $scope.speedCode = "25";
                            $scope.speedRange = "10.7 ~ 77.9";

                            if ($scope.maxspeed > 77.9) {
                                alert("Request Max Speed is Outside of Speed Range");
                            }
                        } else {
                            $scope.alert('conveyLinx');
                            $scope.conveyLinxupgrade = true;
                            $scope.speedCode = "25";
                            $scope.speedRange = "10.7 ~ 77.9";

                            if ($scope.maxspeed > 77.9) {
                                alert("Request Max Speed is Outside of Speed Range");
                            }
                        }
                    } else if ($scope.torquelbf > 29.9 && $scope.torquelbf < 36.5) {
                        // Must go to Boost Mode
                        if ($scope.conveyLinxupgrade == true) {
                            $scope.speedCode = "20";
                            $scope.speedRange = "8.8 ~ 63.9";

                            if ($scope.maxspeed > 63.9) {
                                alert("Request Max Speed is Outside of Speed Range");
                            }
                        } else {
                            $scope.alert('conveyLinx');
                            $scope.conveyLinxupgrade = true;
                            $scope.speedCode = "20";
                            $scope.speedRange = "8.8 ~ 63.9";

                            if ($scope.maxspeed > 63.9) {
                                alert("Request Max Speed is Outside of Speed Range");
                            }
                        }
                    } else if ($scope.torquelbf > 36.5 && $scope.torquelbf < 49.9) {
                        // Must go to Boost Mode
                        if ($scope.conveyLinxupgrade == true) {
                            $scope.speedCode = "15";
                            $scope.speedRange = "6.4 ~ 46.8";

                            if ($scope.maxspeed > 46.8) {
                                alert("Request Max Speed is Outside of Speed Range");
                            }
                        } else {
                            $scope.alert('conveyLinx');
                            $scope.conveyLinxupgrade = true;
                            $scope.speedCode = "15";
                            $scope.speedRange = "6.4 ~ 46.8";

                            if ($scope.maxspeed > 46.8) {
                                alert("Request Max Speed is Outside of Speed Range");
                            }
                        }
                    }

                };

                /**
                 * Power Roller OverRide
                 */
                $scope.powerrolleroverrideChange = function() {
                    $scope.update();
                }

                /**
                 * Curve Qty
                 */
                $scope.curveqtyChange = function() {
                    $scope.update();
                };

                /**
                 * Load Curve
                 */
                $scope.loadcurveChange = function() {
                    $scope.footage = "3.5";
                    $scope.conveyortype = "transportation";
                    $scope.zonelength = "120";
                    $scope.rollercenter = "4.5";
                    $scope.zoneoverride = true;

                    $scope.alert("bf");
                };

                /**
                 * Alerts
                 * @param {string} x The Alert that you want to be fired off
                 */
                $scope.alert = function(x) {
                    switch (x) {
                        case "bf":
                            $scope.alertsBF = true;
                            break;

                        case "conveyLinx":
                            $scope.alertsCL = true;
                            break;
                        case "conveyLinxBF":
                            $scope.alertsCLBF = true;
                            break;
                        case "herringbone":
                            $scope.alertsHERR = true;
                            break;
                    }
                }

                /**
                 * Total Quote Price
                 */
                $scope.quoteTotal = function() {
                    console.log('quoteTotal');
                    $scope.quoteTotalPrice = $scope.componentTotalPrice;

                    // Belts
                    if ($scope.beltsPrice > 0) {
                        $scope.quoteTotalPrice = $scope.quoteTotalPrice + $scope.beltsTotal;
                    }

                    // Guards
                    if ($scope.guardsPrice > 0) {
                        $scope.quoteTotalPrice = $scope.quoteTotalPrice + $scope.guardsTotal;
                    }

                    // Channel Covers
                    if ($scope.channelPrice > 0) {
                        $scope.quoteTotalPrice = $scope.quoteTotalPrice + $scope.channelTotal;
                    }

                    $scope.totalPriceFtChange();
                };

                /**
                 * Power Supplies
                 */
                $scope.pstypeChange = function() {
                    console.log('pstypeChange');

                    switch ($scope.pstype) {
                        case "480 VAC 5A":
                            // Power Supply Qty
                            $scope.psqty = Math.ceil($scope.quickpowerroller / 3);
                            // Power Suppy Cost
                            var ps = document.getElementById("5a").value;
                            $scope.pstypeCost = ps * $scope.psqty;
                            break;

                        case "480 VAC 10A":
                            // Power Supply Qty
                            $scope.psqty = Math.ceil($scope.quickpowerroller / 6);

                            // Power Suppy Cost
                            var ps = document.getElementById("10a").value;
                            $scope.pstypeCost = ps * $scope.psqty;
                            break;

                        case "480 VAC 20A":
                            // Power Supply Qty
                            $scope.psqty = Math.ceil($scope.quickpowerroller / 12);

                            // Power Suppy Cost
                            var ps = document.getElementById("20a").value;
                            $scope.pstypeCost = ps * $scope.psqty;
                            break;

                        case "480 VAC 40A":
                            // Power Supply Qty
                            $scope.psqty = Math.ceil($scope.quickpowerroller / 24);

                            // Power Suppy Cost
                            var ps = document.getElementById("40a").value;
                            $scope.pstypeCost = ps * $scope.psqty;
                            break;

                        case "480 VAC 80A":
                            // Power Supply Qty
                            $scope.psqty = Math.ceil($scope.quickpowerroller / 48);

                            // Power Suppy Cost
                            var ps = document.getElementById("80a").value;
                            $scope.pstypeCost = ps * $scope.psqty;
                            break;
                    }

                    // Power Supply Price
                    // Labor $250.00
                    // Price Calculation is Different for Power Supplies (Labor Must be added to price after Margin)
                    $scope.pspricePrice = Math.ceil(($scope.applyMargin(ps) + 250) * $scope.psqty);
                };

                /**
                 * Quick Footage Change
                 * Calculates the Install Price both Mechanical and Electrical
                 */
                $scope.quickfootageChange = function() {
                    $scope.mechanicalinstallPrice = Math.ceil($scope.quickfootage * 50);
                    $scope.electricalinstallPrice = Math.ceil($scope.quickfootage * 50);
                }

                /**
                 * Quick Power Roller Change
                 */
                $scope.quickpowerrollerChange = function() {
                    $scope.pstypeChange();
                };

                /**
                 * Supports
                 */
                $scope.supporttypeChange = function() {
                    // Supports Qty
                    //$scope.supportqty

                    // Support Cost
                    switch ($scope.supporttype) {
                        case "supports_20":
                            var support = document.getElementById("supports_20").value;
                            $scope.supportCost = support * $scope.supportqty;

                            break;

                        case "supports_30":
                            var support = document.getElementById("supports_30").value;
                            $scope.supportCost = support * $scope.supportqty;
                            break;

                        case "supports_40":
                            var support = document.getElementById("supports_40").value;
                            $scope.supportCost = support * $scope.supportqty;
                            break;

                        case "poly_40":
                            var support = document.getElementById("poly_40").value;
                            $scope.supportCost = support * $scope.supportqty;
                            break;
                    }

                    // Support Price
                    $scope.supportPrice = $scope.applyMargin($scope.supportCost);
                    console.log($scope.supportPrice);
                };

                // Support Qty Change
                $scope.supportqtyChange = function() {
                    $scope.supporttypeChange();
                };

                // Total Price / Ft.
                $scope.totalPriceFtChange = function() {
                    console.log('totalPriceFtChange');

                    $scope.totalPriceFt = $scope.quoteTotalPrice / $scope.footage;

                    // Curves
                    if (document.getElementsByClassName('active')[1].id == 'curve') {
                        // If 45
                        if ($scope.nintycurve != true) {
                            $scope.totalPriceFt = Math.ceil($scope.quoteTotalPrice / $scope.curveqty);
                        } else {
                            // If 90
                            $scope.totalPriceFt = Math.ceil($scope.quoteTotalPrice / ($scope.curveqty / 2));
                        }
                    }
                };

            });

        </script>
</body>

</html>
