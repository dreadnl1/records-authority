<?php
/**
 * Copyright 2011 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@du.edu and fernando.reyes@du.edu
 * 
 * This file is part of Records Authority.
 * 
 * Records Authority is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Records Authority is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Records Authority.  If not, see <http://www.gnu.org/licenses/>.
 * 
 **/
?>

<?php $this->load->view("includes/searchHeader"); echo $unitScript; ?>
	
	<div id="searchContent">
		<h3 id="title">Search the Records Retention Schedule</h3>
		<br />
		<div id="searchFullTextForm">
			<div class="searchFormFullText">
				<form id="searchRetentionSchedules" method="post" action="<?php echo site_url();?>/du/searchByDepartment">
					<select id='divisions' name='divisionID' size='1' class='required'> 
						<option value='' selected='selected'>Select a division</option>
						<option value=''>--------------------</option>
						<?php 
							foreach ($divisions as $divisionID => $divisionName) {
								echo "<option value='$divisionID'>$divisionName</option>";
							}
						?>
					</select>
					
					<select id='departments' name='departmentID' size='1' class='required'>
						<option value=''>Select a department</option>
					</select>
					&nbsp;&nbsp;
					<br />
					<input name="searchRetentionSchedules" type="submit" value="Search" class="button"/>
				</form>
				<?php 
				//Search All								
					$attributes = array('id' => 'searchAllRetentionSchedules');
					echo "<div class='searchByDept-div'>";
					echo "<div class='leftFloat'>";
					echo "<h3 id='title'>Full Schedule</h3>";
					echo form_open('/du/fullTextSearch', $attributes);
					echo form_hidden('keyword', '*');
					
					$inputData = array(	'name' 	=> 'searchAllRetentionSchedules', 
										'class' => 'buttonLink',
								);
					echo form_submit($inputData, 'Full Schedule');
					//echo form_submit('searchRetentionSchedules', 'Search All');
					echo br();
					$siteUrl = site_url();
					echo "<a href='$siteUrl/export/transform/999999/public'>Print Full Schedule</a>";
					echo form_close();
					echo "</div>";
					echo "<div class='rightFloat'>";
				?>
						<h3 id="title">About the Schedule</h3>
						<?php
							echo anchor_popup('http://www.du.edu/bfa/records/retention_sched.html', 'Retention Schedule FAQ', $searchPopUp);
							echo br();
							echo "<a href='$siteUrl'>Records Authority Survey</a>";
							echo br(3);
							//echo "<span class='question'>";
							//echo anchor_popup('http://www.du.edu/media/forms/recordsmanagement/contact_us2.html', 'Have a question?', $searchPopUp);
							//echo "</span>";
						?>
					</div>
						<h3 id="title"><br />Browse the Schedule</h3>
						<a href="<?php echo site_url();?>/du/retentionSchedules/fullText">Full-Text Search</a>
						<br />
						<a href="<?php echo site_url();?>/du/retentionSchedules/browseByDepartment">Browse by Department</a>
						<br /><br /><br />
						<?php
							echo "<span class='question'>";
							echo anchor_popup('http://www.du.edu/media/forms/recordsmanagement/contact_us2.html', 'Have a question?', $searchPopUp);
							echo "</span>";
						?>
						<br />
					<!-- <br />
					<a href='http://library.du.edu/site/about/urmp/glossaryURMP.php' target='_blank' rel='shadowbox'>What do these codes mean?</a><br />
					<br />-->
				</div>
				<?php /*<div class="searchByDept-div">
					<h3 id="title">Browse the Schedule</h3>
					<a href="<?php echo site_url();?>/du/retentionSchedules/fullText">Full-Text Search</a>
					<br />
					<a href="<?php echo site_url();?>/du/retentionSchedules/recordCategory">Browse by Functional Category</a>
					<br />
					<h3 id="title">About the Schedule</h3>
					<?php
						echo anchor_popup('http://www.du.edu/bfa/records/retention_sched.html', 'Retention Schedule FAQ', $searchPopUp);
						echo br(3);
						echo anchor_popup('http://www.du.edu/media/forms/recordsmanagement/contact_us2.html', 'Have a question?', $searchPopUp);
					?>
					<!-- <br />
					<a href='http://library.du.edu/site/about/urmp/glossaryURMP.php' target='_blank'>What do these codes mean?</a><br />
					<br />-->
				</div>*/?>
				</div>
			</div>
		</div>
	</div>
	<!--<div id="divDeptRetentionScheduleSearchResults"></div>-->
	<div id="retentionScheduleSearchResults"></div>
<?php $this->load->view("includes/footer"); ?>