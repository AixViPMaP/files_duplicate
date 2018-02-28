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

namespace OCA\Files_Duplicate\AppInfo;

use \OCP\AppFramework\App;
use \OCP\IContainer;
use \OCP\Util;

// Register global frontend scripts and styles
Util::addScript('files_duplicate', "files_duplicate");

// Register hook
class Application extends App {
  public function __construct(array $urlParams=array()) {
    parent::__construct('files_duplicate', $urlParams);

    $container = $this->getContainer();
    $container->registerService('userFolder', function (IContainer $c) {
      return $c->query('ServerContainer')->getUserFolder();
    });
  }
}
