<?php $this->load->view('includes/adminHeader'); ?>

  <div id="tabs">
	<ul>
		<li class="ui-tabs-nav-item"><a href="#fragment-1">Surveys</a><li>
        </ul>

  <div id="fragment-1">
  <div class="adminForm">

	<?php echo $surveyResults; ?>

  </div>
  </div>
  </div>

<?php $this->load->view('includes/adminFooter'); ?>
