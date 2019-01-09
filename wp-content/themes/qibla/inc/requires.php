<?php
/**
 * Base Requires
 *
 * File is stored under /inc/ directory to not waste the theme dir.
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
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

require_once Qibla\Theme::getTemplateDirPath('/src/Functions/WpBackward.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Array.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Conditionals.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/General.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Formatting.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Kses.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Media.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/TemplateTags.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Sidebars.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/AfterSetupTheme.php');

// Front
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Head.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Header.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Archives.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Posts.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Embed.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Comments.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Footer.php');
require_once Qibla\Theme::getTemplateDirPath('/src/Functions/Extras.php');
// Require here the mobile detect class, we use that in every page.
require_once Qibla\Theme::getTemplateDirPath('/libs/mobile-detect/Mobile_Detect.php');

// Admin
// Require the TGM Plugin Activation.
require_once Qibla\Theme::getTemplateDirPath('/libs/tgmpa/class-tgm-plugin-activation.php');
