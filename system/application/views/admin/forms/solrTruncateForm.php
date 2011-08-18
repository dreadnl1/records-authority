<?php
/**
 * Copyright 2011 University of Denver--Penrose Library--University Records Management Program
 * Author evan.blount@du.edu and fernando.reyes@du.edu
 * 
 * This file is part of Liaison.
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
	$data['title'] = 'Department - Records Authority';
	$this->load->view('includes/adminHeader', $data); 
?>

<p>Are you sure you want to delete the public search index</p>

<form action="<?php echo base_url();?>solr/solrTruncate.php" method="post">
    <input type="submit" name="yes" value="yes" />
    <input type="submit" name="no" value="no" />
</form>

<?php $this->load->view('includes/adminFooter'); ?>
