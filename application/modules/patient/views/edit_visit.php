<script type="text/javascript">
$(window).load(function(){
	$('#visit_date').datetimepicker({		
		timepicker:false,
		format: '<?=$def_dateformate; ?>',
		scrollInput:false, 
		scrollMonth:false,
		scrollTime:false,
	});
	$('#visit_time').datetimepicker({		
		datepicker:false,
		step:<?=$time_interval*60;?>,
		format: '<?=$def_timeformate; ?>',
		formatTime:'<?=$def_timeformate; ?>',
		<?php if($clinic_start_time != '00:00' && $clinic_end_time !='24:00'){?>
		minTime:'<?=date($def_timeformate,strtotime($clinic_start_time));?>',
		maxTime:'<?=date($def_timeformate,strtotime($clinic_end_time));?>',
		<?php } ?>
		scrollInput:false, 
		scrollMonth:false,
		scrollTime:false,
	});
	$('.datetimepicker').datetimepicker({		
		timepicker:false,
		format: '<?=$def_dateformate; ?>',
		scrollInput:false, 
		scrollMonth:false,
		scrollTime:false,
	});
	
		var medicine_array = [<?php
					$i=0;
					foreach ($medicines as $medicine){
						if ($i>0) {echo ",";}
						echo '{value:"' . $medicine['medicine_name'] . '",id:"' . $medicine['medicine_id'] . '"}';
						$i++;
					}
				?>];
		
		$( "#add_medicine" ).click(function() {
			
			var medicine_count = parseInt( $( "#medicine_count" ).val());
			medicine_count = medicine_count + 1;
			$( "#medicine_count" ).val(medicine_count);
			
			var medicine = "<div><div class='col-md-2'><label for='medicine' style='display:block;text-align:left;'>Medicine</label><input type='text' name='medicine_name[]' id='medicine_name"+medicine_count+"' class='form-control'/><input type='hidden' name='medicine_id[]' id='medicine_id"+medicine_count+"' class='form-control'/></div>";
			medicine += "<div class='col-md-6'><label for='frequency' style='display:block;text-align:left;'>Frequency</label><div class='col-md-1'>M</div><div class='col-md-3'><input type='text' name='freq_morning[]' id='freq_morning' class='form-control'/></div><div class='col-md-1'>A</div><div class='col-md-3'><input type='text' name='freq_afternoon[]' id='freq_afternoon' class='form-control'/></div><div class='col-md-1'>N</div><div class='col-md-3'><input type='text' name='freq_evening[]' id='freq_evening' class='form-control'/></div></div>";
			medicine += "<div class='col-md-1'><label for='days' style='display:block;text-align:left;'>Days</label><input type='text' name='days[]' id='days' class='form-control'/></div>";
			medicine += "<div class='col-md-2'><label for='prescription_notes' style='display:block;text-align:left;'>Instructions</label><input type='text' name='prescription_notes[]' id='prescription_notes' class='form-control'/></div>";
			medicine += "<div class='col-md-1'><label></label><a href='#' id='delete_medicine"+medicine_count+"' class='btn btn-danger btn-sm square-btn-adjust'>Delete</a></div></div>";
			$( "#prescription_list" ).append(medicine);
			
			$("#delete_medicine"+medicine_count).click(function() {			
				$(this).parent().parent().remove();
			});
			$("#medicine_name"+medicine_count).autocomplete({
				source: medicine_array,
				minLength: 1,//search after one characters
				select: function(event,ui){
					//do something
					$("#medicine_id"+medicine_count).val(ui.item ? ui.item.id : '');

				},
				change: function(event, ui) {
					 if (ui.item == null) {
						$("#medicine_name"+medicine_count).val('');
						}
				},
			});
		});
		$('#reference_by').on('change', function (e) {
		var optionSelected = $("option:selected", this);
		var reference_add_option  = optionSelected.attr('reference_add_option');
		var placeholder  = optionSelected.attr('reference_placeholder');
		if(reference_add_option == 1){
			$('#reference_details').parent().show();
			$("#reference_details").attr("placeholder", placeholder);
		}else{
			$('#reference_details').parent().hide();
		}
	});	
});
$(document).ready(function(){
	var optionSelected = $("option:selected", this);
	var reference_add_option  = optionSelected.attr('reference_add_option');
	var placeholder  = optionSelected.attr('reference_placeholder');
	
	if(reference_add_option == 1){
		$('#reference_details').parent().show();
		$("#reference_details").attr("placeholder", placeholder);
	}else{
		$('#reference_details').parent().hide();
	}
	
	var departments = '<?php echo $doctor['department_id'];?>';
	departments = departments.split(",");
	$( ".section" ).each(function( index ) {
		  //console.log( index + ": " + $( this ).text() );
		  var section_departments = $(this).data('department');
		  section_departments = section_departments.toString();
		  section_departments = section_departments.split(",");
		  var flag = false;
			$.each(section_departments, function( index, section_department_id ) {
				if(departments.includes(section_department_id)){
					flag = true;
				}
			});
		  if(flag){
			  $( this ).show();
		  }else{
			  $( this ).hide();
		  }
	});
	
	
	$('#visit_doctor').on('change', function (e) {
		var departments = $(this).find(':selected').data('department');
		departments = departments.toString();
		departments = departments.split(",");
		
		$( ".section" ).each(function( index ) {
		  //console.log( index + ": " + $( this ).text() );
		  var section_departments = $(this).data('department');
		  section_departments = section_departments.toString();
		  section_departments = section_departments.split(",");
		  var flag = false;
			$.each(section_departments, function( index, section_department_id ) {
				if(departments.includes(section_department_id)){
					flag = true;
				}
			});
		  if(flag){
			  $( this ).show();
		  }else{
			  $( this ).hide();
		  }
		});
	});
});
</script>
<?php 

