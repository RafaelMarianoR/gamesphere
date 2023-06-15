<?php

namespace Components;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';
/*
<div class="position-absolute top-0 start-0"> 		< 	/\ 	</div>
<div class="position-absolute top-0 end-0">			/\ 	/\	</div>
<div class="position-absolute top-50 start-50"> 	. 	. 	</div>
<div class="position-absolute bottom-50 end-50"> 	. 	. 	</div>
<div class="position-absolute bottom-0 start-0">	\/	< </div>
<div class="position-absolute bottom-0 end-0"></div>
*/

class Notify
{
	public function renderNotify($notifyData)
	{
		$delay = isset($notifyData['delay']) ? $notifyData['delay'] : '10000';
		$icon = isset($notifyData['icon']) ? $notifyData['icon'] : 'bi bi-info-lg';
		$title = isset($notifyData['title']) ? $notifyData['title'] : '';
		$text = isset($notifyData['text']) ? $notifyData['text'] : '';
?>
		<div id="mainToast" class="toast-container p-3 end-0 top-0" style="z-index: 99999;" data-bs-autohide="true" data-bs-delay="<?= $delay; ?>">
			<div class="toast fade show">
				<div class="toast-header">
					<strong class="me-auto">
						<i class="<?= $icon; ?> fs-6"></i>&nbsp;<?= $title; ?>
					</strong>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
				<div class="toast-body">
					<?= $text ?>
				</div>
			</div>
		</div>
<?php
	}
}
