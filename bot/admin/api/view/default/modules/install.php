<div class=" row">
	<?php foreach($modules as $module):?>
		<div class="module col-sm-5">
			<div class="module_h2 row">
				<div class=" col-sm-12 text-center">
					<h2><?=$module['name'];?></h2>
				</div>
			</div>
			<div class="module_descr row">
				<div style="color: white;" class="col-sm-12 text-center">
					<span><?=$module['descr'];?></span>
				</div>
			</div>
			<div class=" row">
				<div class="col-sm-12 text-center">
					<?php if($module['free']): ?>
					<button onclick="location.href = '?route=modules/download&module_id=<?=$module['id']?>';" class="btn btn-block btn-success">Download</button>
					<?php else: ?>
					<button  onclick="location.href = '?route=modules/download&module_id=<?=$module['id']?>';" class="btn btn-block btn-info">Buy</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>