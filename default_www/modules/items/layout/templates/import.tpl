{include:'{$CORE_PATH}/layout/templates/head.tpl'}

	<div id="container">
		{include:'{$CORE_PATH}/layout/templates/header.tpl'}

		{include:'{$CORE_PATH}/layout/templates/nav.tpl'}


		{form:import}
			<label for="csv">CSV</label> {$fileCsv} {$fileCsvError}
			<input type="submit" value="Import" />
		{/form:import}


		{include:'{$CORE_PATH}/layout/templates/footer.tpl'}
	</div>
</body>
</html>