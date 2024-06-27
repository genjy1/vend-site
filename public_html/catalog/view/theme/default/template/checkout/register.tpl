<?php foreach ($custom_fields as $custom_field) { ?>
<?php if ($custom_field['location'] == 'account') { ?>
  <div class="field">
    <label><?php echo $custom_field['name']; ?><span class="req">*</span></label>
    <input name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]">
  </div>
<? } ?>
<? } ?>
<script>
  $(".field, .custperson, .addr").show();
</script>