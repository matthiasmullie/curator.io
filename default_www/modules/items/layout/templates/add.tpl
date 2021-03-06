{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
			{form:add}
			
				{option:formAddHasError}<div class="message notice"><p>{$errGeneralFormError}</p></div>{/option:formAddHasError}
			
				<fieldset>
					<p class="mobileField{option:txtNameError} errorArea{/option:txtNameError}">
						<label for="name">Name<abbr title="{$msgRequired}">*</abbr></label>
						{$txtName} {$txtNameError}
					</p>
					<p class="mobileField{option:txtDescriptionError} errorArea{/option:txtDescriptionError}">
						<label for="description">Description</label>
						{$txtDescription} {$txtDescriptionError}
					</p>
					<p class="customFields">
						{iteration:custom}
						<div class="oneLiner">
							<p><input class="customKey inputText smallInput" type="text" name="names[]" placeholder="Custom Field Name" value="{$custom.name}" /></p>
							<p><input class="customValue inputText smallInput" type="text" name="values[]" placeholder="Custom Field Value" value="" /></p>
							<p><a href="#" class="deleteCustom" style="font-size: 11px; line-height: 22px;">Delete</a></p>
						</div>
						{/iteration:custom}
						<a href="#" id="addCustom" class="button">Add Custom field</a>
					</p>
					<p>
						<label for="image">Image</label>
						{$fileImage} {$fileImageError}
					</p>
					<p>
						<label for="publishToFacebook">{$chkPublishToFacebook} Publish to Facebook</label>
					</p>
				</fieldset>

				<p class="buttonHolder">
					<input type="submit" value="Add" class="inputSubmit bigSubmit" />
				</p>
			{/form:add}
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}