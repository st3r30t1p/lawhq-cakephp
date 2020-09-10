<?= $this->Html->script('/ckeditor/ckeditor.js') ?>

<h1 class="title is-4"><?= $blogPost->state() . ' ' . $blogPost->title ?></h1>


<div>
	<?= $this->Form->create($blogPost, ['type' => 'file']);
		$this->Form->setTemplates([
		    'inputContainer' => '{{content}}',
		    'input' => '<input type="{{type}}" autocomplete="false" name="{{name}}"{{attrs}}/>'
		]); 
	?>

	<div class="columns">
		<div class="column" style="max-width:680px">
			<?= $this->Form->textarea('body', ['id' => 'editor1', 'rows' => '10', 'cols' => '80']) ?>
		</div>

		<div class="column" style="max-width:500px">

			<div class="field">
			  <label class="label">Title</label>
			  <div class="control">
				<?= $this->Form->text('title', ['label' => false, 'class' => 'input is-small', 'placeholder' => 'Title', 'required' => 'required']) ?>
			  </div>
			</div>

			<div class="field">
			  <label class="label">Author</label>
			  <div class="control">
			    <div class="select is-fullwidth">
					<?= $this->Form->select('user_id', $users, ['label' => false, 'type' => 'select', 'class' => 'select is-small', 'default' => '2', 'required' => 'required']); ?>
			    </div>
			  </div>
			</div>

			<div class="field">
			  <label class="label">Status</label>
			  <div class="control">
			    <div class="select is-fullwidth">
			    	<?= $this->Form->select('state', ['draft' => 'Draft', 'inactive' => 'Inactive', 'published' => 'Published'], ['label' => false, 'type' => 'select', 'class' => 'select is-small']); ?>
			    </div>
			  </div>
			</div>

			<div class="field">
				<label class="label" style="margin: 0;">Cover Image</label>
				<p style="margin-bottom: 5px;font-size:12px">1200 x 628 (1080 x 1080 for a 1:1 image)</p>
				<?= $this->Form->file('cover_img'); ?>
			</div>

			<?php if ($blogPost->getSha1()) { ?>
				<img src="<?php echo $this->Url->build(['controller' => 'files', 'action' => 'get', $blogPost->getSha1()]); ?>">
			<?php } ?>
			
		</div>
	</div>
	<?php $blogPost->getSha1(); ?>
	<div>
		<?= $this->Form->submit('Save', ['class' => 'button is-green is-small']) ?>
	</div>
	<?= $this->Form->end() ?>
</div>

<script>
CKEDITOR.replace( 'editor1', 
{
	height: '500px',
	filebrowserUploadUrl: '<?php echo $this->Url->Build(["action" => "upload-image"]) ?>'
});

// CKEDITOR.config.extraPlugins = 'uploadwidget';
// CKEDITOR.config.extraPlugins = 'uploadimage';
// CKEDITOR.config.uploadUrl = '<?php echo $this->Url->Build(["action" => "upload-image"]) ?>';
</script>