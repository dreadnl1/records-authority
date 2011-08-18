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
 **/
?>

<?php 
	$data['title'] = 'Functional Category - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>

<div id="tabs">
	<ul>
    	<li class="ui-tabs-nav-item"><a href="#fragment-1">Add Functional Category</a></li>
    </ul>
    
    <div id="fragment-1">
    	<div class="adminForm">

			<form name="addRecordCategory" method="post" action="<?php echo site_url();?>/upkeep/save">
				<select id='recordCategories' name='recordCategoryID' size='1'>
					<option value=''>Current Functional Categories</option>
					<option value=''>-----------------</option>
					<?php 
						foreach ($recordCategories as $id => $recordCategories) {
							echo "<option value='$id'>$recordCategories</option>";
						}
					?>
				</select>
				<input name="recordCategory" type="text" size="30" />
				<input name="submit" type="submit" value="Save" /> 
			</form>
			<?php if (isset($recordSaved)) { echo $recordSaved; } ?>
		</div>
    </div>
</div>
 
<?php $this->load->view('includes/adminFooter'); ?>
