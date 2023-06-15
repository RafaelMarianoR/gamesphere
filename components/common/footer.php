<?php

namespace Components\Common;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

use Components\notify;
use  Configs\Constants\URL;

class Footer
{
	public static function renderHTML($pageParams = [])
	{ ?>
		<!-- Modal de Loading -->
		<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="text-center">
							<div class="spinner-border m-3" role="status">
							</div>
							<h3 class="m-3">Carregando...</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		if (isset($_SESSION['notify'])) {
			$notify = new notify();
			$notify->renderNotify($_SESSION['notify']);
		}
		?>
		<footer origin="<?= __FUNCTION__ ?>()" class="fixed-bottom mt-auto py-3 bg-dark">
			<div class="container-fluid">
				<img src="<?= URL::root(); ?>/assets/images/estacio.png" height="32px" class="float-right">
				<p class="m-0 text-light float-end text-end fs-7">
					Rafael Mariano Rodrigues<br />
					<span class="text-white-50 fs-8">201803490471</span>
				</p>
			</div>
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
			<script src="<?= URL::root(); ?>/assets/js/ajax.js?version=<?= rand(0, 150) . "." . rand(0, 100) . "." . rand(0, 15) ?>'"></script>

			<?php if (isset($pageParams['links']['js'])) { ?>
				<?php foreach ($pageParams['links']['js'] as $key => $value) { ?>
					<script id="<?= $key; ?>" src="<?= $value; ?>"></script>
				<?php } ?>
			<?php } ?>
			<script>
				const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
				const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

				<?php if (isset($_SESSION['notify'])) { ?>
					var myToast = new bootstrap.Toast(document.getElementById('mainToast'));
					myToast.show();
					<?php unset($_SESSION['notify']); ?>
				<?php } ?>
			</script>
		</footer>
		</body>

		</html>
<?php
	}
}
