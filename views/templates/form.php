<?php if( is_array($variable)): ?>


	<?php if(isset($msgform["error"]) && $msgform["error"]):?>

		<div class="formerror">

		<?php foreach ($msgform["msg"] as $msg):?>
			<li><?php echo $msg;?>
		<?php endforeach;?>

		</div>

	<?php endif;?>



	<form method="<?php echo $variable['config']['method'] ;?>" action="<?php echo "/".UNIX_PATH.$variable['config']['action'] ;?>">

		<?php foreach ($variable['data'] as $name => $options): ?>

			<div class="form-group">
			<?php if( $options["type"] === "email" || $options["type"] === "password" 
					|| $options["type"] === "text"  || $options["type"] === "number" ):?>

				<input type="<?php echo $options["type"]?>" 
						name="<?php echo $name?>" 
						class="form-control"
						value="<?php echo ( isset($post[$name]) && $options["type"] != "password")?$post[$name]:"";?>"
						placeholder="<?php echo $options['placeholder']?>"
						<?php echo  ($options["required"])?"required":""; ?>
						>

			<?php elseif($options["type"] === "select"):?>

				<select name="<?php echo $name?>" class="form-control"> 
					<?php foreach ($options["options"] as $value => $text): ?>
						<option value="<?php echo $value;?>" 
						<?php echo ( isset($post[$name]) && $post[$name]==$value)?"selected='selected'":"";?>
						><?php echo $text;?></option>
					<?php endforeach; ?>

				</select>

			<?php elseif($options["type"] === "submit"):?>

				<input type="<?php echo $options["type"]?>"
						value="<?php echo $options['value']?>"
						 class="btn btn-primary"
						>

			<?php elseif($options["type"] === "captcha"):?>

				<iframe src="<?php echo WEB_PATH?>captcha.php" class="formCaptcha" style="width:160px; height:50px"></iframe>
				<i class="fa-refresh fa formCaptcha"></i>
				<input type="text" name="<?php echo $name?>">

			<?php endif;?>

			</div>

		<?php endforeach; ?>

	</form>

<?php endif;?>

