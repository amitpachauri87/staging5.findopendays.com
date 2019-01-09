<?php
use QiblaFramework as QF;

/**
 * Base Requirements
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// Base General Admin/Front Functions.
require_once QF\Plugin::getPluginDirPath('/src/Functions/AfterSetupTheme.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/WpBackward.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Array.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Conditionals.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/General.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Wpml.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Formatting.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Kses.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Query.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Post.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Term.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Filesystem.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Media.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Setting.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Archive.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Sidebar.php');
require_once QF\Plugin::getPluginDirPath('/src/Functions/Templates.php');