if(isset($visit)){
		
		$patient_reference_by = $visit['reference_by'];
		$patient_reference_by_detail = $visit['reference_by_detail'];
		$reference_by = "";
		$reference_details = "";
		if(count($references) > 0){
			$reference_by = $references[0];
		}
		if(count($references) > 1){
			$reference_details = $references[1];
		}
	}else{
		$reference_by = set_value('reference_by',"");
		//echo $reference_by;
		$reference_details = "";
	}

?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
					<?php echo $this->lang->line("edit")." ".$this->lang->line("visit");?>
			</div>
			<span class="err"><?php echo validation_errors(); ?></span>
			<div class="panel-body">
			<?php echo form_open('patient/edit_visit/'. $visit_id . "/" . $patient_id) ?>
			<div class="form-group">
				<label for="visit_doctor"><?php echo $this->lang->line("doctor");?></label>
				<select name="visit_doctor" id="visit_doctor" class="form-control">
					<option></option>
					<?php foreach ($doctors as $doc) { ?>
					<option data-department="<?=$doc['department_id'] ?>" value="<?php echo $doc['doctor_id'] ?>" <?php if($doc['doctor_id'] == $doctor['doctor_id']){echo "selected";} ?>><?= $doc['name']; ?></option>
					<?php }	?>
				</select>
				<?php echo form_error('visit_doctor','<div class="alert alert-danger">','</div>'); ?>
			</div>
			<div class="form-group">
				<label for="visit_date"><?php echo $this->lang->line("visit")." ".$this->lang->line("date");?></label>
				<input type="text" name="visit_date" id="visit_date" class="form-control" value="<?= date($def_dateformate, strtotime($visit['visit_date'])) ?>" />
				<?php echo form_error('visit_date','<div class="alert alert-danger">','</div>'); ?>
			</div>
			<div class="form-group">
				<label for="visit_time"><?php echo $this->lang->line("visit")." ".$this->lang->line("time");?></label>
				<input type="text" name="visit_time" id="visit_time" class="form-control" value="<?= date($def_timeformate, strtotime($visit['visit_time'])) ?>" />
				<?php echo form_error('visit_time','<div class="alert alert-danger">','</div>'); ?>
			</div>
			<div class="form-group">
				<label for="type"><?php echo $this->lang->line("type");?></label>
				<select name="type" class="form-control">
					<option <?php if ($visit['type'] == "New Visit") {echo 'selected = "selected"';} ?> value="New Visit"><?php echo $this->lang->line('new')." ".$this->lang->line('visit');?></option>
					<option <?php if ($visit['type'] == "Established Patient") {echo 'selected = "selected"';} ?> value="Established Patient"><?php echo $this->lang->line('established_patient');?></option>
				</select>
				<?php echo form_error('type','<div class="alert alert-danger">','</div>'); ?>
			</div>
			
			<div class="form-group">
				<label><?php echo $this->lang->line('reason');?></label>
				<input type="text" name="appointment_reason" id="appointment_reason" class="form-control" value="<?=$visit['appointment_reason'];?>" />
				<?php echo form_error('appointment_reason','<div class="alert alert-danger">','</div>'); ?>
			</div>
			
			<div class="form-group">
				 <label for="notes"><?php echo $this->lang->line("notes");?></label> 
				<textarea class="form-control" name="notes" rows=7><?= $visit['notes'] ?></textarea>
				<?php echo form_error('notes','<div class="alert alert-danger">','</div>'); ?>
			</div>
			<div class="form-group">
				 <label for="patient_notes"><?php echo $this->lang->line("notes_for_patient");?></label> 
				<textarea class="form-control" name="patient_notes" rows=7><?= $visit['patient_notes'] ?></textarea>
				<?php echo form_error('patient_notes','<div class="alert alert-danger">','</div>'); ?>
			</div>
			<?php if (in_array("treatment", $active_modules)) { ?>
			<div class="form-group">
				 <label for="visit_treatment" ><?php echo $this->lang->line("treatment");?></label>
				<select id="treatment" class="form-control" multiple="multiple" style="width:350px;" tabindex="4" name="treatment[]">
					<option value=""></option> 
					<?php            
					
					foreach ($treatments as $treatment) { 
					?>
						<option value="<?php echo $treatment['id'] . "/" . $treatment['treatment'] . "/" . $treatment['price'] ?>" 
							<?php 
								foreach ($visit_treatments as $visit_treatment) { 
									if($visit_treatment['particular'] == $treatment['treatment']) 
									{   
										echo 'selected=\'selected\'';                                
									}
								}
							?>
						>
						<?= $treatment['treatment']; ?></option>
					<?php 
					}
					?>
				</select>
				<?php echo form_error('notes','<div class="alert alert-danger">','</div>'); ?>
				<script>jQuery('#treatment').chosen();</script>
			</div>
			<?php } ?>
			<?php if (in_array("disease", $active_modules)) { ?>
			<div class="form-group">
				<label for="visit_treatment" ><?php echo $this->lang->line("diagnosed_diseases");?></label>
				<select id="disease" class="form-control" multiple="multiple" style="width:350px;" tabindex="4" name="disease[]">
					<option value=""></option> 
					<?php            
					
					foreach ($diseases as $disease) { 
					?>
						<option value="<?php echo $disease['disease_id'] ?>" 
							<?php 
								foreach ($visit_diseases as $visit_disease) { 
									if($visit_disease['disease_id'] == $disease['disease_id']) 
									{   
										echo 'selected=\'selected\'';                                
									}
								}
							?>
						>
						<?= $disease['disease_name']; ?></option>
					<?php 
					}
					?>
				</select>
				<?php echo form_error('notes','<div class="alert alert-danger">','</div>'); ?>
				<script>jQuery('#disease').chosen();</script>
			</div>
			<?php } ?>
			<script src="<?= base_url() ?>js/chosen.jquery.js" type="text/javascript"></script>
			<script type="text/javascript"> 
				var config = {
					'.chzn-select'           : {},
					'.chzn-select-deselect'  : {allow_single_deselect:true},
					'.chzn-select-no-single' : {disable_search_threshold:10},
					'.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
					'.chzn-select-width'     : {width:"95%"}
				}
				for (var selector in config) {
					$(selector).chosen(config[selector]);
				}
			</script>
			<?php if (in_array("prescription", $active_modules)){ ?>
			<div class="col-md-12">
				<div id="prescription_list">
					<div class="col-md-12">
						<a href="#" id="add_medicine" class="btn btn-primary square-btn-adjust"><?=$this->lang->line('add_another_medicine');?></a>
						<input type="hidden" id="medicine_count" value="0"/>
					</div>
					<?php foreach($prescriptions as $medicine){
							if($medicine['medicine_id'] == 0){
								$medicine_id = "";
								$medicine_name = "";
							}else{
								$medicine_id = $medicine['medicine_id'];
								$medicine_name = $medicine_array[$medicine['medicine_id']];
							}
							
						?>
						<div class="col-md-2">	
							<label for="medicine" style="display:block;text-align:left;"><?=$this->lang->line('medicine');?></label>
							<input type="text" name="medicine_name[]" id="medicine_name" value="<?=$medicine_name;?>" class="form-control medicine_name"/>
							<input type="hidden" name="medicine_id[]" id="medicine_id" value="<?=$medicine_id;?>" class="form-control"/>
						</div>
						<div class="col-md-6">
							<label for="frequency" style="display:block;text-align:left;"><?=$this->lang->line('frequency');?></label>
							<div class="col-md-1">
								<?=$this->lang->line('morning');?>
							</div>
							<div class="col-md-3">
								<input type="text" name="freq_morning[]" id="freq_morning"  value="<?=$medicine['freq_morning'];?>" class="form-control"/>
							</div>
							<div class="col-md-1">
								<?=$this->lang->line('afternoon');?>
							</div>
							<div class="col-md-3">
								<input type="text" name="freq_afternoon[]" id="freq_afternoon" value="<?=$medicine['freq_afternoon'];?>" class="form-control"/>
							</div>
							<div class="col-md-1">
								<?=$this->lang->line('night');?>
							</div>
							<div class="col-md-3">
							<input type="text" name="freq_evening[]" id="freq_evening" value="<?=$medicine['freq_night'];?>" class="form-control"/>
							</div>
						</div>
						<div class="col-md-1">
							<label for="days" style="display:block;text-align:left;"><?=$this->lang->line('days');?></label>
							<input type="text" name="days[]" id="days" value="<?=$medicine['for_days'];?>" class="form-control"/>
						</div>
						<div class="col-md-2">
							<label for="prescription_notes" style="display:block;text-align:left;"><?=$this->lang->line('instructions');?></label>
							<input type="text" name="prescription_notes[]" value="<?=$medicine['instructions'];?>" id="prescription_notes" class="form-control"/>
						</div>
						<div class="col-md-1">	
							<label></label>
							<a href="<?= site_url('prescription/delete_prescription_medicine/'.$visit_id.'/'.$medicine['medicine_id']);?>" class="btn btn-danger btn-sm square-btn-adjust" >Delete</a>
						</div>
					<?php }?>
				</div>
			</div>
			<?php }?>
			<div class="col-md-12">
							<?php if(isset($section_master)){ ?>
								<script>
								<?php
									foreach($section_conditions as $section_condition){
										echo "$(document).on('change', '#".$field_name[$section_condition['field_name']]."', function() {\n";
										//Check Value of field
										if($section_condition['field_is_checked'] != NULL &  $section_condition['field_is_checked'] == 1){ //checked
											echo "if ($('#".$field_name[$section_condition['field_name']]."').is(':checked')) {";
										}elseif($section_condition['field_is_checked'] != NULL & $section_condition['field_is_checked'] == 0){ //unchecked
											echo "if (!$('#".$field_name[$section_condition['field_name']]."').is(':checked')) {";
										}elseif($section_condition['condition_type'] == 'has_value' ){ //has value
											echo "var flag = false;\n";
											echo "$('#".$field_name[$section_condition['field_name']].":checked').each(function() {\n";
											echo "  if(this.value == '".$section_condition['field_has_value']."'){\n";
											echo "	flag = true;\n";
											echo "	}\n";
											echo "});\n";
											echo "if(flag){\n";
											//echo "if ($('#".$field_name[$section_condition['field_name']]."').val() == '".$section_condition['field_has_value']."') {";
										}elseif($section_condition['condition_type'] == 'does_not_has_value' ){ //does not has value
											echo "var flag = true;\n";
											echo "$('#".$field_name[$section_condition['field_name']].":checked').each(function() {\n";
											echo "  if(this.value == '".$section_condition['field_has_value']."'){\n";
											echo "	flag = false;\n";
											echo "	}\n";
											echo "});\n";
											echo "if(flag){\n";
											//echo "if ($('#".$field_name[$section_condition['field_name']]."').val() == '".$section_condition['field_has_value']."') {";
										}
										
										//Change Status of field
										if($section_condition['change_status_to'] == 'enabled'){
											echo "$('#".$field_name[$section_condition['change_status_of_field']]."').parent().show();";
											echo "$('#".$field_name[$section_condition['change_status_of_field']]."').prop('disabled', false);";
										}elseif($section_condition['change_status_to'] == 'disabled'){
											echo "$('#".$field_name[$section_condition['change_status_of_field']]."').parent().show();";
											echo "$('#".$field_name[$section_condition['change_status_of_field']]."').prop('disabled', true);";
										}elseif($section_condition['change_status_to'] == 'hidden'){
											echo "$('#".$field_name[$section_condition['change_status_of_field']]."').parent().hide();";
										
										}
										echo "}";
										echo "});";
									} ?>
								</script>
								<?php foreach($section_master as $section){ ?>
									<div class="section" data-department="<?=$section['department_id'];?>">
									<h3><?=$section['section_name'];?></h3>
									<?php $section_id = $section['section_id'];?>
									<?php foreach($section_fields as $field){?>
										<?php 
										if(isset($visit_history_details[$field['field_id']])){
											$value = $visit_history_details[$field['field_id']];
										}else{
											$value = "";
										}?>
										<?php
											$style="";
											$disabled="";
											if($field['field_status'] == 'hidden' ){
												$style="style='display:none;'";
											}
											if($field['field_status'] == 'disabled' ){
												$disabled=" disabled='disabled'";
											}
										?>
										<div class="form-group col-md-<?=$field['field_width'];?>" <?=$style;?>>
										<?php if($field['field_type'] == "header"){?>
											<h4><?=$field['field_label'];?></h4> 
										<?php }else{ ?>
											<label for="history_<?=$field['field_id'];?>"><?=$field['field_label'];?></label> 
										<?php } ?>
										<?php if($field['field_type'] == "text"){?>
											<input type="input" <?=$disabled;?> class="form-control" id="<?=$field['field_name'];?>" name="history_<?=$field['field_id'];?>" value="<?=$value?>"/>
										<?php }elseif($field['field_type'] == "date"){ ?>
											<input type="input" <?=$disabled;?> class="form-control datetimepicker" id="<?=$field['field_name'];?>" name="history_<?=$field['field_id'];?>" value="<?=$value?>"/>
										<?php }elseif($field['field_type'] == "combo"){ ?>
											<select id="<?=$field['field_name'];?>" class="form-control" <?=$disabled;?> name="history_<?=$field['field_id'];?>">
												<?php foreach($field_options as $field_option){ ?>
													<?php if($field_option['field_id'] == $field['field_id']){?>
													<option value="<?=$field_option['option_value'];?>" <?php if($field_option['option_value'] == $value ){echo "selected";}?>><?=$field_option['option_label'];?></option>
													<?php } ?> 
												<?php } ?> 
											</select>
										<?php }elseif($field['field_type'] == "checkbox"){ ?>
											<?php foreach($field_options as $field_option){ ?>
												<?php if($field_option['field_id'] == $field['field_id']){?>
													<label class="checkbox">
														<input id="<?=$field['field_name'];?>" <?=$disabled;?> type="checkbox" name="history_<?=$field['field_id'];?>[]" value="<?=$field_option['option_value'];?>" <?php if(strpos($value,$field_option['option_value']) !== FALSE ){echo "checked";}?> /><?=$field_option['option_label'];?>
													</label>
												<?php } ?> 
											<?php } ?> 
										<?php }elseif($field['field_type'] == "radio"){ ?>
											
											<?php foreach($field_options as $field_option){ ?>
												<?php if($field_option['field_id'] == $field['field_id']){?>
													<div class="radio">
														<label>
															<input id="<?=$field['field_name'];?>" <?=$disabled;?> type="radio" name="history_<?=$field['field_id'];?>" id="history_<?=$field['field_id'];?>" value="<?=$field_option['option_value'];?>" checked=""><?=$field_option['option_label'];?>
														</label>
													</div>
												<?php } ?> 
											<?php } ?> 
										<?php } ?> 
											<?php echo form_error($field['field_id'],'<div class="alert alert-danger">','</div>'); ?>
										</div>
									<?php } ?>
								<?php } ?>
								</div>
							<?php } ?>
						</div>
			<div class="col-md-12">
			<div class="form-group">
				<button class="btn btn-primary square-btn-adjust" type="submit" name="submit" /><?php echo $this->lang->line("save");?></button>
				<a class="btn btn-primary square-btn-adjust" href="<?php echo base_url()."index.php/appointment/change_status_visit/".$visit['visit_id']?>"><?php echo $this->lang->line("complete");?></a>
				<a class="btn btn-primary square-btn-adjust" href="<?php echo base_url() . "/index.php/patient/visit/" . $patient_id . "/" . $appointment_id; ?>"><?php echo $this->lang->line("cancel");?></a>
			</div>
			</div>
			</div>
		</div>
	</div>
</div>
			