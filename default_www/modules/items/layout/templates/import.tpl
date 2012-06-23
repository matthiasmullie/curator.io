{include:'{$CORE_PATH}/layout/templates/head.tpl'}

{form:import}
	{$fileCsv} {$fileCsvError}
	<input type="submit" value="Submit" />
{/form:import}