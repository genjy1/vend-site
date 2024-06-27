<div id="tags_cloud"></div>

<script type="text/javascript">
	var tags = [];
	<?php if ($tags) { ?>
		<?php foreach ($tags as $key => $tag) { ?>
			tags.push({
				text: "<?php echo $tag['name']; ?>", 
				weight: "<?php echo $tag['count']; ?>", 
				link: "<?php echo str_replace('&amp;','&',$tag['href']); ?>"
			});
		<?php } ?>
		$(function(){
			$("#tags_cloud").jQCloud(tags);
		})
	<?php } else { ?>
		$("#tags_cloud").remove();
	<?php } ?>
</script>