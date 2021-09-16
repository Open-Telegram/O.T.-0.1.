
	<div class="row">
		<div class="col-sm-12">
				<h2 style="color: white;">Modules</h2>
				<?php foreach ($modules as $module_name => $functions) :?>
					<h3 style="color: white;">
						<?=$module_name;?>
						<button onclick="location.href='?route=modules/delete&name=<?=$module_name?>';" style="background-color: #636363;border-color: #563232;" class="btn btn-sm btn-danger">delete</button>
					</h3>
					<?php foreach ($functions as $key => $function) :?>
						<div class="panel panel-default" id="panel1">
							<div class=" text-center panel-heading">
								<h5><?=$function['name'];?></h5>
								<!-- <h5><?=$key;?></h5> -->
							</div>
							<div style="display: none;" class="panel-body">
								<p><?=$function['info']; ?></p>
							</div>
							<div  style="display: none;" class="panel-footer">
								<p>Creator name - <?=$function['creator_name']; ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
		</div>
	</div>