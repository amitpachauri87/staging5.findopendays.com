<?php
namespace QiblaFramework\Front\Settings;

use QiblaFramework\Functions as F;
use QiblaFramework\TemplateEngine as T;

/**
 * Class Front-end Settings Footer
 *
 * @since      1.0.0
 * @package    QiblaFramework\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa
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

/**
 * Class Footer
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Footer
{
    /**
     * Social Links
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function socialLinks()
    {
        // Want to show the social Links on footer?
        if ('on' !== F\getThemeOption('footer', 'social_links', true)) {
            return;
        }

        $socialsSettingsInstance = new Socials();
        $socialsSettingsInstance->socialsLinksTmpl();
    }

    /**
     * Copyright Text
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function copyrightText()
    {
        // Remove the default copyright hook, so we can show the custom one.
        remove_action('qibla_colophon', 'Qibla\\Functions\\copyright', 20);

        // Retrieve the Meta.
        $meta = F\getThemeOption('footer', 'copyright', true);

        if (! $meta) {
            return;
        }

        // Set the data for the view.
        $data = new \stdClass();
        // Set the content data.
        $data->content = $meta;

        $engine = new T\Engine('footer', $data, 'views/footer/copyright.php');
        $engine->render();
    }

    /**
     * Google Analytics
     *
     * Show the google analytics code
     *
     * @since  1.0.0
     */
    public static function googleAnalytics()
    {
        // Retrieve the meta.
        $meta = sanitize_text_field(F\getThemeOption('footer', 'google_analytics'));

        if (! $meta) {
            return;
        }

        print <<<SCRIPT
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '$meta', 'auto');
ga('send', 'pageview');
</script>
SCRIPT;
    }
}
