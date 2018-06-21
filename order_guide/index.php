<!doctype html>
<html class="no-js" lang="">
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    </head>
	<body>   
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
				
				// Text Inputs
				function textInput($name,$value=Null,$readonly=NULL,$model=NULL,$type=NULL,$label=NULL){
					$name = str_replace(" ","-",$name);
					if($readonly) {
						$readonly = "readonly='readonly'";
					}
					if(is_null($model)){
						$output =  '
							<div id="'.$name.'-group" class="form-group row">';
								
                                if($label == null){
                                        $output .= '<label for="'.$name.'" class="col-3 col-form-label">'.ucwords(str_replace('-'," ",$name)).'</label>';
                                }
                        
								$output .= '<div class="col-9">
									<input type="text" class="form-control" id="'.$name.'" ng-model="'.str_replace('-',"",$name).'" value="'.$value.'" ng-change="'.str_replace('-',"",$name).'Change()" '.$readonly.'/>
								</div>
							</div>
						';
					}
					else
					{
						($type != NULL) ? $type = $type : $type = 'text';
						
						$output =  '
							<div id="'.$name.'-group" class="form-group row">
								<label for="'.$name.'" class="col-3 col-form-label">'.ucwords(str_replace('-'," ",$name)).'</label>
								<div class="col-9">
									<input type="'. $type.'" class="form-control" id="'.$name.'" value="'.$value.'" '.$readonly.'/>
								</div>
							</div>
						';
					}
					return $output;
				}
				
				// Checkbox Inputs
				function checkboxInput($name,$label,$function) {
					/* $name = The Name of the Input
					 * $label = The Label that accompaines the Checkbox
					 * $function = The Angluarjs Function that fires when checkbox is checked
					 * Returns the $output
					 */
					$name = str_replace(" ","-",$name);
					$output = '
						<div class="form-check">
							<label class="form-check-label">
								<input name="'.$name.'" class="form-check-input" type="checkbox" ng-model="'.str_replace('-',"",$name).'" ng-click="'.$function.'"/>
								'.$label.'
							</label>
						</div>
					';
					return $output;
				}
				
				
				// Radio Inputs
				function radioInput ($name, $label, $function){
					/* $name = The Name of the Input
					 * $label = The Label that accompaines the Checkbox
					 * $function = The Angluarjs Function that fires when checkbox is checked
					 * Returns the $output
					 */
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
				
				// Selectbox Inputs
				function selectInput($name,$list,$label=Null){
					$name = str_replace(" ","-",$name);
					$output = '
						<div id="'.$name.'-group" class="form-group row">';
						
							if($label == Null){
								$output .= '<label for="'.$name.'" class="col-3 col-form-label">'.ucwords(str_replace('-'," ",$name)).'</label>';
							}
							
							$output .='<div class="col-9">
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
			?>
		<div class="container">		
			<div class="row">
				<div class="col-12">
					<h1>Russell Conveyor & Equipment<br><small>Order Guide</h1>
					<hr />								
				</div>
			</div>
		</div>
    <div ng-app="myApp" ng-controller="myCtrl">
		<!-- Line Item Table -->
		<div class="container-fluid">
			<div class="row d-print-none">
				<div class="col-12">
					<table class="table table-sm">
						<thead>
							<tr class="row">								
								<th class="col-1">BF</th>
								<th class="col-1">Power Roller</th>
								<th class="col-1">Idler Roller</th>
								<th class="col-1">Tapered Roller</th>
								<th class="col-1">Gravity Roller</th>
								<th class="col-2">Card Type</th>
								<th class="col-1">Card Qty</th>
								<th class="col-1">PhotoEye</th>
								<th class="col-1">Cabling</th>
								<th class="col-1">Reg O-Rings</th>
								<th class="col-1">Blue O-Rings</th>
							</tr>
						</thead>
						<tbody>
							<tr class="row" ng-repeat="line in lineitems">								
								<td class="col-1"><?php echo selectInput('bf',['22','28','30'],true); ?></td>
								<td class="col-1"><input class="form-control" type="text"></td>
								<td class="col-1"><input class="form-control" type="text"></td>
								<td class="col-1"><input class="form-control" type="text"></td>
								<td class="col-1"><input class="form-control" type="text"></td>                            
								<td class="col-2"><?php echo selectInput('cardType',['IQZonz','Eqube','Conveylinx','IQMap'],true); ?></td>
								<td class="col-1"><input class="form-control" type="text"></td>
								<td class="col-1"><input class="form-control" type="text"></td>
								<td class="col-1"><input class="form-control" type="text"></td>
								<td class="col-1"><input class="form-control" type="text"></td>
								<td class="col-1"><input class="form-control" type="text" name="lineItemPrice{{$index}}"></td>
							</tr>
							<tr><td><a href="#" class="btn btn-primary btn-sm d-print-none" ng-model="new" ng-click="newline()">New Line Item</a></td></tr>
							
						</tbody>
						<tfoot>
						
						</tfoot>
					</table>
                    <a class="btn btn-primary" href="#">Update</a>  
				</div>
			</div>			
		</div> <!-- Container -->
		
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center w-100">Project Order Guide</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <b>Customer Name:</b>
                </div>
                <div class="col-4">
                <p>{{}}</p>
                </div>
                <div class="col-2">
                    <b>Project Number:</b>
                </div>
                <div class="col-4">
                    <p>{{}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <b>Due Date:</b>
                </div>
                <div class="col-4">
                    <p>{{}}</p>
                </div>
                <div class="col-2">
                    <b>Location:</b>
                </div>
                <div class="col-4">
                    <p>{{}}</p>
                </div>
            </div>  
            <div class="row">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Qty</th>
                            <th>Inventory</th>
                            <th>Qty to Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Powered Rollers</b></td>
                        </tr>
                        <tr>
                            <td class="text-right">22</td>
                            <td><?php echo textInput('bf22qty',$value=Null,'readonly',$model=NULL,$type=NULL,true); ?></td>
                            <td><?php echo textInput('bf22inv',$value=Null,null,$model=NULL,$type=NULL,true); ?></td>
                            <td><?php echo textInput('bf22ord',$value=Null,'readonly',$model=NULL,$type=NULL,true); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                    
                    </tfoot>
                </table>  
            </div>
        </div>
    </div><!-- Angularjs App -->
            
        <script src="js/vendor/modernizr-3.5.0.min.js"></script>
        <!--<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script> -->
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
		<script>
		function reset(){
			var reset = confirm("Are you sure you want to Reset the Form");
			if(reset ==true){
				window.location.assign('http://server.ais.local/apps/rce/quote_app/');
			}
			else
			{
				// Do Nothing
			}
			
		}
		</script>
		<script>
			var app = angular.module('myApp', []);
			app.controller('myCtrl', function($scope){				
				$scope.lineitems = [{}];
                $scope.bf22 = [{}];
				
				$scope.newline = function(){
					this.lineitems.push({});					
				};
				
				$scope.update = function(){
					$scope.total = 0;
					$scope.lineTotal = 0;
					
					
					var i = 0;
					
					
					// Line Items
					for(; i < $scope.lineitems.length; i++){
						$scope.lineTotal += Number(document.getElementsByName('lineItemPrice' + i)[0].value);
					}
					
					
					
					
					
					$scope.total = $scope.lineTotal;
					
				};
			});
		</script>
    </body>
</html>
