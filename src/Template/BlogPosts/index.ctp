<?php
	$this->assign('title', 'Blog Posts');
?>

<ul class="flex even-spacing">
	<li>
		<h1 class="title is-4">Blog Posts</h1>
	</li>

	<div class="break" style="display:none"></div>

	<li>	
		<a class="button is-green is-small" href="<?= $this->Url->build(['action' => 'add']) ?>">
		   <span class="icon is-small">
		     <i class="fas fa-plus"></i>
		   </span>
		   <span>New Blog Post</span>
		 </a>
	</li>
</ul>

<div class="card table-container">
	<table class="table is-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th>Posted By</th>
			<th>State</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody style="font-size:smaller;">
		<?php foreach ($blogPosts as $post) { ?>
			<tr>
				<td><?= $this->Html->link($post->title, ['action' => 'edit', 'id' => $post->id]) ?></td>
				<td><?= $post->team_member->full_name ?></td>
				<td><?= ucfirst($post->state) ?></td>
				<td><?= date('m-d-y', strtotime($post->created)) ?></td>
			</tr>
		<?php } ?>
	</tbody>
	</table>
</div>