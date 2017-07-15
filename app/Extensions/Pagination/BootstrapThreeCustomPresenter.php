<?php

namespace App\Extensions\Pagination;

use Illuminate\Pagination\BootstrapThreePresenter;

class BootstrapThreeCustomPresenter extends BootstrapThreePresenter
{
	/**
	 * Convert the URL window into Bootstrap HTML.
	 *
	 * @return string
	 */
	public function render()
	{
		if ($this->hasPages())
		{
			return sprintf(
				'<ul class="pagination">%s %s %s</ul>',
				$this->getPreviousButton('<i class="icon-simply-left"></i>'),
				$this->getLinks(),
				$this->getNextButton('<i class="icon-simply-right"></i>')
			);
		}

		return '';
	}
}
