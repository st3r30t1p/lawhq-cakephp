<?php
	$this->assign('title', 'Whois '.$domain);
	$this->set('wunder_title', 'Whois: '.$domain);
?>

<?php
	function onlyIntKeys(array $array) {
		return count(array_filter(array_keys($array), 'is_int')) == count($array);
	}

	function camelCase($str) {
		return ucwords(str_replace('_', '&nbsp;', $str));
	}

	function recursiveOutput($array, $html, $path = []) {
		$depth = count($path) + 1;
		$onlyIntKeys = onlyIntKeys($array);

		foreach ($array as $key => $val) {
			$niceKey = camelCase($key);
			$addedToPath = false;
			if (is_array($val) && onlyIntKeys($val)) {
				array_push($path, $key.'[*]');
				$addedToPath = true;
			} else if (!$onlyIntKeys) {
				array_push($path, $key);
				$addedToPath = true;
			}
			echo "<tr>\n";
			if (is_array($val)) {
				echo "<td style=\"padding-left:" . (2 * $depth) . "em\"><b>{$niceKey}</b></td>\n";
				echo "<td>&nbsp;</td>\n";
				recursiveOutput($val, $html, $path);
			} else {
				echo "<td style=\"padding-left:" . (2 * $depth) . "em\">{$niceKey}</td>\n";
				$isString = is_string($val);
				$padded = json_encode($val);
				echo "<td>" . $html->link($val, ['action' => 'search', 'jspath' => implode('.', $path), 'val' => $padded]) . "</td>\n";
			}
			echo "</tr>";
			if ($addedToPath) {
				array_pop($path);
			}
		}
	}
?>

<h1 class="title is-1">Whois: <?php echo $domain; ?></h1>

<div class="even-spacing"><?php echo $this->Html->link('Re-request Whois', ['id' => $domain, '?' => ['refresh' => 1]], ['class' => 'button is-green']); ?></div>

<?php
	foreach ($domainDetails as $domainDetail) {
		$whois = $domainDetail->info['data'];
		unset($whois['appraise']);
		unset($whois['web']);
		unset($whois['domain_sld_taken']);
?>
<h2 class="title is-2">Domain IQ on <?= $domainDetail->created->format('n/j/Y g:ia'); ?></h2>
<table class="table is-striped" style="width:auto;font-size:smaller;">
<?php recursiveOutput($whois, $this->Html); ?>
</table>
<?php } ?>

<style type="text/css">
.table td, .table th {
    border: none;
    padding: 5px;
}
</style>