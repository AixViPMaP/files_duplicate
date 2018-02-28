<?php
/**
 * @author Lukas Koschmieder <lukas.koschmieder@rwth-aachen.de>
 * @author Alper Topaloglu <alper.topaloglu@rwth-aachen.de>
 * @copyright Copyright (c) 2017-18 RWTH Aachen University
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

namespace OCA\Files_Duplicate\Controller;

use \OCP\IRequest;
use \OCP\AppFramework\Controller;
use \OCP\Files\NotPermittedException;

class AppController extends Controller {
	private $userId;
	private $userFolder;

	public function __construct($appName, IRequest $request, $userId, $userFolder){
		parent::__construct($appName, $request);
		$this->userId = $userId;
		$this->userFolder = $userFolder;
	}

	/**
	 * @NoAdminRequired
	 */
	public function duplicate($dirname, $basename) {
		$srcInternalPath = $dirname . "/" . $basename;
		$dstInternalPath = $this->userFolder->getNonExistingName($srcInternalPath);

		$srcNode = $this->userFolder->get($srcInternalPath);
		$dstFullPath = $this->userFolder->getFullPath($dstInternalPath);

		try {
			$dstNode = $srcNode->copy($dstFullPath);
			$dstNode = $srcNode = $this->userFolder->get($dstInternalPath);

			$fileInfo = array(
				'id'          => $dstNode->getId(),
				'name'        => $dstNode->getName(),
				'etag'        => $dstNode->getEtag(),
				'mimetype'    => $dstNode->getMimetype(),
				'size'        => $dstNode->getSize(),
				'mtime'       => $dstNode->getMtime(),
				'type'        => $dstNode->getType(),
				'permissions' => $dstNode->getPermissions(),
			);
		} catch (NotPermittedException $exception) {
			return array('success' => false, 'message' => 'Permission denied');
		}
		return array('success' => true, 'message' => $dstInternalPath, 'info' => $fileInfo);
	}
}
