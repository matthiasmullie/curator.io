{include:'{$CORE_PATH}/layout/templates/head.tpl'}
{include:'{$CORE_PATH}/layout/templates/header.tpl'}
	<div id="main">
		<div class="container">
		{form:import}
			<label for="csv">CSV</label> {$fileCsv} {$fileCsvError}
			<input type="submit" value="Import" />
		{/form:import}
		</div>
	</div>
{include:'{$CORE_PATH}/layout/templates/footer.tpl'}