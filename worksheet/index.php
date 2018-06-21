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
				function textInput($name,$value=Null,$readonly=NULL,$model=NULL,$type=NULL){
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
                
                function getQuoteNum($database){
                    $datas = $database->query("SELECT quote_num FROM quote_numbers ORDER BY id desc LIMIT 1")->fetchAll();
                    
                    foreach($datas as $data){
                        $newQuote = $data['quote_num'];
                    }
                    
                    return $newQuote;
                }                                    
			?>
		<div class="container d-print-table" ng-app="myApp" ng-controller="myCtrl">			
			<div class="row">
				<div class="col-12">
                    <h1>Russell Conveyor & Equipment<br><small>Quote Worksheet</small></h1>
					<hr />								
				</div>
			</div>
			
			<!-- Quote Header -->
			<div class="row">
				<div class="col-12">
					<h4>Quote Info</h4>
					<table class="table table-sm" id="quote info">
						<tr>	
							<td></td>
							<td><?php echo textInput("quote number",$value=Null,$readonly=NULL,$model=NULL); ?></td>														
						</tr>
						<tr>							
							<td><?php echo textInput("account name",$value=Null,$readonly=NULL,$model=NULL); ?></td>							
							<td><?php echo textInput("date",$value=Null,$readonly=NULL,1,'date'); ?></td>
						</tr>
						<tr>							
							<td><?php echo textInput("contact name",$value=Null,$readonly=NULL,$model=NULL); ?></td>							
							<td><?php echo textInput("end user",$value=Null,$readonly=NULL,$model=NULL); ?></td>
						</tr>
						<tr>							
							<td><?php echo textInput("location",$value=Null,$readonly=NULL,$model=NULL); ?></td>							
							<td><?php echo textInput("margin",.70,$readonly=NULL,1); ?></td>
						</tr>
						<tr>							
							<td><?php echo textInput("total footage",$value=Null,$readonly=NULL,$model=NULL); ?></td>							
							<td><?php echo textInput("total zones",$value=Null,$readonly=NULL,$model=NULL); ?></td>
						</tr>
						<tr>							
							<td><?php echo textInput("terms",$value=Null,$readonly=NULL,$model=NULL); ?></td>							
							<td><?php echo textInput("lead time",$value=Null,$readonly=NULL,$model=NULL); ?></td>
						</tr>
					</table>
				</div>
			</div>
			
			<!-- Line Items -->
			<div class="row">
				<div class="col-12">
					
					
					
					<table class="table table-sm" id="quote details">
						<thead>
							<tr class="bg-primary text-white">
								<th colspan="5">Line Items</th>
							</tr>
							<tr>
								<th>Item Number</th>
								<th>Description</th>
								<th>Qty</th>
								<th>Unit</th>
								<th>Sale Price</th>
							</tr>							
						</thead>
						<tbody>
							<tr ng-repeat="line in lineitems">
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" name="lineItemPrice{{$index}}"></td>
							</tr>
							<tr><td><a href="#" class="btn btn-primary btn-sm d-print-none" ng-model="new" ng-click="newprice()">New Line Item</a></td></tr>
							
							<!-- Supports Area -->
							
							<thead>
								<tr class="bg-primary text-white">
									<th colspan="5">Supports</th>
								</tr>
								<tr>
									<th>Item Number</th>
									<th>Description</th>
									<th>Qty</th>
									<th>Per Ft/Each</th>
									<th>Sale Price</th>
								</tr>							
							</thead>
							<tr ng-repeat="line in supportitems">
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" name="lineSupportPrice{{$index}}"></td>
							</tr>
							<tr><td><a href="#" class="btn btn-primary btn-sm d-print-none" ng-model="new" ng-click="newsupport()">New Support Line</a></td></tr>
							
							<!-- Power Supplies -->
							
							<thead>
								<tr class="bg-primary text-white">
									<th colspan="5">Power Supplies</th>
								</tr>
								<tr>
									<th>Item Number</th>
									<th>Description</th>
									<th>Qty</th>
									<th>Per Ft/Each</th>
									<th>Sale Price</th>
								</tr>							
							</thead>
							<tr ng-repeat="line in poweritems">
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" name="linePowerPrice{{$index}}"></td>
							</tr>
							<tr><td><a href="#" class="btn btn-primary btn-sm d-print-none" ng-model="new" ng-click="newpower()">New Power Supply Line</a></td></tr>
							
							<!-- Additional Costs-->
							
							<thead>
								<tr class="bg-primary text-white">
									<th colspan="5">Other Costs</th>
								</tr>
								<tr>
									<th>Item Number</th>
									<th>Description</th>
									<th>Qty</th>
									<th>Per Ft/Each</th>
									<th>Sale Price</th>
								</tr>							
							</thead>
							<tr>
								<td><label>Additional Cost</label></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" ng-model="addCost"></td>
							</tr>
							<tr>
								<td><label>Additional Controls Cost</label></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" ng-model="addControlCost"></td>
							</tr>
							<tr>
								<td><label>Mechanical Install</label></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" ng-model="mechInstall"></td>
							</tr>
							<tr>
								<td><label>Electrial Install</label></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" ng-model="elecInstall"></td>
							</tr>
							<tr>
								<td><label>Start-Up Cost</label></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" ng-model="startCost"></td>
							</tr>
							<tr>
								<td><label>Freight</label></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text"></td>
								<td><input class="form-control" type="text" ng-model="freight"></td>
							</tr>
						</tbody>
						<tfoot>
							
							<!-- Totals Area -->
							
							<tr class="bg-dark"><td colspan="5"></td></tr>
							<tr>
								<td><a id="update" class="btn btn-primary d-print-none" href="#" ng-model="update" ng-click="update()">Caluclate</a></td>
								<td colspan="2"></td>
								<td colspan="2"><?php echo textInput("total quote price", '{{total | currency}}', 1, 1); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div> <!-- Container -->
		
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
			app.controller('myCtrl', function($scope, $http){				
				$scope.lineitems = [{}];
				$scope.supportitems = [{}];
				$scope.poweritems = [{}];
				
				$scope.addCost = 0;
				$scope.addControlCost = 0;
				$scope.mechInstall = 0;
				$scope.elecInstall = 0;
				$scope.startCost = 0;
				$scope.freight = 0;
				
				
				$scope.newprice = function(){
					this.lineitems.push({});					
				};
				
				$scope.newsupport = function(){
					this.supportitems.push({});					
				};
				
				$scope.newpower = function(){
					this.poweritems.push({});					
				};
				
				$scope.update = function(){
					$scope.total = 0;
					$scope.lineTotal = 0;
					$scope.supportTotal = 0;
					$scope.powerTotal = 0;
					
					var i = 0;
					var j = 0;
					var k = 0;
					
					// Line Items
					for(; i < $scope.lineitems.length; i++){
						$scope.lineTotal += Number(document.getElementsByName('lineItemPrice' + i)[0].value);
					}
					
					// Supports
					for(; j < $scope.supportitems.length; j++){
						$scope.supportTotal += Number(document.getElementsByName('lineSupportPrice' + j)[0].value);
					}
					
					// Power Supplies
					for(; k < $scope.poweritems.length; k++){
						$scope.powerTotal += Number(document.getElementsByName('linePowerPrice' + k)[0].value);
					}
					
					
					
					$scope.total = $scope.lineTotal + $scope.supportTotal + $scope.powerTotal + Number($scope.addCost) + Number($scope.addControlCost) + Number($scope.mechInstall) + Number($scope.elecInstall) + Number($scope.startCost) + Number($scope.freight);
					
				};
			});
		</script>
    </body>
</html>
