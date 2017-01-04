<?php require 'header.php'; ?>
<?php require '../controllers/ContestController.php'; 
$contest = new ContestController() ;

$concour = $contest->getDataContest($contest->getCurrentContest()['id']); ?>


	<header class="title-container">
		<h1 class="title"><?php var_dump($concour); ?></h1>
		<button class="open-modal-desc">Description</button>
	</header>

	<a href="upload_images.php">PARTICIPER</a>

	<div class="concours-container">
		<h2 class="concours-title">Nom concours</h2>
		<ul class="grid effect-1" id="grid">
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
			<li class="grid-item">
				<img class="visuel" src="http://www.w3schools.com/css/img_fjords.jpg" />
				<div class="hover-container">
					<p class="item-title">Item title</p>
					<a><i class="icon-heart"></i></a>
					<a><i class="icon-circle-up"></i></a>
				</div>
			</li>
		</ul>
	</div>

<?php require 'footer.php'; ?>